CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `job_title_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
