<?php

// Vérifie si l'utilisateur est admin
$isAdmin = false;

if (!empty($_SESSION['userID'])) {

    include_once 'private/src/model/DAO/UserDAO.php';

    $userDAO = new UserDAO();
    $currentUser = $userDAO->get($_SESSION['userID']);
    $isAdmin = $currentUser && $currentUser->getRole() === 'admin';

}

?>

<nav class="navbar navbar-expand-xl navbar-light bg-light fixed-top custom-navbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="index.php?page=annonces" style="color: #0D9B8A;">
            <img src="public/assets/logo.png" alt="Logo" style="height:32px; margin-right:8px;">
            Petites annonces
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <form class="d-flex position-relative" role="search">
                <input class="form-control me-2" type="search" placeholder="Rechercher une annonce..." aria-label="Search" id="searchInput" autocomplete="off">
                <button class="btn loupe" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <ul id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000;"></ul>
            </form>

            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link custom-nav-link" href="index.php?page=annonces">Annonces</a>
                </li>

                <?php if (!empty($_SESSION['userID'])): ?>
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link" href="index.php?page=profil">Profil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link custom-nav-link" href="index.php?page=inbox">Messagerie</a>
                    </li>

                    <?php if ($isAdmin): ?>
                        <li class="nav-item">
                            <a class="nav-link custom-nav-link" href="index.php?page=admin" style="color: #dc3545;">
                                <i class="fa-solid fa-shield-halved"></i> Admin
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="index.php?page=logout" title="Déconnexion">Déconnexion</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link custom-nav-link" href="index.php?page=auth">Compte</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="btn fw-bold" href="index.php?page=create-announce">
                        <i class="fa-solid fa-square-plus" style="margin-right: 5px;"></i> Déposer une annonce
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>