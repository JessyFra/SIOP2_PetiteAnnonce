<?php

if (!empty($_GET["id"])) {
    $announceId = htmlspecialchars($_GET["id"], ENT_QUOTES);
} else {
    header("Location: index.php?page=annonces");
    exit;
}

$announce = AnnounceDAO::get($announceId);

if (!$announce) {
    header("Location: index.php?page=annonces");
    exit;
}

?>

<section class="announce-detail-container">
    <div class="announce-detail-row">
        <!-- Images -->
        <div class="announce-images">
            <?php
            $pathImage = "public/assets/img/" . $announce->getId() . ".png";
            if (!file_exists($pathImage)) {
                $pathImage = "public/assets/default.png";
            }
            ?>
            <img src="<?php echo $pathImage ?>" alt="<?php echo htmlspecialchars($announce->getTitle()) ?>" class="announce-main-image">

            <!-- Badges -->
            <div class="announce-badges">
                <span class="announce-type-badge <?= $announce->getType() == 'offer' ? 'badge-offer' : 'badge-request' ?>">
                    <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                </span>
                <?php if ($announce->getStatus() == 'closed'): ?>
                    <span class="announce-status-badge">
                        <i class="fa-solid fa-lock"></i> Clôturée
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Carte d'achat -->
        <div class="announce-purchase-card">
            <h2><?php echo htmlspecialchars($announce->getTitle()) ?></h2>

            <?php if ($announce->getType() == 'offer'): ?>
                <p class="announce-detail-price"><?php echo number_format($announce->getPrice(), 2, ',', ' ') ?> €</p>
            <?php else: ?>
                <p class="announce-detail-type">
                    <i class="fa-solid fa-search"></i> Recherche
                </p>
            <?php endif; ?>

            <div class="announce-actions">
                <?php if ($announce->getType() == 'offer' && $announce->getStatus() == 'open'): ?>
                    <button class="btn btn-success btn-block">
                        <i class="fa-solid fa-shopping-cart"></i> Acheter
                    </button>
                <?php endif; ?>

                <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] != $announce->getAuthorId()): ?>
                    <a href="index.php?page=inbox&id=<?php echo $announce->getAuthorId() ?>" class="btn btn-primary btn-block">
                        <i class="fa-solid fa-envelope"></i> Contacter
                    </a>
                <?php endif; ?>
            </div>

            <!-- Informations vendeur -->
            <div class="seller-info">
                <h3><i class="fa-solid fa-user-circle"></i> Vendeur</h3>
                <a href="index.php?page=user-profile&id=<?= $announce->getAuthorId() ?>" class="seller-link">
                    <div class="seller-avatar">
                        <?= strtoupper(substr($announce->getAuthorName(), 0, 1)) ?>
                    </div>
                    <span><?php echo htmlspecialchars($announce->getAuthorName()) ?></span>
                </a>
            </div>
        </div>
    </div>

    <!-- Description et détails -->
    <div class="announce-details-section">
        <div class="detail-card">
            <h3><i class="fa-solid fa-align-left"></i> Description</h3>
            <p class="announce-description"><?php echo nl2br(htmlspecialchars($announce->getDescription())) ?></p>
        </div>

        <div class="detail-card">
            <h3><i class="fa-solid fa-info-circle"></i> Informations</h3>
            <div class="info-grid">
                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <strong>Localisation</strong>
                        <p><?php echo htmlspecialchars($announce->getCityName()) ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-calendar"></i>
                    <div>
                        <strong>Date de publication</strong>
                        <p><?php echo date('d/m/Y à H:i', strtotime($announce->getCreatedAt())) ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-tag"></i>
                    <div>
                        <strong>Type</strong>
                        <p><?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?></p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>Statut</strong>
                        <p><?= $announce->getStatus() == 'open' ? 'Ouverte' : 'Clôturée' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>