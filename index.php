<?php
// Routeur pour 3 pages

$page = $_GET['page'] ?? 'annonces';

/*
- Vérifie les paramètres après ? dans l'URL, si vide redirection vers la liste des annonces
*/
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: index.php?page=annonces");
    exit;
}

// Démarrage du buffer pour capturer le contenu de la page
ob_start();

// Route selon la valeur de "page"
switch ($page) {
    case 'annonces':
        include_once 'private/src/control/annonceControl.php';
        $controller = new annonceControl();
        $controller->liste(); // Affiche la liste des annonces
        $pageTitle = "Petites annonces"; // Titre de la page
        break;

    case 'connexion':
        include_once 'private/src/control/userControl.php';
        $controller = new userControl();
        $controller->connexion(); // Affiche le formulaire de connexion
        $pageTitle = "Connexion - Petites annonces"; // Titre de la page
        break;

    case 'inscription':
        include_once 'private/src/control/userControl.php';
        $controller = new userControl();
        $controller->inscription(); // Affiche le formulaire d'inscription
        $pageTitle = "Inscription - Petites annonces"; // Titre de la page
        break;

    default:
        // Page par défaut : liste des annonces
        include_once 'private/src/control/annonceControl.php';
        $controller = new annonceControl();
        $controller->liste();
        $pageTitle = "Petites annonces"; // Titre de la page
        break;
}

// Récupère le contenu généré par le contrôleur
$pageContent = ob_get_clean();

include_once 'private/src/view/component/structure.php';
