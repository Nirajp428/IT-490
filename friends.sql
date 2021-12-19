CREATE TABLE `relation` (
  `user_id` int(20) NOT NULL,
  `friend_id` int(20) NOT NULL,
  `status` varchar(1) NOT NULL,
  `since` datetime NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (`user_id`,`friend_id`,`status`)
) 
