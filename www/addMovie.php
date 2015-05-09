<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="./proj1c.css">
		<title>CS143 Project 1C: IMDB Query Tool</title>
	</head>
	<body>
		<div class="section" id="sidebar">
			<div id="heading">
				<h1>Welcome to the UCLA</h1>
				<h1>Movie Database!</h1>
			</div>
			<div id="tabs">
				<div class="pagelink"><a href="./addActor.php">Add an actor to our database</a></div>
				<div class="pagelink"><a href="./addMovie.php">Add a movie to our database</a></div>
				<div class="pagelink"><a href="./addMovieActor.php">Add an actor to a movie</a></div>		
				<div class="pagelink"><a href="./findActor.php">Actor profile</a></div>
				<div class="pagelink"><a href="./findMovie.php">Movie profile</a></div>
				<div class="pagelink"><a href="./search.php">General search</a></div>
			</div>
		</div>	
		<div class="section" id="content">
		<h1>Add Movie</h1>
		<p>Add a new movie by filling in the following fields.</p>
		<hr>
		<form action="#" method="GET" id="queryform">
			Title<b>:</b> <input type="text" name="title"><br>
			Company<b>:</b> <input type="text" name="company"><br>
			Year<b>:</b> <input type="text" name="year"><br>
			MPAA Rating<b>:</b> <select name="rating">
				<option value=""></option>
				<option value="G">G</option>
				<option value="PG">PG</option>
				<option value="PG-13">PG-13</option>
				<option value="R">R</option>
				<option value="NC-17">NC-17</option>
			</select><br>
			Genre<b>:</b> 			
			<input type="checkbox" name="genre[]" value="Action">Action</input>
			<input type="checkbox" name="genre[]" value="Adult">Adult</input>
			<input type="checkbox" name="genre[]" value="Adventure">Adventure</input>
			<input type="checkbox" name="genre[]" value="Animation">Animation</input>
			<input type="checkbox" name="genre[]" value="Comedy">Comedy</input>
			<input type="checkbox" name="genre[]" value="Crime">Crime</input>
			<input type="checkbox" name="genre[]" value="Documentary">Documentary</input>
			<input type="checkbox" name="genre[]" value="Drama">Drama</input>
			<input type="checkbox" name="genre[]" value="Family">Family</input>
			<input type="checkbox" name="genre[]" value="Fantasy">Fantasy</input>
			<input type="checkbox" name="genre[]" value="Horror">Horror</input>
			<input type="checkbox" name="genre[]" value="Musical">Musical</input>
			<input type="checkbox" name="genre[]" value="Mystery">Mystery</input>
			<input type="checkbox" name="genre[]" value="Romance">Romance</input>
			<input type="checkbox" name="genre[]" value="Sci-Fi">Sci-Fi</input>
			<input type="checkbox" name="genre[]" value="Short">Short</input>
			<input type="checkbox" name="genre[]" value="Thriller">Thriller</input>
			<input type="checkbox" name="genre[]" value="War">War</input>
			<input type="checkbox" name="genre[]" value="Western">Western</input>		   
			<hr>
			<input type="submit" name="submit" value="Run" id="submit">			
		</form>

			<?php	
			if (isset( $_GET['submit'] ) ) {		
				$required = array('title','company','year','rating','genre');
				$error=false;
				foreach($required as $req){
					if(!isset($_GET[$req])){
						$error=true;		
					}
					if($req=='rating' && $_GET[$req]=='')
					{
						$error=true;
					}
				}

				if($error==true){
					echo "<div class='errorMessage normaltext'>Form validation error: missing field</div>";
				}else if(strlen($_GET['year'])!=4 || is_numeric($_GET['year'])==FALSE){
					echo "<div class='errorMessage normaltext'>Year field should be a 4 digit number</div>";
				}else{
					//Establish connection to msql server and select database to work from
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
					
					//Query the current MaxMovieID
					$sql_query='select * from MaxMovieID';
					$query_result=mysql_query($sql_query, $db_connection) or die("Database access error: please try again!");
					$row=mysql_fetch_row($query_result);
					$newMaxMID=$row[0]+1;

					//Insert new Movie into database	
					$sql_query="insert into Movie values(" . $newMaxMID . ",'" . $_GET["title"] . "'," 
						. $_GET["year"] . ",'" . $_GET["rating"] . "','" . $_GET['company'] . "')";
					$query_result=mysql_query($sql_query, $db_connection) or die("<div class='errorMessage'>Database update error: please try again later</div>");

					//Update MaxMovieID on insert success
					$sql_query='update MaxMovieID set id=id+1';
					$query_result=mysql_query($sql_query, $db_connection);
					if(!$query_result){
						do{
							$sql_query='delete from Movie where id=' . $newMaxMID;
							$del_result=mysql_query($sql_query,$db_connection);
						}while(!$del_result);
						die("<div class='errorMessage normaltext'>Database update error: please try again later</div>");
					}

					//Insert new MovieGenre tuples into database
					foreach($_GET['genre'] as $newgenre)
					{
						$sql_query="insert into MovieGenre values(" . $newMaxMID . ",'" . $newgenre . "')";
						$query_result=mysql_query($sql_query,$db_connection);
					}

					//Display resulting tuple on insert success
					echo "<div class='successMessage'>Added movie entry into our databases as:</div>";
					$sql_query='select id as "Movie ID", title as "title", year as "Year", rating as "Rating", company as "Company" from Movie where id=' . $newMaxMID;
					$query_result=mysql_query($sql_query, $db_connection) or die("Query failure");
					$num_fields=mysql_num_fields($query_result);
					echo '<table border=1 cellspacing=1 cellpadding=2><tr>';
					$cur_field=0;
					while($cur_field<$num_fields)
					{
						echo '<td><u>' . mysql_field_name($query_result,$cur_field) . '</u></td>';
						$cur_field++;
					}
					echo '</tr><tr>';
					while($row=mysql_fetch_row($query_result)){
						$n=0;
						while($n<$num_fields){
							echo '<td>' . $row[$n] . '</td>';
							$n++;
						}
						echo '</tr><tr>';
					}
					echo '</tr>';
					$sql_query='select mid as "Movie ID", genre as "Genre" from MovieGenre where mid=' . $newMaxMID;
					$query_result=mysql_query($sql_query, $db_connection) or die("Query failure");
					$num_fields=mysql_num_fields($query_result);
					echo '<table border=1 cellspacing=1 cellpadding=2><tr>';
					$cur_field=0;
					while($cur_field<$num_fields)
					{
						echo '<td><u>' . mysql_field_name($query_result,$cur_field) . '</u></td>';
						$cur_field++;
					}
					echo '</tr><tr>';
					while($row=mysql_fetch_row($query_result)){
						$n=0;
						while($n<$num_fields){
							echo '<td>' . $row[$n] . '</td>';
							$n++;
						}
						echo '</tr><tr>';
					}
					echo '</tr>';
					mysql_close($db_connection);
				}
			}
			?>
		</div>
	</body>
</html>