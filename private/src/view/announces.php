<div class="filters-section">
    <h1>Liste des Annonces</h1>
    <p>Retrouvez ici la liste des annonces que vous pourriez vouloir</p>

    <!-- Formulaire de filtres -->
    <form method="GET" action="index.php" class="filters-form">
        <input type="hidden" name="page" value="annonces">

        <div class="row g-3 align-items-end">
            <!-- Recherche par mots-clés -->
            <div class="col-md-3">
                <label for="search" class="form-label">Rechercher</label>
                <input
                    type="text"
                    class="form-control"
                    id="search"
                    name="search"
                    placeholder="Mots-clés..."
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>

            <!-- Filtre par catégorie -->
            <div class="col-md-2">
                <label for="category" class="form-label">Catégorie</label>
                <select class="form-select" id="category" name="category">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->getId() ?>"
                            <?= (isset($_GET['category']) && $_GET['category'] == $category->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre par ville -->
            <div class="col-md-2">
                <label for="city" class="form-label">Ville</label>
                <select class="form-select" id="city" name="city">
                    <option value="">Toutes</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city->getId() ?>"
                            <?= (isset($_GET['city']) && $_GET['city'] == $city->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($city->getName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtre par type -->
            <div class="col-md-2">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Tous</option>
                    <option value="offer" <?= (isset($_GET['type']) && $_GET['type'] == 'offer') ? 'selected' : '' ?>>
                        Offres
                    </option>
                    <option value="request" <?= (isset($_GET['type']) && $_GET['type'] == 'request') ? 'selected' : '' ?>>
                        Demandes
                    </option>
                </select>
            </div>

            <!-- Boutons -->
            <div class="col-md-3">
                <button type="submit" class="btn filterSearchBtn">
                    <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                </button>
                <a href="index.php?page=annonces" class="btn btn-secondary">
                    <i class="fa-solid fa-rotate-right"></i> Réinitialiser
                </a>
            </div>
        </div>
    </form>

    <!-- Résultats de recherche -->
    <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['city']) || !empty($_GET['type'])): ?>
        <div class="search-info">
            <p class="text-muted">
                <i class="fa-solid fa-info-circle"></i>
                <?= count($announces) ?> résultat(s) trouvé(s)
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Affichage des annonces -->
<?php
// Déterminer si on affiche par catégorie ou en liste filtrée
$hasFilters = !empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['city']) || !empty($_GET['type']);
?>

<?php if ($hasFilters): ?>
    <!-- AFFICHAGE FILTRÉ (liste simple) -->
    <?php if (!empty($announces)): ?>
        <section class="announces-list">
            <div class="announces-grid">
                <?php foreach ($announces as $announce): ?>
                    <?php
                    // Récupération de l'image principale
                    require_once 'private/src/model/DAO/AnnounceImageDAO.php';
                    $mainImage = AnnounceImageDAO::getMainImage($announce->getId());

                    if ($mainImage) {
                        $pathImage = $mainImage->getFullPath();
                    } else {
                        // Fallback : chercher n'importe quelle image de l'annonce
                        $images = AnnounceImageDAO::getByAnnounce($announce->getId());
                        if (!empty($images)) {
                            $pathImage = $images[0]->getFullPath();
                        } else {
                            $pathImage = "public/assets/default.png";
                        }
                    }
                    ?>
                    <article class="announce-card" onclick="window.location.href='index.php?page=annonce&id=<?= $announce->getId() ?>'">
                        <div class="announce-card-image">
                            <img src="<?= $pathImage ?>" alt="<?= htmlspecialchars($announce->getTitle()) ?>">

                            <!-- Badge type -->
                            <span class="announce-type-badge <?= $announce->getType() == 'offer' ? 'badge-offer' : 'badge-request' ?>">
                                <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                            </span>

                            <?php if ($announce->getStatus() == 'closed'): ?>
                                <span class="announce-status-badge">
                                    <i class="fa-solid fa-lock"></i> Clôturée
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="announce-card-content">
                            <h3><?= htmlspecialchars($announce->getTitle()) ?></h3>
                            <p class="announce-description"><?= htmlspecialchars(substr($announce->getDescription(), 0, 100)) ?>...</p>

                            <?php if ($announce->getType() == 'offer'): ?>
                                <p class="announce-price">
                                    <strong><?= number_format($announce->getPrice(), 2, ',', ' ') ?> €</strong>
                                </p>
                            <?php endif; ?>

                            <div class="announce-footer">
                                <span class="announce-location">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <?= htmlspecialchars($announce->getCityName()) ?>
                                </span>
                                <span class="announce-author" onclick="event.stopPropagation(); window.location.href='index.php?page=user-profile&id=<?= $announce->getAuthorId() ?>'">
                                    <i class="fa-solid fa-user"></i>
                                    <?= htmlspecialchars($announce->getAuthorName()) ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php else: ?>
        <div class="no-results">
            <i class="fa-solid fa-search" style="font-size: 48px; color: #ccc;"></i>
            <p>Aucune annonce trouvée avec ces critères.</p>
            <a href="index.php?page=annonces" class="btn btn-primary">Voir toutes les annonces</a>
        </div>
    <?php endif; ?>

