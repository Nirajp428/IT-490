
CREATE TABLE Users
(
    id INT NOT NULL AUTO_INCREMENT,
     email VARCHAR(60) NOT NULL,
      password VARCHAR(60) NOT NULL,
       created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       username VARCHAR(60) default'', 
       PRIMARY KEY (id), UNIQUE(email));
