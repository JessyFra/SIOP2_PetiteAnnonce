<div class="public-profile-container">
    <!-- En-tête du profil -->
    <div class="profile-header">
        <div class="profile-avatar-large">
            <?= strtoupper(substr($user->getName(), 0, 1)) ?>
        </div>
        <div class="profile-info">
            <h1 class="profile-username"><?= htmlspecialchars($user->getGlobalName() ?? $user->getName()) ?></h1>
            <p class="profile-handle">@<?= htmlspecialchars($user->getName()) ?></p>

            <?php if ($user->isBanned()): ?>
                <span class="badge badge-danger">
                    <i class="fa-solid fa-ban"></i> Compte banni
                </span>
            <?php endif; ?>

            <div class="profile-stats">
                <div class="stat-item">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span><?= count($announces) ?> annonce(s)</span>
                </div>
                <div class="stat-item">
                    <i class="fa-solid fa-calendar"></i>
                    <span>Membre depuis <?= date('M Y', strtotime($user->getCreatedAt())) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Biographie -->
    <?php if ($user->getBiography()): ?>
        <div class="profile-section">
            <h2><i class="fa-solid fa-quote-left"></i> Biographie</h2>
            <p class="profile-bio"><?= nl2br(htmlspecialchars($user->getBiography())) ?></p>
        </div>
    <?php endif; ?>

    <!-- Bouton de contact -->
    <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] != $user->getId()): ?>
        <div class="profile-actions">
            <a href="index.php?page=inbox&id=<?= $user->getId() ?>" class="btn btn-primary">
                <i class="fa-solid fa-envelope"></i> Contacter
            </a>
        </div>
    <?php endif; ?>

    <!-- Annonces de l'utilisateur -->
    <div class="profile-section">
        <h2><i class="fa-solid fa-list"></i> Annonces de <?= htmlspecialchars($user->getGlobalName() ?? $user->getName()) ?></h2>

        <?php if (!empty($announces)): ?>
            <div class="profile-announces-grid">
                <?php foreach ($announces as $announce): ?>
                    <?php
                    $pathImage = "public/assets/img/" . $announce->getId() . ".png";
                    if (!file_exists($pathImage)) {
                        $pathImage = "public/assets/default.png";
                    }
                    ?>
                    <article class="profile-announce-card" onclick="window.location.href='index.php?page=annonce&id=<?= $announce->getId() ?>'">
                        <div class="announce-image-container">
                            <img src="<?= $pathImage ?>" alt="<?= htmlspecialchars($announce->getTitle()) ?>">

                            <!-- Badge type -->
                            <span class="announce-type-badge <?= $announce->getType() == 'offer' ? 'badge-offer' : 'badge-request' ?>">
                                <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                            </span>

                            <!-- Badge statut -->
                            <?php if ($announce->getStatus() == 'closed'): ?>
                                <span class="announce-status-badge">
                                    <i class="fa-solid fa-lock"></i> Clôturée
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="announce-card-body">
                            <h3><?= htmlspecialchars($announce->getTitle()) ?></h3>
                            <p><?= htmlspecialchars(substr($announce->getDescription(), 0, 80)) ?>...</p>

                            <?php if ($announce->getType() == 'offer'): ?>
                                <p class="announce-price">
                                    <strong><?= number_format($announce->getPrice(), 2, ',', ' ') ?> €</strong>
                                </p>
                            <?php endif; ?>

                            <div class="announce-meta">
                                <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($announce->getCityName()) ?></span>
                                <span><i class="fa-solid fa-clock"></i> <?= date('d/m/Y', strtotime($announce->getCreatedAt())) ?></span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                <p>Aucune annonce publiée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>