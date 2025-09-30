<?php

session_start();

// Routeur principal
// Récupère la page demandée via le paramètre 'page' dans l'URL, par défaut 'annonces'
$page = $_GET['page'] ?? 'annonces';

// Liste des pages autorisées et leur titre
$pages = [
    'annonces' => ['class' => 'AnnounceControl', 'method' => 'announce', 'title' => 'Petites annonces', 'css' => 'announceStyle.css'],

    'auth' => ['class' => 'UserControl', 'method' => 'auth', 'title' => 'Compte - Petites annonces', 'css' => 'authStyle.css'],

    'profil' => ['class' => 'UserControl', 'method' => 'profil', 'title' => 'Profil - Petites annonces', 'css' => 'profilStyle.css', 'js' => 'profil.js'],
    
    'logout' => ['class' => 'UserControl', 'method' => 'logout', 'title' => 'Déconnexion']
    // Plus de pages peuvent être ajoutées ici
];

// Vérifie les paramètres après ? dans l'URL, si vide redirection vers la liste des annonces
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: index.php?page=annonces");
    exit;
}

// Démarrage du buffer pour capturer le contenu de la page
ob_start();

include_once 'private/src/control/' . $pages[$page]['class'] . '.php'; 
$controllerClass = $pages[$page]['class'];
$controller = new $controllerClass();
$controller->{$pages[$page]['method']}();
$pageTitle = $pages[$page]['title'];
$pageCSS = $pages[$page]['css'] ?? null;
$pageJS = $pages[$page]['js'] ?? null;


// Récupère le contenu généré par le contrôleur
$pageContent = ob_get_clean();

include_once 'private/src/view/component/structure.php';
