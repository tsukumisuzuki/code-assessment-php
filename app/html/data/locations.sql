CREATE TABLE IF NOT EXISTS `locations` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`location_name` VARCHAR(255) NOT NULL,
	`address` VARCHAR(255) NOT NULL,
	`city` VARCHAR(255) NOT NULL,
	`state` VARCHAR(255) NOT NULL,
	`latitude` FLOAT NOT NULL,
	`longitude` FLOAT NOT NULL,
	`phone` VARCHAR(100) NOT NULL,
	`country` VARCHAR(255) NOT NULL,
	`postal_code` INT(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;
