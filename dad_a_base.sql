DROP DATABASE IF EXISTS `rent_a_dad`;
CREATE DATABASE `rent_a_dad`;
USE `rent_a_dad`;

-- Create tables
CREATE TABLE `client`
(
    `id`            INT             NOT NULL    AUTO_INCREMENT,
    `username`      VARCHAR(255)    NOT NULL    UNIQUE,
    `password`      VARCHAR(255)    NOT NULL,
    `first_name`    VARCHAR(255)    NOT NULL,
    `last_name`     VARCHAR(255)    NOT NULL,
    `email`         VARCHAR(255)    NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `dad`
(
    `id`            INT             NOT NULL    AUTO_INCREMENT,
    `first_name`    VARCHAR(255)    NOT NULL,
    `last_name`     VARCHAR(255)    NOT NULL,
    `age`           INT             NOT NULL,
    `biography`     VARCHAR(255)    NOT NULL,
    `rate`          DECIMAL(4,2)    NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `client_has_dad`
(
    `client_id`     INT         NOT NULL,
    `dad_id`        INT         NOT NULL,
    `start_time`    DATETIME    NOT NULL,
    `end_time`      DATETIME    NOT NULL,

    CONSTRAINT `client_has_dad_FK_client`
        FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
    CONSTRAINT `client_has_dad_FK_dad`
        FOREIGN KEY (`dad_id`) REFERENCES `dad` (`id`)
);

CREATE TABLE `skill`
(
    `id`            INT             NOT NULL    AUTO_INCREMENT,
    `name`          VARCHAR(255)    NOT NULL,
    `description`   VARCHAR(255)    NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `dad_has_skill`
(
    `dad_id`        INT     NOT NULL,
    `skill_id`      INT     NOT NULL,
    `proficiency`   INT     NOT NULL,

    CONSTRAINT `dad_has_skill_FK_dad`
        FOREIGN KEY (`dad_id`) REFERENCES `dad` (`id`),
    CONSTRAINT `dad_has_skill_FK_skill`
        FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`)
);

-- Insert data
INSERT INTO `client` VALUES
(1, 'wolfie', '$2y$10$PiKNpHThbiRwSKL16gjtYO7XtvhaP7qjlIE.kz8gqLS44RPw4Je86', 'UWG', 'Wolfie', 'test@westga.edu'),
(2, 'sd00070', '$2y$10$xkw45v0Q0mtjDgDR7HDJhOVPdrXQRiyU48bWjO1Rvz.h0z7wHYMny', 'Spencer', 'Dent', 'sd00070@my.westga.edu');

INSERT INTO `dad` VALUES
(1, 'Michael', 'McMichaels', 52, 'Father of 3. Will treat your children like his own', 4.5),
(2, 'Sam'    , 'Sampson'   , 56, 'Has 2 daughters. Tenderhearted and thoughtful'     , 3.0),
(3, 'Bennie' , 'Brown'     , 54, 'Dad of 3, knowledgeable and resourceful'           , 5.0),
(4, 'John'   , 'Johnson'   , 57, 'Father of 1, quirky and kindhearted'               , 3.5),
(5, 'Dexter' , 'Lab'       , 63, 'Hilarious genius'                                  , 4.5);

INSERT INTO `skill` VALUES
(1, 'football', 'watch, coach, or play football'),
(2, 'woodwork', 'build something'),
(3, 'repair'  , 'fix something'),
(4, 'math'    , 'tutor math'),
(5, 'grilling', 'master of meats');

-- Join data
INSERT INTO `client_has_dad` VALUES
(2, 1, '2021-08-20 00:00:00', '2021-08-22 00:00:00'),
(2, 3, '2021-06-18 12:00:00', '2021-06-18 18:00:00');

INSERT INTO `dad_has_skill` VALUES
(1, 1, 4),
(1, 2, 3),
(1, 3, 3),
(1, 4, 3),
(1, 5, 2),
(2, 3, 4),
(2, 5, 3),
(3, 2, 4),
(3, 3, 3),
(3, 4, 5),
(4, 2, 4),
(4, 5, 3),
(5, 3, 5),
(5, 4, 4);

-- Grant permissions
GRANT USAGE
ON *.*
TO `granddad`@`localhost`
IDENTIFIED BY 'gri11m4st3r';

GRANT SELECT, INSERT, UPDATE, DELETE
ON `rent_a_dad`.*
TO `granddad`@`localhost`;
