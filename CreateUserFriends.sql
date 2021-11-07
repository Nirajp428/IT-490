CREATE TABLE user_friends(
        id int auto_increment,
        user_id int,
        friend_id int,
	added boolean,
        primary key (`id`),
        foreign key (`user_id`) REFERENCES Users(`id`)
)

