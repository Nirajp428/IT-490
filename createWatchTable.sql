CREATE TABLE 'watch_list'(
    user_id int,
    movie_id int,
    foreign key (`user_id`) REFERENCES Users(`id`),
	foreign key (`movie_id`) REFERENCES Movies(`movieID`)


)