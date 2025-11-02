<?php if (!empty($message)): ?>
    <div id="messageBox" data-message="<?php echo htmlspecialchars($message); ?>"></div>
<?php endif; ?>

<div class="admin-container">
    <!-- Header -->
    <div class="admin-header">
        <div>
            <h1 class="admin-title">
                <i class="fa-solid fa-users"></i>
                Gestion des utilisateurs
            </h1>
            <p class="admin-subtitle"><?= count($users) ?> utilisateur(s) enregistré(s)</p>
        </div>
    </div>

    <!-- Navigation admin -->
    <div class="admin-nav">
        <a href="index.php?page=admin" class="admin-nav-link">
            <i class="fa-solid fa-chart-line"></i>
            Tableau de bord
        </a>
        <a href="index.php?page=admin-users" class="admin-nav-link active">
            <i class="fa-solid fa-users"></i>
            Utilisateurs
        </a>
        <a href="index.php?page=admin-announces" class="admin-nav-link">
            <i class="fa-solid fa-bullhorn"></i>
            Annonces
        </a>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="admin-card">
        <div class="card-header">
            <h2>Liste des utilisateurs</h2>
        </div>

        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Nom global</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Inscrit le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?= $user->getId() ?></td>
                            <td><?= htmlspecialchars($user->getName()) ?></td>
                            <td><?= htmlspecialchars($user->getGlobalName() ?? '-') ?></td>
                            <td>
                                <?php if ($user->getRole() == 'admin'): ?>
                                    <span class="badge badge-danger">
                                        <i class="fa-solid fa-shield"></i> Admin
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Utilisateur</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user->isBanned()): ?>
                                    <span class="badge badge-warning">
                                        <i class="fa-solid fa-ban"></i> Banni
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-success">
                                        <i class="fa-solid fa-check"></i> Actif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user->getCreatedAt())) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($user->getRole() != 'admin'): ?>
                                        <!-- Bannir/Débannir -->
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="userId" value="<?= $user->getId() ?>">
                                            <input type="hidden" name="currentBanned" value="<?= $user->isBanned() ? 1 : 0 ?>">
                                            <button
                                                type="submit"
                                                name="toggleBan"
                                                class="btn-action <?= $user->isBanned() ? 'btn-success' : 'btn-warning' ?>"
                                                title="<?= $user->isBanned() ? 'Débannir' : 'Bannir' ?>">
                                                <i class="fa-solid fa-<?= $user->isBanned() ? 'check' : 'ban' ?>"></i>
                                            </button>
                                        </form>

                                        <!-- Supprimer -->
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="userId" value="<?= $user->getId() ?>">
                                            <button
                                                type="submit"
                                                name="deleteUser"
                                                class="btn-action btn-danger"
                                                onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');"
                                                title="Supprimer">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>