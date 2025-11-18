<?php

include_once 'private/src/helper/DateHelper.php';
include_once 'private/src/model/DAO/AnnounceDAO.php';
$announces = AnnounceDAO::getByUser($user->getId());

// S'assurer que $announces est un tableau
if (!is_array($announces)) {
    $announces = [];
}
?>

<?php if (!empty($message)): ?>
    <div id="messageBox" class="alert-message">
        <span class="alert-icon">✓</span>
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="dashboard-container">
    <!-- Header avec avatar -->
    <div class="dashboard-header">
        <div class="user-welcome">
            <div class="user-avatar">
                <?= strtoupper(substr($user->getName(), 0, 1)) ?>
            </div>
            <div>
                <h1 class="dashboard-title">Tableau de bord</h1>
                <p class="dashboard-subtitle">Bienvenue, <?= $user->getGlobalName() ?></p>
            </div>
        </div>
        <div class="user-badge">
            <?php if (htmlspecialchars($user->getRole()) == "admin"): ?>
                <span class="badge badge-admin">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" />
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                    Administrateur
                </span>
            <?php else: ?>
                <span class="badge badge-user">Utilisateur</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistiques cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-primary">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <path d="M3 9h18M9 3v18" />
                </svg>
            </div>
            <div class="stat-content">
                <p class="stat-label">Mes annonces</p>
                <h3 class="stat-value"><?= count($announces) ?></h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-success">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                </svg>
            </div>
            <div class="stat-content">
                <p class="stat-label">Annonces actives</p>
                <h3 class="stat-value"><?= count(array_filter($announces, fn($a) => $a->getStatus() != 'closed')) ?></h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-warning">
                <i class="fa-solid fa-clock" style="font-size: 24px;"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Membre depuis</p>
                <h3 class="stat-value"><?= floor((time() - strtotime($user->getCreatedAt())) / (60 * 60 * 24)) ?> jours</h3>
            </div>
        </div>
    </div>

    <!-- Contenu principal en deux colonnes -->
    <div class="dashboard-grid">
        <!-- Colonne gauche - Informations profil -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title">Informations du profil</h2>
                <p class="card-description">Gérez vos informations personnelles</p>
            </div>

            <form method="post" class="profile-form" id="profile-form">
                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Nom d'utilisateur
                    </label>
                    <div class="input-group">
                        <span class="form-value" id="name-display"><?= htmlspecialchars($user->getName()) ?></span>
                        <input type="text" class="form-input" id="name-input" name="name" value="<?= htmlspecialchars($user->getName()) ?>" hidden>
                        <button type="button" class="icon-btn" onclick="toggleEdit('name')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                        Nom global
                    </label>
                    <div class="input-group">
                        <span class="form-value" id="global_name-display"><?= $user->getGlobalName() ?></span>
                        <input type="text" class="form-input" id="global_name-input" name="global_name" value="<?= $user->getGlobalName() ?>" hidden>
                        <button type="button" class="icon-btn" onclick="toggleEdit('global_name')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                        Biographie
                    </label>
                    <div class="input-group">
                        <span class="form-value" id="biography-display"><?= nl2br($user->getBiography() ?? "Aucune description...") ?></span>
                        <textarea class="form-input form-textarea" id="biography-input" name="biography" hidden><?= $user->getBiography() ?? "Aucune description..." ?></textarea>
                        <button type="button" class="icon-btn" onclick="toggleEdit('biography')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                        Mot de passe
                    </label>
                    <div class="input-group">
                        <span class="form-value" id="password-display">••••••••</span>
                        <input type="password" class="form-input" id="password-input" name="new_password" placeholder="Nouveau mot de passe" hidden>
                        <button type="button" class="icon-btn" onclick="togglePassword()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-divider"></div>

                <div class="form-group-readonly">
                    <div class="readonly-item">
                        <span class="readonly-label">Rôle</span>
                        <span class="readonly-value">
                            <?php if (htmlspecialchars($user->getRole()) == "user") {
                                echo "Utilisateur";
                            } else {
                                echo "Administrateur";
                            } ?>
                        </span>
                    </div>
                    <div class="readonly-item">
                        <span class="readonly-label">Compte créé le</span>
                        <span class="readonly-value"><?= date('d/m/Y à H:i', strtotime($user->getCreatedAt())) ?></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancel-btn" onclick="cancelEdits()" hidden>
                        Annuler
                    </button>
                    <button type="submit" name="updateProfile" class="btn btn-primary" id="save-btn" hidden>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Enregistrer
                    </button>
                </div>
            </form>

            <div class="card-footer">
                <button type="submit" name="deleteAccount" form="profile-form" class="btn btn-danger-outline"
                    onclick="return confirm('Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.');">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                    Supprimer mon compte
                </button>
            </div>
        </div>

        <!-- ------------------- Mes annonces ------------------- -->
        <!-- Colonne droite - Mes annonces -->
        <div class="dashboard-card">
            <div class="card-header">
                <h2 class="card-title">Mes annonces</h2>
                <p class="card-description">Gérez vos annonces publiées</p>
            </div>

            <?php if (!empty($announces)): ?>
                <div class="announces-list">
                    <?php foreach ($announces as $announce): ?>
                        <div class="announce-item">
                            <div class="announce-header">
                                <h4 class="announce-title"><?= htmlspecialchars($announce->getTitle()) ?></h4>
                                <?php if ($announce->getStatus() == 'closed'): ?>
                                    <span class="announce-badge badge-closed">Clôturée</span>
                                <?php else: ?>
                                    <span class="announce-badge badge-active">Active</span>
                                <?php endif; ?>
                            </div>

                            <div class="announce-details">
                                <div class="announce-detail">
                                    <i class="fa-solid fa-dollar-sign" style="font-size: 14px;"></i>
                                    <span><?= htmlspecialchars(number_format($announce->getPrice(), 2, ',', ' ')) ?> €</span>
                                </div>
                                <div class="announce-detail">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg>
                                    <span><?= DateHelper::getRelativeTime($announce->getCreatedAt()) ?></span>
                                </div>
                            </div>

                            <div class="announce-actions">
                                <a href="index.php?page=edit-announce&id=<?= $announce->getId() ?>" class="btn-small btn-small-primary">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                    Modifier
                                </a>

                                <?php if ($announce->getStatus() != 'closed'): ?>
                                    <!-- Bouton Clôturer pour les annonces actives -->
                                    <form method="post" action="" style="display:inline;">
                                        <input type="hidden" name="closeAnnounceId" value="<?= $announce->getId() ?>">
                                        <button type="submit" class="btn-small btn-small-secondary">
                                            <i class="fa-solid fa-pause" style="font-size: 14px;"></i>
                                            Clôturer
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <!-- Bouton Rouvrir pour les annonces clôturées -->
                                    <form method="post" action="" style="display:inline;">
                                        <input type="hidden" name="reopenAnnounceId" value="<?= $announce->getId() ?>">
                                        <button type="submit" class="btn-small btn-small-success">
                                            <i class="fa-solid fa-play" style="font-size: 14px;"></i>
                                            Rouvrir
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="deleteAnnounceId" value="<?= $announce->getId() ?>">
                                    <button type="submit" class="btn-small btn-small-danger"
                                        onclick="return confirm('Voulez-vous vraiment retirer cette annonce ?');">
                                        <i class="fa-solid fa-trash" style="font-size: 14px;"></i>
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18M9 3v18" />
                    </svg>
                    <p class="empty-state-title">Aucune annonce</p>
                    <p class="empty-state-text">Vous n'avez pas encore publié d'annonce</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>