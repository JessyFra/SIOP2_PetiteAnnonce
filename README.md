# SIOP2_PetiteAnnonce

## Présentation

Ce projet consiste à créer un site web de petites annonces entre particuliers. 
Il permet à des utilisateurs de publier, rechercher et gérer des annonces (offres ou demandes) dans différentes catégories et villes.

---

## Fonctionnalités principales

-   **Recherche d’annonces** : Par catégorie et ville, accessible à tous.
-   **Création de compte** : Inscription et authentification des utilisateurs.
-   **Gestion des annonces** :
    -   Déposer une annonce (offre ou demande) avec photos, titre, description, prix (pour les offres), date et heure de parution.
    -   Modifier, clôturer ou supprimer ses propres annonces.
-   **Messagerie interne** : Système de messages non instantanés entre utilisateurs authentifiés.
-   **Administration** :
    -   Connexion en tant qu’administrateur.
    -   Gestion de toutes les annonces (modifier, clôturer, supprimer).
    -   Bannir/débannir des comptes utilisateurs (hors admin).
-   **Responsive design** : Adaptation à tous les écrans grâce à Bootstrap.

---

## Pages principales du site

-   Accueil
-   Liste des annonces (avec filtres)
-   Détail d’une annonce
-   Inscription
-   Connexion
-   Dépôt/modification d’annonce
-   Messagerie (boîte de réception, envoi de message)
-   Profil utilisateur
-   Espace administrateur (gestion des annonces et des utilisateurs)
-   Documentation utilisateur (PDF dans le dossier `documentation`)

---

## Structure de la base de données

Un script SQL de création et de remplissage de la base sera disponible dans le fichier `bdd.sql`.

---

## Arborescence des dossiers/fichiers

---

## Technologies utilisées

-   **Frontend** : HTML, CSS (Bootstrap), JavaScript
-   **Backend** : PHP
-   **Base de données** : MySQL
-   **Serveur** : Apache

---

## Installation et configuration

---

## Auteurs

-   **Nolan Arsac**
-   **Jessy Frachisse**

---

## TODO

### 1. Authentification & Comptes Utilisateurs
- [ ] Créer la page d'inscription
- [ ] Créer la page de connexion
- [ ] Implémenter la gestion des sessions utilisateurs
- [ ] Ajouter la gestion du profil utilisateur (modification des infos, suppression du compte)

### 2. Gestion des annonces
- [ ] Créer le formulaire de dépôt d'annonce (offre/demande)
- [ ] Gérer l'upload et l'affichage des photos
- [ ] Afficher la liste des annonces avec filtres (catégorie, ville, type)
- [ ] Créer la page de détail d'une annonce
- [ ] Permettre la modification/suppression/clôture d'une annonce par son propriétaire

### 3. Recherche & Navigation
- [ ] Mettre en place la recherche par catégorie et ville
- [ ] Ajouter la pagination sur la liste des annonces

### 4. Messagerie interne
- [ ] Créer la boîte de réception et d'envoi de messages
- [ ] Permettre l'envoi de messages entre utilisateurs authentifiés
- [ ] Afficher l'historique des messages

### 5. Administration
- [ ] Créer l'espace administrateur
- [ ] Permettre la gestion de toutes les annonces (modification, clôture, suppression)
- [ ] Permettre le bannissement/dé-bannissement des utilisateurs (hors admin)

### 6. Base de données & Backend
- [ ] Rédiger le script SQL de création et de remplissage de la base (`bdd.sql`)
- [ ] Implémenter les DAO/DTO pour chaque entité

### 7. Frontend & Responsive
- [ ] Intégrer Bootstrap pour le responsive design
- [ ] Améliorer l’ergonomie et le graphisme des pages

### 8. Documentation
- [ ] Rédiger la documentation utilisateur avec captures d’écran
- [ ] Ajouter le PDF dans le dossier `documentation`

### 9. Déploiement & Git
- [ ] Préparer le projet pour l’hébergement web
- [ ] Mettre à jour régulièrement le dépôt GitHub avec des commits

---

## Notes

-   Ce projet est un travail d'étudiant et n'est pas destiné à un usage commercial.
-   Les données utilisateurs sont fictives et destinées uniquement à des fins de test.
-   La structure de la base de données et les fonctionnalités peuvent évoluer en fonction des besoins du projet.

---
