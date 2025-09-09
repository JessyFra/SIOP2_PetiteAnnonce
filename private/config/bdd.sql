SET NAMES utf8mb4;
USE 202425_b2_jfrachisse;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS cities;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS ads;
DROP TABLE IF EXISTS messages;

SET FOREIGN_KEY_CHECKS = 1;


# Utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    username VARCHAR(64) NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    global_name VARCHAR(64),
    biography TEXT,
    role ENUM("user", "admin") DEFAULT "user",
    created_at DATETIME NOT NULL
) ENGINE=InnoDB;


# Villes
CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
) ENGINE=InnoDB;


# Catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
) ENGINE=InnoDB;


# Annonces
CREATE TABLE ads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    title VARCHAR(64) NOT NULL,
    description TEXT,
    price DOUBLE DEFAULT 0,
    city_id INT NOT NULL,
    category_id INT DEFAULT 0,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;


# Messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


# Utilisateurs
INSERT INTO users VALUES
(1, "admin", SHA2("admin", 256), "Administrateur", NULL, true, NOW()),
(2, "user", SHA2("user", 256), "Utilisateur", "Juste un utilisateur lambda", false, NOW()),
(3, "albert_einstein", SHA2("E=MC2", 256), "Albert Einstein", NULL, false, NOW());


# Villes
INSERT INTO cities VALUES
(1, "Paris"),
(2, "Marseille"),
(3, "Clermont-Ferrand");


# Catégories
INSERT INTO categories VALUES
(0, "Autres"),
(1, "Immobilier"),
(2, "Véhicules"),
(3, "Emploi"),
(4, "Mode"),
(5, "Électronique");


# Annonces
INSERT INTO ads VALUES
(1, 2, "Maison 6 Pièces 105m²", "À découvrir", 285000, 1, 1, NOW()),
(2, 3, "Maison 5 pièces 110m²", "À seulement 4 km d'Uzès", 390000, 2, 1, NOW()),
(3, 2, "AUDI A6", "TDI ultra 190 Business", 17490, 1, 2, NOW()),
(4, 1, "Renault Twingo III", "SCE 65 EQUILIBRE", 12450, 3, 2, NOW()),
(5, 3, "PC Portable Gamer", "i7, RTX 3060, 16Go RAM", 1200, 1, 5, NOW()),
(6, 2, "Appartement 3 pièces", "Proche centre-ville", 185000, 2, 1, NOW()),
(7, 1, "Veste en cuir", "Très bon état, taille M", 72.48, 3, 4, NOW()),
(8, 2, "Console PS5", "Quasi neuve avec 2 manettes", 500, 1, 5, NOW()),
(9, 3, "Stage développeur", "Cherche étudiant motivé", 0, 2, 3, NOW()),
(10, 1, "Table basse en bois", "Style scandinave", 60, 3, 0, NOW()),
(11, 2, "Maison de campagne", "4 pièces avec jardin", 95000, 3, 1, NOW()),
(12, 3, "Tesla Model 3", "Long Range, 2022", 45000, 1, 2, NOW()),
(13, 1, "MacBook Air M2", "Comme neuf, garanti", 1100, 2, 5, NOW()),
(14, 2, "Pull en laine", "Taille L, très chaud", 35.5, 1, 4, NOW()),
(15, 3, "Offre d'emploi : Cuisinier", "Restaurant cherche cuisinier", 0, 2, 3, NOW()),
(16, 1, "Chaise gaming", "Confortable et réglable", 150, 3, 5, NOW()),
(17, 2, "Moto Yamaha MT-07", "Parfait état", 6200, 2, 2, NOW()),
(18, 3, "Robes vintage", "Lot de 3 robes", 99.99, 1, 4, NOW()),
(19, 1, "Livre ancien", "Édition rare de 1890", 200, 3, 0, NOW());
