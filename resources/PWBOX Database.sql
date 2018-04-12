-- CREATE DATABASE PWBOX;
USE PWBOX;

DROP TABLE IF EXISTS Roles CASCADE;
DROP TABLE IF EXISTS Folder CASCADE;
DROP TABLE IF EXISTS Usuari CASCADE;

CREATE TABLE Usuari (
	`id` INT NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(255) NOT NULL,
    `contrasenya` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `profile_image` VARCHAR(255),
    `verified` BOOLEAN DEFAULT false,
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Folder(
	`id` INT NOT NULL AUTO_INCREMENT,
    `creador` INT NOT NULL,
    `nom` VARCHAR(255) NOT NULL,
    `path` VARCHAR(255) NOT NULL,
	`created_at` DATETIME NOT NULL,
	`updated_at` DATETIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (creador) REFERENCES Usuari(id)
);

CREATE TABLE Roles(
	`usuari` INT NOT NULL,
    `folder` INT NOT NULL,
    `role` VARCHAR(255) NOT NULL,
    FOREIGN KEY (usuari) REFERENCES Usuari(id),
    FOREIGN KEY (folder) REFERENCES Folder(id)
);
