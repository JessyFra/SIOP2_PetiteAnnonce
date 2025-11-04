<?php

include_once 'private/src/helper/DateHelper.php';

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
            require_once 'private/src/model/DAO/AnnounceImageDAO.php';
            $images = AnnounceImageDAO::getByAnnounce($announce->getId());

            // Afficher l'image principale ou la première, ou l'image par défaut
            $mainImage = null;
            if (!empty($images)) {
                foreach ($images as $img) {
                    if ($img->isMain()) {
                        $mainImage = $img;
                        break;
                    }
                }
                if (!$mainImage) {
                    $mainImage = $images[0];
                }
                $pathImage = $mainImage->getFullPath();
            } else {
                $pathImage = "public/assets/default.png";
            }
            ?>
            <img src="<?php echo $pathImage ?>" alt="<?php echo htmlspecialchars($announce->getTitle()) ?>" class="announce-main-image" id="mainImage">

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

            <!-- Miniatures -->
            <?php if (count($images) > 1): ?>
                <div class="image-thumbnails">
                    <?php foreach ($images as $index => $image): ?>
                        <img src="<?= $image->getFullPath() ?>"
                            alt="Image <?= $index + 1 ?>"
                            class="thumbnail <?= $image->isMain() ? 'active' : '' ?>"
                            onclick="changeMainImage('<?= $image->getFullPath() ?>', this)">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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

                <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] != $announce->getAuthorId()): ?>
                    <a href="index.php?page=inbox&id=<?php echo $announce->getAuthorId() ?>" class="btn btn-block contactBtn">
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
                        <p><span><?= DateHelper::getRelativeTime($announce->getCreatedAt()) ?></span></p>
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

<script>
    function changeMainImage(imagePath, thumbnail) {
        document.getElementById('mainImage').src = imagePath;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }
</script>