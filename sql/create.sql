CREATE TABLE Movie(id INT,
				   title VARCHAR(100) NOT NULL,
				   year INT, 
				   rating VARCHAR(10), 
				   company VARCHAR(50), 
				   PRIMARY KEY(id),
				   CHECK(LENGTH(title)>=1)) ENGINE = INNODB;

--We make Movie ids a primary key, because we don't want more than one movie to
--share id numbers. 
--We want each entry in the table to also have a title of at least length 1 at
--the very least.

CREATE TABLE Actor(id INT, 
				   last VARCHAR(20) NOT NULL, 
				   first VARCHAR(20) NOT NULL, 
				   sex VARCHAR(6), 
				   dob DATE, 
				   dod DATE, 
				   PRIMARY KEY(id),
				   CHECK(LENGTH(last)>=1),
				   CHECK(LENGTH(first)>=1)) ENGINE = INNODB;
--We make Actor ids a primary key, because we don't want more than one actor to
--share id numbers.
--We want the minimum data provided for an actor to be a first and last name of
--at least length one.


CREATE TABLE Director(id INT, 
					  last VARCHAR(20) NOT NULL, 
					  first VARCHAR(20) NOT NULL, 
					  dob DATE, 
					  dod DATE, 
					  PRIMARY KEY(id),
					  CHECK(LENGTH(last)>=1),
				   	  CHECK(LENGTH(first)>=1)) ENGINE = INNODB;
--We make Director ids a primary key, because we don't want more than one 
--director to share id numbers.
--We want the minimum data provided for a director to be a first and last name
--of at least length one.


CREATE TABLE MovieGenre(mid INT NOT NULL, 
					    genre VARCHAR(20) NOT NULL,
					    UNIQUE(mid,genre),
					    FOREIGN KEY(mid) REFERENCES Movie(id),
					    CHECK(LENGTH(genre)>=1)) ENGINE = INNODB;
--We want MovieGenre entries to reference movies that are already in the Movie
--relation, so we use a foreign key to check that the mid field of a MovieGenre
--entry appears in the Movie relation.
--We disallow null values for both fields because foreign key constraints evaluate
--null as being an id in Movie. A tuple with either field null would be useless,
--so we choose to disallow it.
--Even if the genre field for an inserted tuple is non-null, it can still be empty
--so we want the length of genre to be at least 1 character
--A movie can be of multiple genres, but we do not want duplicates of the same
--movie-genre connection so we have a unique constraint on mid,genre

CREATE TABLE MovieDirector(mid INT NOT NULL, 
						   did INT NOT NULL, 
						   UNIQUE(mid,did),
						   FOREIGN KEY(mid) REFERENCES Movie(id), 
						   FOREIGN KEY(did) REFERENCES Director(id)) ENGINE = INNODB;
--We want MovieDirector entries to reference movies that are already in the Movie
--relation,so we use a foreign key to check that the mid field of a MovieDirector
--entry appears in the Movie relation.
--We want MovieDirector entries to also reference directors that are already in the
--Director relation, so we use a foreign key to check that the did field of an entry
--in MovieDirector also appears in the Director relation.
--We disallow null values for both fields because foreign key constraints evaluate null
--values to being in the relation(s) that gets referenced. A tuple with either field null
--would be useless, so we choose to disallow it.
--We have a unique constraint on mid,did because we do not want duplicates of the same
--tuple appearing in the relation.

CREATE TABLE MovieActor(mid INT NOT NULL, 
						aid INT NOT NULL, 
						role VARCHAR(50), 
						UNIQUE(mid,aid,role),
						FOREIGN KEY(mid) REFERENCES Movie(id), 
						FOREIGN KEY(aid) REFERENCES Actor(id)) ENGINE = INNODB;
--We want MovieActor entries to reference movies that are already in the Movie
--relation,so we use a foreign key to check that the mid field of a MovieActor
--entry appears in the Movie relation.
--We want MovieActor entries to also reference actors that are already in the
--Actor relation, so we use a foreign key to check that the aid field of an entry
--in MovieActor also appears in the Actor relation.
--The main purpose of MovieActor is to be able to know which actors appeared in
--which movies, so we make mid and aid not null. The role, while we would like to 
--know, is not absolutely mandatory.
--We have unique constraint on mid,aid,role since an actor may have more than
--one role in a movie, but we do not want duplicate entries of the same role

CREATE TABLE Review(name VARCHAR(20) NOT NULL, 
					time TIMESTAMP NOT NULL, 
					mid INT NOT NULL, 
					rating INT, 
					comment VARCHAR(500) NOT NULL,					
					FOREIGN KEY(mid) REFERENCES Movie(id),
					CHECK(LENGTH(name)>=1),
					CHECK(LENGTH(comment)>=1)) ENGINE = INNODB;
--We want to have at least the name, movie referenced, and review for each tuple
--in Review, so we disallow null values for those fields.
--We likely want to organize reviews for a movie by time, so we also disallow
--null values for the time attribute.
--The comment field may not be null but still be an empty string, so we put a check
--on the length of comment being at least 1


CREATE TABLE MaxPersonID(id INT NOT NULL) ENGINE = INNODB;
--We always want MaxPersonID to contain the id of the largest person id that has,
--been assigned, so we do not allow null values.

CREATE TABLE MaxMovieID(id INT NOT NULL) ENGINE = INNODB;
--We always want MaxMovieID to contain the id of the largest movie id that has 
--been assigned, so we do not allow null values.
