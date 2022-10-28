CREATE TABLE `assignment`.`user` (
    `user_id` INT UNSIGNED NULL AUTO_INCREMENT,
    `name`    VARCHAR(100) NOT NULL,
    PRIMARY KEY (`user_id`),
    UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC) VISIBLE
);

INSERT INTO `assignment`.`user` (NAME)
VALUES ('Matt Damon'),
       ('Johny Bravo'),
       ('Rafael Nadal')
;
