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
			<?php
				if(!empty($_GET['link'])){
					$actor=$_GET['link'];
					$actorparts=explode(' ', $actor);				
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
					$actor_query="select * from Actor where first='".$actorparts[0]."' and last='".$actorparts[1]."'";
					$actor_result=mysql_query($actor_query, $db_connection) or die("Database access error: please try again!");				
					$row=mysql_fetch_row($actor_result);	
					$aid=$row[0];			
					echo "<h2>".$actor."</h2>";
					echo "<div class='normaltext'>Name: ".$row[2]." ".$row[1]."</div>";
					echo "<div class='normaltext'>Sex: ".$row[3]."</div>";
					echo "<div class='normaltext'>Date of Birth: ".$row[4]."</div>";
					if($row[5]==NULL){
						echo "<div class='normaltext'>Date of Death: N/A</div>";
					}else{
						echo "<div class='normaltext'>Date of Death: ".$row[5]."</div>";
					}
					echo "<br><div class='normaltext'><u>Appears in:</u></div>";

					$movie_query="select * from Movie where id in (select mid from MovieActor where aid=".$row[0].")";
					$movie_result=mysql_query($movie_query, $db_connection) or die("Database access error: please try again!");				
					while($row=mysql_fetch_row($movie_result)){	
						$role_query="select * from MovieActor where mid=".$row[0]." and aid=".$aid;
						$role_result=mysql_query($role_query,$db_connection) or die("Database access error: please try again!");	
						$role=mysql_fetch_row($role_result);
						echo "<div class='normaltext'><a href='./findMovie.php?link=".$row[1]."'>".$row[1]." (".$row[2].")</a> as ".$role[2]."</div>";
					}
					echo"<br><hr>";
				}else{
					echo "<h1>Actor Profile</h1>";
					echo "<div class='normaltext'>Name:</div>";
					echo "<div class='normaltext'>Sex:</div>";
					echo "<div class='normaltext'>Date of Birth:</div>";
					echo "<div class='normaltext'>Date of Death:</div>";
					echo "<br><div class='normaltext'><u>Appears in:</u></div>";
					echo"<br><br><hr>";
				}
			?>
			<form action="./search.php" method="GET" id="queryform">
				Search for actor/movie<br>Search: <input type="text" name="search"><br>
				<input type="submit" name="submit" value="Run" id="submit">
			</form>
		</div>
	</body>
</html>