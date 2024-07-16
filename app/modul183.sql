CREATE DATABASE IF NOT EXISTS `modul183`;

USE `modul183`;

CREATE TABLE IF NOT EXISTS `users` (
    id INT NOT NULL AUTO_INCREMENT,
    username NVARCHAR(25) NOT NULL,
    email NVARCHAR(50) NOT NULL,
    password VARBINARY(60) NOT NULL,
    `is_email_verified` BIT NOT NULL DEFAULT 0,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

INSERT INTO users (username, email, password)
VALUES (
    'johndoe420',
    'john_doe@example.com',
    '$2a$10$aAMhn9RzTJPkBotNsm6cFu96K1gnVigZhuxAUuhc/XXVN1TlQKxLq' -- hash = "password", for test purposes only!
);