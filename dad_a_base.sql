DROP DATABASE IF EXISTS `rent_a_dad`;
CREATE DATABASE `rent_a_dad`;
USE `rent_a_dad`;

-- Create tables
CREATE TABLE `client`
(
    `id`        INT             NOT NULL    AUTO_INCREMENT,
    `username`  VARCHAR(255)    NOT NULL    UNIQUE,
    `password`  VARCHAR(255)    NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `dad`
(
    `id`            INT             NOT NULL    AUTO_INCREMENT,
    `name`          VARCHAR(255)    NOT NULL,
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
(1, 'TestUsername', 'TestPassword'),
(2, 'InvalidUser' , 'NotEncrypted');

INSERT INTO `dad` VALUES
(1, 'Michael', 52, 'Father of 3. Will treat your children like his own', 4.5),
(2, 'Sam'    , 56, 'Has 2 daughters. Tenderhearted and thoughtful'     , 3.0),
(3, 'Bennie' , 54, 'Dad of 3, knowledgeable and resourceful'           , 5.0),
(4, 'John'   , 57, 'Father of 1, quirky and kindhearted'               , 3.5),
(5, 'Dexter' , 63, 'Hilarious genius'                                  , 4.5);

INSERT INTO `skill` VALUES
(1, 'football', 'watch, coach, or play football'),
(2, 'woodwork', 'build something'),
(3, 'repair'  , 'fix somthing'),
(4, 'math'    , 'tutor math'),
(5, 'grilling', 'master of meats');

-- Join data
INSERT INTO `client_has_dad` VALUES
(1, 3, '2021-06-18 12:00:00', '2021-06-18 18:00:00'),
(2, 1, '2021-08-20 00:00:00', '2021-08-22 00:00:00');

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

GRANT SELECT, INSERT, UPDATE
ON `rent_a_dad`.*
TO `granddad`@`localhost`;

GRANT USAGE
ON *.*
TO `client`@`localhost`
IDENTIFIED BY 'likefatherlikeson';

GRANT SELECT
ON `rent_a_dad`.*
TO `client`@`localhost`;
