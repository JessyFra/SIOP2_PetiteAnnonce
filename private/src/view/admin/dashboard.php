<?php if (!empty($message)): ?>
    <div id="messageBox" data-message="<?php echo htmlspecialchars($message); ?>"></div>
<?php endif; ?>

<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1 class="admin-title">
                <i class="fa-solid fa-shield-halved"></i>
                Espace Administrateur
            </h1>
            <p class="admin-subtitle">Bienvenue <?= htmlspecialchars($admin->getGlobalName() ?? $admin->getName()) ?></p>
        </div>
    </div>

    <!-- Navigation admin -->
    <div class="admin-nav">
        <a href="index.php?page=admin" class="admin-nav-link active">
            <i class="fa-solid fa-chart-line"></i>
            Tableau de bord
        </a>
        <a href="index.php?page=admin-users" class="admin-nav-link">
            <i class="fa-solid fa-users"></i>
            Utilisateurs
        </a>
        <a href="index.php?page=admin-announces" class="admin-nav-link">
            <i class="fa-solid fa-bullhorn"></i>
            Annonces
        </a>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Utilisateurs</p>
                <h3 class="stat-value"><?= $totalUsers ?></h3>
                <p class="stat-detail"><?= $bannedUsers ?> banni(s)</p>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Annonces</p>
                <h3 class="stat-value"><?= $totalAnnounces ?></h3>
                <p class="stat-detail"><?= $openAnnounces ?> active(s)</p>
            </div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fa-solid fa-chart-simple"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Taux d'activité</p>
                <h3 class="stat-value">
                    <?= $totalAnnounces > 0 ? round(($openAnnounces / $totalAnnounces) * 100) : 0 ?>%
                </h3>
                <p class="stat-detail">Annonces ouvertes</p>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Dernière connexion</p>
                <h3 class="stat-value"><?= date('H:i') ?></h3>
                <p class="stat-detail"><?= date('d/m/Y') ?></p>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions">
        <h2>Actions rapides</h2>
        <div class="actions-grid">
            <a href="index.php?page=admin-users" class="action-card">
                <i class="fa-solid fa-user-shield"></i>
                <h3>Gérer les utilisateurs</h3>
                <p>Bannir, débannir ou supprimer des comptes</p>
            </a>

            <a href="index.php?page=admin-announces" class="action-card">
                <i class="fa-solid fa-pen-to-square"></i>
                <h3>Gérer les annonces</h3>
                <p>Modifier, clôturer ou supprimer des annonces</p>
            </a>

            <a href="index.php?page=annonces" class="action-card">
                <i class="fa-solid fa-eye"></i>
                <h3>Voir le site</h3>
                <p>Consulter le site en tant qu'utilisateur</p>
            </a>
        </div>
    </div>

    <!-- Dernières annonces -->
    <div class="admin-card">
        <div class="card-header">
            <h2>Dernières annonces</h2>
            <a href="index.php?page=admin-announces" class="btn-link">Voir tout</a>
        </div>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Auteur</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recentAnnounces = array_slice($allAnnounces, 0, 5);
                    foreach ($recentAnnounces as $announce):
                    ?>
                        <tr>
                            <td>#<?= $announce->getId() ?></td>
                            <td><?= htmlspecialchars(substr($announce->getTitle(), 0, 40)) ?></td>
                            <td>
                                <span class="badge <?= $announce->getType() == 'offer' ? 'badge-success' : 'badge-info' ?>">
                                    <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $announce->getStatus() == 'open' ? 'badge-success' : 'badge-secondary' ?>">
                                    <?= $announce->getStatus() == 'open' ? 'Ouverte' : 'Fermée' ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($announce->getAuthorName()) ?></td>
                            <td><?= date('d/m/Y', strtotime($announce->getCreatedAt())) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>