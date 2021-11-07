CREATE TABLE Movies
(
	movieID	int NOT NULL AUTO_INCREMENT,
	title VARCHAR(60),
	year VARCHAR(10),
	rated VARCHAR(20),
	released VARCHAR(40),
	runtime VARCHAR(20),
	genre VARCHAR(60),
	director VARCHAR(60),
	actors VARCHAR(100),
	plot VARCHAR(500),
	poster VARCHAR(120),
	imdbRating VARCHAR(10),
	contentType VARCHAR(20),
	seasons VARCHAR(20),
	primary key (movieID)
)
