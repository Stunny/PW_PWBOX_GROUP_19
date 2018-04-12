-- CREATE DATABASE PWBOX;
USE PWBOX;

DROP TABLE IF EXISTS Roles CASCADE;
DROP TABLE IF EXISTS Folder CASCADE;
DROP TABLE IF EXISTS Usuari CASCADE;

CREATE TABLE Usuari (
	id INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255),
    contrasenya VARCHAR(255),
    email VARCHAR(255),
    birthdate DATE,
    profile_image VARCHAR(255),
    verified BOOLEAN DEFAULT false,
    PRIMARY KEY (id)
);

CREATE TABLE Folder(
	id INT NOT NULL AUTO_INCREMENT,
    creador INT,
    nom VARCHAR(255),
    path VARCHAR(255),
    PRIMARY KEY (id),
    FOREIGN KEY (creador) REFERENCES Usuari(id)
);

CREATE TABLE Roles(
	usuari INT,
    folder INT,
    role VARCHAR(255),
    FOREIGN KEY (usuari) REFERENCES Usuari(id),
    FOREIGN KEY (folder) REFERENCES Folder(id)
);

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `password` char(32) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
