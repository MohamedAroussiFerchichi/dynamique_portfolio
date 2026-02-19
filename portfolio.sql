-- =============================================
-- Portfolio Dynamique – Base de données
-- =============================================

CREATE DATABASE IF NOT EXISTS portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio;

-- Table infos_personnelles
CREATE TABLE IF NOT EXISTS infos_personnelles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    description TEXT,
    photo VARCHAR(255),
    cv VARCHAR(255),
    linkedin VARCHAR(255),
    github VARCHAR(255),
    site_perso VARCHAR(255)
);

-- Table projets
CREATE TABLE IF NOT EXISTS projets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    technologie VARCHAR(255),
    date_projet DATE
);

-- Table certificats
CREATE TABLE IF NOT EXISTS certificats (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    fichier VARCHAR(255),
    date_obtention DATE
);

-- Table contact
CREATE TABLE IF NOT EXISTS contact (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table admin
CREATE TABLE IF NOT EXISTS admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL
);

-- Données de démo pour infos_personnelles
INSERT INTO infos_personnelles (nom, prenom, description, linkedin, github, site_perso) VALUES (
    'Doe',
    'John',
    'Développeur Full Stack passionné par les nouvelles technologies, avec une expertise en PHP, JavaScript, et les bases de données relationnelles.',
    'https://linkedin.com',
    'https://github.com',
    'https://monsite.com'
);
