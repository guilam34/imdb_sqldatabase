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
			<form action="#" method="GET" id='queryform'>
				Search for actor/movie<br>Search: <input type="text" name="search"><br>
				<input type="submit" name="submit" value="Run" id="submit">
			</form>
			<?php			
				if(isset($_GET['submit']) && $_GET['search']!='')
				{
					$db_connection = mysql_connect("localhost", "cs143", "");
					mysql_select_db("CS143",$db_connection) or die("Unable to connect to server!");
					echo "<hr><div class='normaltext'><u>Actors:</u></div>";
					$search=$_GET['search'];
					$searchparts=explode(' ',$search);
					if(sizeof($searchparts)<3){
						$actor_query="select * from Actor order by first,last";
						$actor_result=mysql_query($actor_query, $db_connection) or die("Database access error: please try again!");				
						while($row=mysql_fetch_row($actor_result)){	
							$match=true;
							foreach($searchparts as $part){
								if(strpos(strtolower($row[1]),strtolower($part))===false && strpos(strtolower($row[2]),strtolower($part))===false){
									$match=false;
								}
							}
							if($match===true){
								echo "<div class='result normaltext' ><a href='./findActor.php?link=". $row[2]. " " . $row[1] ."'>" .$row[2]." ".$row[1]."</a></div>";							
							}
						}							
						echo "<br>";				
					}
					echo "<div class='normaltext'><u>Movies:</u></div>";
					$movie_query="select * from Movie order by title";
					$movie_result=mysql_query($movie_query,$db_connection) or die("Database access error: please try again!");
					while($row=mysql_fetch_row($movie_result)){
						$match=true;
						if(strpos(strtolower($row[1]),strtolower($search))===false){
							$match=false;
						}
						foreach($searchparts as $parts){
							if(strpos(strtolower($row[1]),strtolower($part))===false){
									$match=false;
								}
						}
						if($match===true){
							echo "<div class='result normaltext'><a href='./findMovie.php?link=".$row[1]."'>".$row[1]."</a></div>";
						}
					}
					mysql_close($db_connection);
				}				
			?>
		</div>
	</body>
</html>