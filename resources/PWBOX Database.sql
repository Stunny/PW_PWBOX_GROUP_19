-- CREATE DATABASE PWBOX;
USE PWBOX;

DROP TABLE IF EXISTS `role` CASCADE;
DROP TABLE IF EXISTS `folder` CASCADE;
DROP TABLE IF EXISTS `user` CASCADE;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `password` char(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `profileImgPath` VARCHAR(255),
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `folder`(
	`id` INT NOT NULL AUTO_INCREMENT,
  `creador` int(11) unsigned NOT NULL,
  `nom` VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'new-folder',
  `path` VARCHAR(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`creador`) REFERENCES `user`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `role`(
	`usuari` INT unsigned NOT NULL,
  `folder` INT NOT NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuari`) REFERENCES `user`(`id`),
  FOREIGN KEY (`folder`) REFERENCES `folder`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
