<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Inclusion des balises meta -->
    <?php include_once 'private/src/view/component/head.php'; ?>

    <!-- Titre dynamique -->
    <title><?php echo $pageTitle; ?></title>
</head>

<body>

    <!-- Inclusion de la barre de navigation -->
    <?php include_once 'private/src/view/component/navbar.php'; ?>

    <!-- Contenu de la page -->
    <main class="container">
        <?php echo $pageContent; ?>
    </main>

    <!-- Liens vers les scripts JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="public/js/popup.js"></script>
    <script src="public/js/<?php echo $pageJS; ?>"></script>

</body>

</html>