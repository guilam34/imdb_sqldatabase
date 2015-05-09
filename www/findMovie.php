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
					$movie=$_GET['link'];								
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
					$movie_query="select * from Movie where title='".$movie."'";
					$movie_result=mysql_query($movie_query, $db_connection) or die("Database access error: please try again!");				
					$row=mysql_fetch_row($movie_result);	
					$mid=$row[0];			
					echo "<h1>".$movie."</h1>";
					echo "<div class='normaltext'>Title: ".$row[1]."</div>";
					echo "<div class='normaltext'>Year: ".$row[2]."</div>";
					echo "<div class='normaltext'>Rating: ".$row[3]."</div>";
					echo "<div class='normaltext'>Company: ".$row[4]."</div>";
					$director_query="select * from Director where id in(select did from MovieDirector where mid=".$mid.")";
					$director_result=mysql_query($director_query, $db_connection) or die("Database access error: please try again!");				
					echo "<div class='normaltext'>Directors: ";
					$row=mysql_fetch_row($director_result);
					while($row){
						echo $row[2]." ".$row[1];
						if($row=mysql_fetch_row($director_result)){
							echo ", ";
						}
					}
					echo "</div>";
					echo "<div class='normaltext'>Genres: ";
					$genre_query="select * from MovieGenre where mid=".$mid;
					$genre_result=mysql_query($genre_query, $db_connection) or die("Database access error: please try again!");				
					$row=mysql_fetch_row($genre_result);
					while($row){
						echo $row[1];
						if($row=mysql_fetch_row($genre_result)){
							echo ", ";
						}
					}
					echo "</div>";
					echo "<br><div class='normaltext'><u>Cast:</u></div>";
					$actor_query="select * from Actor where id in (select aid from MovieActor where mid=".$mid.")";
					$actor_result=mysql_query($actor_query, $db_connection) or die("Database access error: please try again!");				
					while($row=mysql_fetch_row($actor_result)){	
						$role_query="select * from MovieActor where mid=".$mid." and aid=".$row[0];
						$role_result=mysql_query($role_query,$db_connection) or die("Database access error: please try again!");	
						$role=mysql_fetch_row($role_result);
						echo "<div class='normaltext'><a href='./findActor.php?link=".$row[2]." ".$row[1]."'>".$row[2]." ".$row[1]."</a> as ".$role[2]."</div>";
					}
					echo"<br><hr>";
				}else{
					echo "<h1>Movie Profile</h1>";
					echo "<div class='normaltext'>Title:</div>";
					echo "<div class='normaltext'>Year:</div>";
					echo "<div class='normaltext'>Rating:</div>";
					echo "<div class='normaltext'>Company:</div>";
					echo "<div class='normaltext'>Directors:</div>";
					echo "<div class='normaltext'>Genres:</div>";
					echo "<br><div class='normaltext'><u>Cast:</u></div>";
					echo"<br><br><hr>";
				}
			?>
			<form action="./search.php" method="GET" id='queryform'>
				Search for actor/movie<br>Search: <input type="text" name="search"><br>
				<input type="submit" name="submit" value="Run" id="submit">
			</form>
		</div>
	</body>
</html>