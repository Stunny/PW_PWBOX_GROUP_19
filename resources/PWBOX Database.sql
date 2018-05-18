drop database PWBOX;
CREATE DATABASE PWBOX;
USE PWBOX;

DROP TABLE IF EXISTS `role` CASCADE;
DROP TABLE IF EXISTS `file` CASCADE;
DROP TABLE IF EXISTS `folder` CASCADE;
DROP TABLE IF EXISTS `user` CASCADE;

CREATE TABLE `user` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `password` char(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `birthdate`date not null,
  `verified` bool not null default false,
  `verificationHash` varchar(32) not null,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`),
  unique key `unique_username`(`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `folder`(
  `id` INT unsigned NOT NULL AUTO_INCREMENT,
  `creador` int unsigned NOT NULL,
  `nom` VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'new-folder',
  `path` VARCHAR(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`creador`) REFERENCES `user`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `role`(
  `usuari` INT unsigned NOT NULL,
  `folder` INT unsigned NOT NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`usuari`) REFERENCES `user`(`id`),
  FOREIGN KEY (`folder`) REFERENCES `folder`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `file`(
  `id` INT unsigned NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT "new-file",
  `creator` INT unsigned NOT NULL,
  `folder` INT unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`creator`) REFERENCES `user`(`id`),
  FOREIGN KEY (`folder`) REFERENCES `folder`(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

