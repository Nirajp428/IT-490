CREATE TABLE rating(
	id int auto_increment,
	user_id int,
	movie_id int,
	isLike boolean,
	primary key (`id`),
	foreign key (`user_id`) REFERENCES Users(`id`),
	foreign key (`movie_id`) REFERENCES Movies(`movieID`)
)
