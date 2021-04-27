DROP DATABASE IF EXISTS `rent_a_dad`;
CREATE DATABASE IF NOT EXISTS `rent_a_dad`;
USE `rent_a_dad`;

DROP TABLE IF EXISTS `client_has_dad`;
DROP TABLE IF EXISTS `client`;
DROP TABLE IF EXISTS `dad_has_skill`;
DROP TABLE IF EXISTS `dad`;
DROP TABLE IF EXISTS `skill`;

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
    `picture`       VARCHAR(255)    NOT NULL,
    `biography`     VARCHAR(255)    NOT NULL,

    PRIMARY KEY (`id`)
);

CREATE TABLE `client_has_dad`
(
    `client_id`     INT     NOT NULL,
    `dad_id`        INT     NOT NULL,

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

GRANT SELECT, INSERT, UPDATE
ON `rent_a_dad`.*
TO `granddad`
IDENTIFIED BY 'gri11m4st3r';

GRANT SELECT
ON `rent_a_dad`.*
TO `client`
IDENTIFIED BY 'likefatherlikeson';
