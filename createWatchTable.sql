CREATE TABLE 'watch_list'(
    user_id int,
    movie_id int,
	title VARCHAR(60),
    store int, /* do we need a value to identify if movie is added to the list or needs to be removed. */
    primary key(user_id)


)