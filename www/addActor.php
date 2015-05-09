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
			<h1>Add Actor</h1>
			<p>Add a new actor by filling in the following fields.</p>
			<hr>
			<form action="#" method="GET" id="queryform">
				First name*<b>:</b> <input type="text" name="firstname"><br>
				Last name*<b>:</b> <input type="text" name="lastname"><br>
				Sex*<b>:</b> <input type="radio" name="sex" value="Male"> Male
				<input type="radio" name="sex" value="Female"> Female<br>
				Date of Birth (ex. 1975-08-30)*<b>:</b> <input type="text" name="dob"><br>
				Date of Death (ex. 2005-05-29)<b>:</b> <input type="text" name="dod"><br>			
				<hr>
				<input type="submit" name="submit" value="Run" id="submit">			
			</form>		
			<?php	

			if (isset( $_GET['submit'] ) ) {	
				$required = array('firstname','lastname','sex','dob');
				$error=false;
				foreach($required as $req){
					if(!isset($_GET[$req])){
						$error=true;		
					}
				}

				if($error==true){
					echo "<div class='errorMessage normaltext'>Form validation error: missing field</div>";
				}else if(preg_match('/^(\d{4})-(\d{2})-(\d{2})$/',$_GET['dob'])==false){
					echo "<div class='errorMessage normaltext'>Date of birth format is incorrect - please format as XXXX-XX-XX.</div>";
				}else if(!empty($_GET['dod']) && preg_match('/^(\d{4})-(\d{2})-(\d{2})$/',$_GET['dod'])==false ){
					echo "<div class='errorMessage normaltext'>Date of death format is incorrect - please format as XXXX-XX-XX.</div>";
				}else{
					//Establish connection to msql server and select database to work from
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
					
					//Query the current MaxPersonID
					$sql_query='select * from MaxPersonID';
					$query_result=mysql_query($sql_query, $db_connection) or die("Database access error: please try again!");
					$row=mysql_fetch_row($query_result);
					$newMaxPID=$row[0]+1;

					//Insert new Actor into database
					$bdate=date('Y-m-d',strtotime($_GET['dob']));
					if(!empty($_GET['dod'])){
						$ddate=date('Y-m-d',strtotime($_GET['dod']));
						$sql_query="insert into Actor values(" . $newMaxPID . ",'" . $_GET["lastname"] . "','" 
						. $_GET["firstname"] . "','" . $_GET["sex"] . "','" . $bdate . "','" . $ddate . "')";
					}else{
						$sql_query="insert into Actor values(" . $newMaxPID . ",'" . $_GET["lastname"] . "','" 
						. $_GET["firstname"] . "','" . $_GET["sex"] . "','" . $bdate . "'," . 'NULL' . ")";
					}
					$query_result=mysql_query($sql_query, $db_connection) or die("<div class='errorMessage'>Database update error: please try again later</div>");

					//Update maxPersonID on insert success
					$sql_query='update MaxPersonID set id=id+1';
					$query_result=mysql_query($sql_query, $db_connection);
					if(!$query_result){
						do{
							$sql_query='delete from Actor where id=' . $newMaxPID;
							$del_result=mysql_query($sql_query,$db_connection);
						}while(!$del_result);
						die("<div class='errorMessage normaltext'>Database update error: please try again later</div>");
					}

					//Display resulting tuple on insert success
					echo "<div class='successMessage'>Added actor entry into our databases as:</div>";
					//Get query from textarea and use it to make a query
					$sql_query='select id as "Actor ID", last as "Last Name", first as "First Name", sex as "Sex", dob as "Date of Birth", dod as "Date of Death" from Actor where id=' . $newMaxPID;
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