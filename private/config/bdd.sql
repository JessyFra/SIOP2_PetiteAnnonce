SET NAMES utf8mb4;

DROP DATABASE IF EXISTS 202425_b2_jfrachisse;
CREATE DATABASE 202425_b2_jfrachisse;
USE 202425_b2_jfrachisse;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS city;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS announce;
DROP TABLE IF EXISTS announce_category;
DROP TABLE IF EXISTS message;

SET FOREIGN_KEY_CHECKS = 1;

# Utilisateurs
CREATE TABLE user
(
    id              INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name            VARCHAR(64)        NOT NULL,
    hashed_password VARCHAR(255)       NOT NULL,
    global_name     VARCHAR(64),
    biography       TEXT,
    role            ENUM ("user", "admin") DEFAULT "user",
    banned TINYINT(1) DEFAULT 0,
    created_at      DATETIME               DEFAULT NOW()
) ENGINE = InnoDB;


# Villes
CREATE TABLE city
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
) ENGINE = InnoDB;


# Catégories
CREATE TABLE category
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL
) ENGINE = InnoDB;


# Annonces
CREATE TABLE announce
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(64) NOT NULL,
    description TEXT,
    price       DOUBLE   DEFAULT 0,
    status     ENUM ("closed", "open") DEFAULT "open",
    type ENUM ("offer", "request") DEFAULT "offer",
    city_id     INT         NOT NULL,
    author_id   INT         NOT NULL,
    created_at  DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE,
    FOREIGN KEY (city_id) REFERENCES city (id) ON DELETE CASCADE
) ENGINE = InnoDB;


# Catégories des annonces
CREATE TABLE announce_category
(
    announce_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (announce_id) REFERENCES announce (id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
) ENGINE = InnoDB;


# Messages
CREATE TABLE message
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    content     TEXT NOT NULL,
    author_id   INT  NOT NULL,
    receiver_id INT  NOT NULL,
    created_at  DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES user (id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES user (id) ON DELETE CASCADE
) ENGINE = InnoDB;


# Utilisateurs
INSERT INTO user
VALUES (1, "admin", SHA2("admin", 256), "Administrateur", NULL, "admin", 0, NOW()),
       (2, "user", SHA2("user", 256), "Utilisateur", "Juste un utilisateur lambda", "user", 0, NOW()),
       (3, "albert_einstein", SHA2("E=MC2", 256), "Albert Einstein", NULL, "user", 0, NOW());


# Villes
INSERT INTO city
VALUES (1, "Paris"),
       (2, "Marseille"),
       (3, "Clermont-Ferrand");


# Catégories
INSERT INTO category
VALUES (1, "Autres"),
       (2, "Immobilier"),
       (3, "Véhicules"),
       (4, "Emploi"),
       (5, "Mode"),
       (6, "Électronique");


# Annonces
INSERT INTO announce (id, title, description, price, status, type, city_id, author_id, created_at)
VALUES (1, "Maison 6 Pièces 105m²", "À découvrir", 285000, "closed", "offer", 1, 2, NOW()),
       (2, "Maison 5 pièces 110m²", "À seulement 4 km d'Uzès", 390000, "open", "offer", 2, 3, NOW()),
       (3, "AUDI A6", "TDI ultra 190 Business", 17490, "open", "offer", 1, 2, NOW()),
       (4, "Renault Twingo III", "SCE 65 EQUILIBRE", 12450, "open", "offer", 3, 1, NOW()),
       (5, "PC Portable Gamer", "i7, RTX 3060, 16Go RAM", 1200, "open", "offer", 1, 2, NOW()),
       (6, "Appartement 3 pièces", "Proche centre-ville", 185000, "open", "offer", 2, 2, NOW()),
       (7, "Veste en cuir", "Très bon état, taille M", 72.48, "open", "offer", 3, 1, NOW()),
       (8, "Console PS5", "Quasi neuve avec 2 manettes", 500, "open", "offer", 1, 2, NOW()),
       (9, "Stage développeur", "Cherche étudiant motivé", 0, "open", "request", 2, 3, NOW()),
       (10, "Table basse en bois", "Style scandinave", 60, "open", "offer", 3, 1, NOW()),
       (11, "Maison de campagne", "4 pièces avec jardin", 95000, "open", "offer", 3, 3, NOW()),
       (12, "Tesla Model 3", "Long Range, 2022", 45000, "open", "offer", 1, 2, NOW()),
       (13, "MacBook Air M2", "Comme neuf, garanti", 1100, "open", "offer", 2, 1, NOW()),
       (14, "Pull en laine", "Taille L, très chaud", 35.5, "open", "offer", 1, 2, NOW()),
       (15, "Offre d'emploi : Cuisinier", "Restaurant cherche cuisinier", 0, "open", "request", 2, 3, NOW()),
       (16, "Chaise gaming", "Confortable et réglable", 150, "open", "offer", 3, 1, NOW()),
       (17, "Moto Yamaha MT-07", "Parfait état", 6200, "open", "offer", 2, 2, NOW()),
       (18, "Robes vintage", "Lot de 3 robes", 99.99, "open", "offer", 1, 1, NOW()),
       (19, "Livre ancien", "Édition rare de 1890", 200, "open", "offer", 3, 3, NOW()),
       (20, "Smartphone + Accessoires", "Vendu avec coque et chargeur", 450, "open", "offer", 1, 2, NOW());


# Catégories des annonces
INSERT INTO announce_category
VALUES (1, 2),
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
