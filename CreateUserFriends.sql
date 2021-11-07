CREATE TABLE user_friends(
        id int auto_increment,
        user_id int,
        friend_id int,
        primary key (`id`),
        foreign key (`friend_id`) REFERENCES Users(`id`),
)

