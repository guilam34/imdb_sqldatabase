<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="./proj1c.css"/>
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
			<h1>Add Actor to Movie</h1>				
			<hr>
			<?php
				$db_connection = mysql_connect("localhost", "cs143", "");
				mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
				$sql_query="select * from Actor order by first";
				$query_actor=mysql_query($sql_query, $db_connection) or die("Database access error: please reload the page");
				$sql_query="select * from Movie order by title";
				$query_movie=mysql_query($sql_query, $db_connection) or die("Database access error: please reload the page");
			?>
			<form action="#" method="GET" id="queryform">
				Actor<b>:</b> <select name='actor'>				
					<?php
						echo "<option value=''>Select an actor...</option>";						
						while($row=mysql_fetch_row($query_actor)){
							echo "<option name='actor' value='".$row[0]."'>".$row[2] ." ". $row[1] . " (".$row[4] ." - ". $row[5].")</option>";
						}
					?>
				</select><br>
				Movie<b>:</b> <select name='movie'>
					<?php
						echo "<option value=''>Select a movie...</option>";										
						while($row=mysql_fetch_row($query_movie)){
							echo "<option name='movie' value='".$row[0]."'>".$row[1] ." (" . $row[2] . ")" . "</option>";
						}
					?>
				</select><br>
				Role<b>:</b> <input type="text"	name="role">
				<hr>
				<input type="submit" name="submit" value="Run" id="submit">		
			</form>

			<?php	

			if (isset( $_GET['submit'] ) ) {	
				$required = array('actor','movie','role');
				$error=false;
				foreach($required as $req){
					if($req=='role' && empty($_GET[$req])){
						$error=true;		
					}
					if($req=='actor' && $_GET[$req]==''){
						$error=true;
					}
					if($req=='movie' && $_GET[$req]==''){
						$error=true;
					}
				}

				if($error==true){
					echo "<div class='errorMessage normaltext'>Form validation error: missing field</div>";
				}else{
					//Establish connection to msql server and select database to work from
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");

					//Insert new Actor into database
					$sql_query="insert into MovieActor values(" . $_GET['movie'] . "," . $_GET['actor'] . ",'" . $_GET['role'] . "')" ;
					$query_result=mysql_query($sql_query, $db_connection) or die("<div class='errorMessage normaltext'>Database update error: please try again later</div>");		
					echo "<div class='successMessage'>Add success!</div>";
					mysql_close($db_connection);
				}
			}
			?>
		</div>
	</body>
</html>