CREATE TABLE Movies
(
	movieID	int NOT NULL AUTO_INCREMENT,
	title VARCHAR(60),
	year VARCHAR(10),
	rated VARCHAR(60),
	released VARCHAR(60),
	runtime VARCHAR(60),
	genre VARCHAR(100),
	director VARCHAR(100),
	actors VARCHAR(200),
	plot VARCHAR(700),
	poster VARCHAR(300),
	imdbRating VARCHAR(10),
	contentType VARCHAR(60),
	seasons VARCHAR(60),
	primary key (movieID)
)
