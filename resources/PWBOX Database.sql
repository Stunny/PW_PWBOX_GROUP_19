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