<?php else: ?>
    <!-- AFFICHAGE PAR CATÉGORIE (style LeBonCoin) -->
    <?php foreach ($categories as $category): ?>
        <?php $categoryAnnounces = $category->getAnnounces(); ?>

        <?php if (!empty($categoryAnnounces)): ?>
            <section class="category-section">
                <div class="category-header">
                    <h2 class="category-title">
                        <i class="fa-solid fa-tag"></i>
                        <?= htmlspecialchars($category->getName()) ?>
                    </h2>
                    <span class="category-count"><?= count($categoryAnnounces) ?> annonce(s)</span>
                </div>

                <div class="category-announces-scroll">
                    <?php foreach ($categoryAnnounces as $announce): ?>
                        <?php
                        // Récupération de l'image principale
                        require_once 'private/src/model/DAO/AnnounceImageDAO.php';
                        $mainImage = AnnounceImageDAO::getMainImage($announce->getId());

                        if ($mainImage) {
                            $pathImage = $mainImage->getFullPath();
                        } else {
                            // Fallback : chercher n'importe quelle image de l'annonce
                            $images = AnnounceImageDAO::getByAnnounce($announce->getId());
                            if (!empty($images)) {
                                $pathImage = $images[0]->getFullPath();
                            } else {
                                $pathImage = "public/assets/default.png";
                            }
                        }
                        ?>
                        <article class="announce-card-horizontal" onclick="window.location.href='index.php?page=annonce&id=<?= $announce->getId() ?>'">
                            <div class="announce-image-horizontal">
                                <img src="<?= $pathImage ?>" alt="<?= htmlspecialchars($announce->getTitle()) ?>">

                                <!-- Badge type -->
                                <span class="announce-type-badge <?= $announce->getType() == 'offer' ? 'badge-offer' : 'badge-request' ?>">
                                    <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                                </span>

                                <?php if ($announce->getStatus() == 'closed'): ?>
                                    <span class="announce-status-badge">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="announce-content-horizontal">
                                <h3><?= htmlspecialchars($announce->getTitle()) ?></h3>
                                <p class="announce-desc"><?= htmlspecialchars(substr($announce->getDescription(), 0, 80)) ?>...</p>

                                <?php if ($announce->getType() == 'offer'): ?>
                                    <p class="announce-price-horizontal">
                                        <?= number_format($announce->getPrice(), 2, ',', ' ') ?> €
                                    </p>
                                <?php endif; ?>

                                <div class="announce-meta-horizontal">
                                    <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($announce->getCityName()) ?></span>
                                    <span onclick="event.stopPropagation(); window.location.href='index.php?page=user-profile&id=<?= $announce->getAuthorId() ?>'">
                                        <i class="fa-solid fa-user"></i> <?= htmlspecialchars($announce->getAuthorName()) ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>