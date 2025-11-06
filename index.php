<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


session_start();

// Routeur principal
// Récupère la page demandée via le paramètre 'page' dans l'URL, par défaut 'annonces'
$page = $_GET['page'] ?? 'annonces';

// Liste des pages autorisées et leur titre
$pages = [
    'annonces' => ['class' => 'AnnounceControl', 'method' => 'announces', 'title' => 'Petites annonces', 'css' => 'announcesStyle.css', 'js' => 'announcesScript.js'],

    'auth' => ['class' => 'UserControl', 'method' => 'auth', 'title' => 'Compte - Petites annonces', 'css' => 'authStyle.css'],

    'profil' => ['class' => 'UserControl', 'method' => 'profil', 'title' => 'Profil - Petites annonces', 'css' => 'profilStyle.css', 'js' => 'profil.js'],

    'user-profile' => ['class' => 'UserControl', 'method' => 'viewProfile', 'title' => 'Profil utilisateur', 'css' => 'publicProfileStyle.css'],

    'logout' => ['class' => 'UserControl', 'method' => 'logout', 'title' => 'Déconnexion'],

    'annonce' => ['class' => 'AnnounceControl', 'method' => 'announce', 'title' => 'Petites annonces', 'css' => 'announceStyle.css'],

    'inbox' => ['class' => 'InboxControl', 'method' => 'inbox', 'title' => 'Messagerie', 'css' => 'inboxStyle.css', 'js' => 'inboxScript.js'],

    'sendMessageAjax' => ['class' => 'InboxControl', 'method' => 'sendMessageAjax'],

    'countReceiverAjax' => ['class' => 'InboxControl', 'method' => 'countReceiverAjax'],

    // Routes admin
    'admin' => ['class' => 'AdminControl', 'method' => 'dashboard', 'title' => 'Administration - Tableau de bord', 'css' => 'adminStyle.css'],

    'admin-users' => ['class' => 'AdminControl', 'method' => 'manageUsers', 'title' => 'Administration - Utilisateurs', 'css' => 'adminStyle.css'],

    'admin-announces' => ['class' => 'AdminControl', 'method' => 'manageAnnounces', 'title' => 'Administration - Annonces', 'css' => 'adminStyle.css'],
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
