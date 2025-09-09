SET NAMES utf8mb4;
USE 202425_b2_jfrachisse;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS city;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS ad;
DROP TABLE IF EXISTS ad_category;
DROP TABLE IF EXISTS message;

SET FOREIGN_KEY_CHECKS = 1;

# Utilisateurs
CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    username VARCHAR(64) NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    global_name VARCHAR(64),
    biography TEXT,
    role ENUM("user", "admin") DEFAULT "user",
    created_at DATETIME DEFAULT NOW()
) ENGINE=InnoDB;


# Villes
CREATE TABLE city (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
) ENGINE=InnoDB;


# Catégories
CREATE TABLE category (
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
    created_at DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES city(id) ON DELETE CASCADE
) ENGINE=InnoDB;


# Catégories des annonces
CREATE TABLE ad_category (
    ad_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (ad_id) REFERENCES ad(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
) ENGINE=InnoDB;


# Messages
CREATE TABLE message (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES user(id) ON DELETE CASCADE
) ENGINE=InnoDB;


# Utilisateurs
INSERT INTO user VALUES
(1, "admin", SHA2("admin", 256), "Administrateur", NULL, "admin", NOW()),
(2, "user", SHA2("user", 256), "Utilisateur", "Juste un utilisateur lambda", "user", NOW()),
(3, "albert_einstein", SHA2("E=MC2", 256), "Albert Einstein", NULL, "user", NOW());


# Villes
INSERT INTO city VALUES
(1, "Paris"),
(2, "Marseille"),
(3, "Clermont-Ferrand");


# Catégories
INSERT INTO category VALUES
(1, "Autres"),
(2, "Immobilier"),
(3, "Véhicules"),
(4, "Emploi"),
(5, "Mode"),
(6, "Électronique");


# Annonces
INSERT INTO ad (id, author_id, title, description, price, city_id, created_at) VALUES
(1, 2, "Maison 6 Pièces 105m²", "À découvrir", 285000, 1, NOW()),
(2, 3, "Maison 5 pièces 110m²", "À seulement 4 km d'Uzès", 390000, 2, NOW()),
(3, 2, "AUDI A6", "TDI ultra 190 Business", 17490, 1, NOW()),
(4, 1, "Renault Twingo III", "SCE 65 EQUILIBRE", 12450, 3, NOW()),
(5, 2, "PC Portable Gamer", "i7, RTX 3060, 16Go RAM", 1200, 1, NOW()),
(6, 2, "Appartement 3 pièces", "Proche centre-ville", 185000, 2, NOW()),
(7, 1, "Veste en cuir", "Très bon état, taille M", 72.48, 3, NOW()),
(8, 2, "Console PS5", "Quasi neuve avec 2 manettes", 500, 1, NOW()),
(9, 3, "Stage développeur", "Cherche étudiant motivé", 0, 2, NOW()),
(10, 1, "Table basse en bois", "Style scandinave", 60, 3, NOW()),
(11, 3, "Maison de campagne", "4 pièces avec jardin", 95000, 3, NOW()),
(12, 2, "Tesla Model 3", "Long Range, 2022", 45000, 1, NOW()),
(13, 1, "MacBook Air M2", "Comme neuf, garanti", 1100, 2, NOW()),
(14, 2, "Pull en laine", "Taille L, très chaud", 35.5, 1, NOW()),
(15, 3, "Offre d'emploi : Cuisinier", "Restaurant cherche cuisinier", 0, 2, NOW()),
(16, 1, "Chaise gaming", "Confortable et réglable", 150, 3, NOW()),
(17, 2, "Moto Yamaha MT-07", "Parfait état", 6200, 2, NOW()),
(18, 1, "Robes vintage", "Lot de 3 robes", 99.99, 1, NOW()),
(19, 3, "Livre ancien", "Édition rare de 1890", 200, 3, NOW()),
(20, 2, "Smartphone + Accessoires", "Vendu avec coque et chargeur", 450, 1, NOW());


# Catégories des annonces
INSERT INTO ad_category VALUES
(1, 2),
(2, 2),
(3, 3),
(4, 3),
(5, 6),
(6, 2),
(7, 5),
(8, 6),
(9, 4),
(10, 1),
(11, 2),
(12, 3),
(13, 6),
(14, 5),
(15, 4),
(16, 6),
(17, 3),
(18, 5),
(19, 1),
(20, 5),
(20, 6);
