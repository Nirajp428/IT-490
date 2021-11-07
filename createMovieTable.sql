CREATE TABLE Movies
(
	movieID	int NOT NULL AUTO_INCREMENT,
	title VARCHAR(40),
	year VARCHAR(10),
	rated VARCHAR(10),
	released VARCHAR(30),
	runtime VARCHAR(15),
	genre VARCHAR(60),
	director VARCHAR(40),
	actors VARCHAR(100),
	plot VARCHAR(500),
	poster VARCHAR(120),
	imdbRating VARCHAR(10),
	contentType VARCHAR(15),
	seasons VARCHAR(5),
	primary key (movieID)
)
