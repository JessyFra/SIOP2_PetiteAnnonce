<?php if (!empty($message)): ?>
    <div id="messageBox" data-message="<?php echo htmlspecialchars($message); ?>"></div>
<?php endif; ?>

<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1 class="admin-title">
                <i class="fa-solid fa-bullhorn"></i>
                Gestion des annonces
            </h1>
            <p class="admin-subtitle"><?= count($announces) ?> annonce(s) trouvée(s)</p>
        </div>
    </div>

    <!-- Navigation admin -->
    <div class="admin-nav">
        <a href="index.php?page=admin" class="admin-nav-link">
            <i class="fa-solid fa-chart-line"></i>
            Tableau de bord
        </a>
        <a href="index.php?page=admin-users" class="admin-nav-link">
            <i class="fa-solid fa-users"></i>
            Utilisateurs
        </a>
        <a href="index.php?page=admin-announces" class="admin-nav-link active">
            <i class="fa-solid fa-bullhorn"></i>
            Annonces
        </a>
    </div>

    <!-- Filtres -->
    <div class="admin-filters">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="admin-announces">

            <div class="filter-group">
                <label>Statut :</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="open" <?= (isset($_GET['status']) && $_GET['status'] == 'open') ? 'selected' : '' ?>>
                        Ouvertes
                    </option>
                    <option value="closed" <?= (isset($_GET['status']) && $_GET['status'] == 'closed') ? 'selected' : '' ?>>
                        Fermées
                    </option>
                </select>
            </div>

            <div class="filter-group">
                <label>Type :</label>
                <select name="type" class="form-select">
                    <option value="">Tous</option>
                    <option value="offer" <?= (isset($_GET['type']) && $_GET['type'] == 'offer') ? 'selected' : '' ?>>
                        Offres
                    </option>
                    <option value="request" <?= (isset($_GET['type']) && $_GET['type'] == 'request') ? 'selected' : '' ?>>
                        Demandes
                    </option>
                </select>
            </div>

            <button type="submit" class="btn filterSearchBtn">
                <i class="fa-solid fa-filter"></i> Filtrer
            </button>
            <a href="index.php?page=admin-announces" class="btn btn-secondary">
                <i class="fa-solid fa-rotate-right"></i> Réinitialiser
            </a>
        </form>
    </div>

    <!-- Tableau des annonces -->
    <div class="admin-card">
        <div class="card-header">
            <h2>Liste des annonces</h2>
        </div>

        <?php if (!empty($announces)): ?>
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Ville</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($announces as $announce): ?>
                            <tr>
                                <td>#<?= $announce->getId() ?></td>
                                <td>
                                    <a href="index.php?page=annonce&id=<?= $announce->getId() ?>" target="_blank">
                                        <?= htmlspecialchars(substr($announce->getTitle(), 0, 40)) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge <?= $announce->getType() == 'offer' ? 'badge-success' : 'badge-info' ?>">
                                        <?= $announce->getType() == 'offer' ? 'Offre' : 'Demande' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($announce->getType() == 'offer'): ?>
                                        <?= number_format($announce->getPrice(), 2, ',', ' ') ?> €
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?= $announce->getStatus() == 'open' ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $announce->getStatus() == 'open' ? 'Ouverte' : 'Fermée' ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($announce->getCityName()) ?></td>
                                <td><?= htmlspecialchars($announce->getAuthorName()) ?></td>
                                <td><?= date('d/m/Y', strtotime($announce->getCreatedAt())) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- Clôturer/Rouvrir -->
                                        <?php if ($announce->getStatus() == 'open'): ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="announceId" value="<?= $announce->getId() ?>">
                                                <button
                                                    type="submit"
                                                    name="closeAnnounce"
                                                    class="btn-action btn-warning"
                                                    title="Clôturer">
                                                    <i class="fa-solid fa-pause"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="announceId" value="<?= $announce->getId() ?>">
                                                <button
                                                    type="submit"
                                                    name="reopenAnnounce"
                                                    class="btn-action btn-success"
                                                    title="Rouvrir">
                                                    <i class="fa-solid fa-play"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <!-- Supprimer -->
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="announceId" value="<?= $announce->getId() ?>">
                                            <button
                                                type="submit"
                                                name="deleteAnnounce"
                                                class="btn-action btn-danger"
                                                onclick="return confirm('Voulez-vous vraiment supprimer cette annonce ?');"
                                                title="Supprimer">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fa-solid fa-inbox" style="font-size: 48px; color: #ccc;"></i>
                <p>Aucune annonce trouvée.</p>
            </div>
        <?php endif; ?>
    </div>
</div>