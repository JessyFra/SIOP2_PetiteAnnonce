<?php if (!empty($message)): ?>
    <div id="messageBox" data-message="<?php echo htmlspecialchars($message); ?>"></div>
<?php endif; ?>

<div class="profile-container">
    <h1 class="profile-header">Mon Profil</h1>

    <form method="post" class="profile-form" id="profile-form">
        <div class="profile-row">
            <span class="profile-label">Nom d'utilisateur</span>
            <span class="profile-value" id="name-display"><?= htmlspecialchars($user->getName()) ?></span>
            <input type="text" class="profile-input" id="name-input" name="name" value="<?= htmlspecialchars($user->getName()) ?>" hidden>
            <button type="button" class="edit-btn" onclick="toggleEdit('name')">✏️</button>
        </div>

        <div class="profile-row">
            <span class="profile-label">Nom global</span>
            <span class="profile-value" id="global_name-display"><?= htmlspecialchars($user->getGlobalName()) ?></span>
            <input type="text" class="profile-input" id="global_name-input" name="global_name" value="<?= htmlspecialchars($user->getGlobalName()) ?>" hidden>
            <button type="button" class="edit-btn" onclick="toggleEdit('global_name')">✏️</button>
        </div>

        <div class="profile-row">
            <span class="profile-label">Biographie</span>
            <span class="profile-value" id="biography-display"><?= nl2br(htmlspecialchars($user->getBiography())) ?></span>
            <textarea class="profile-input" id="biography-input" name="biography" hidden><?= htmlspecialchars($user->getBiography()) ?></textarea>
            <button type="button" class="edit-btn" onclick="toggleEdit('biography')">✏️</button>
        </div>

        <div class="profile-row">
            <span class="profile-label">Mot de passe</span>
            <span class="profile-value" id="password-display">••••••••</span>
            <input type="password" class="profile-input" id="password-input" name="new_password" placeholder="Nouveau mot de passe" hidden>
            <button type="button" class="edit-btn" onclick="togglePassword()">✏️</button>
        </div>

        <div class="profile-row muted">
            <span class="profile-label">Rôle</span>
            <span class="profile-value">
                <?php if (htmlspecialchars($user->getRole()) == "user") {
                    echo "Utilisateur";
                } else {
                    echo "Administrateur";
                } ?>
            </span>
        </div>

        <div class="profile-row muted">
            <span class="profile-label">Compte créé le</span>
            <span class="profile-value"><?= date('d/m/Y à H:i', strtotime($user->getCreatedAt())) ?></span>
        </div>

        <div class="profile-actions">
            <input type="submit" name="updateProfile" value="Enregistrer" class="btn-discreet" id="save-btn" hidden>
            <button type="button" class="btn-discreet danger" id="cancel-btn" onclick="cancelEdits()" hidden>Annuler</button>
            <button type="submit" name="deleteAccount" class="btn-discreet danger"
                onclick="return confirm('Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.');">
                Supprimer
            </button>
        </div>
    </form>

    <!-- ------------------- Mes annonces ------------------- -->
    <h2 class="profile-subheader">Mes annonces</h2>

    <?php
    // Récupérer les annonces de l'utilisateur
    require_once 'private/src/model/DAO/AnnounceDAO.php';
    $announces = AnnounceDAO::getByUser($user->getId());

    if (!empty($announces)):
    ?>
        <table class="profile-announces">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Prix</th>
                    <th>Date de publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($announces as $announce): ?>
                    <tr>
                        <td><?= htmlspecialchars($announce->getTitle()) ?></td>
                        <td><?= htmlspecialchars(number_format($announce->getPrice(), 2, ',', ' ')) ?> €</td>
                        <td><?= date('d/m/Y à H:i', strtotime($announce->getCreatedAt())) ?></td>
                        <td>
                            <a href="index.php?page=edit&id=<?= $announce->getId() ?>" class="btn-discreet">Modifier</a>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="deleteAnnounceId" value="<?= $announce->getId() ?>">
                                <button type="submit" class="btn-discreet danger"
                                    onclick="return confirm('Voulez-vous vraiment retirer cette annonce ?');">
                                    Retirer
                                </button>
                            </form>
                            <?php if ($announce->getStatus() != 'closed'): ?>
                                <form method="post" action="" style="display:inline;">
                                    <input type="hidden" name="closeAnnounceId" value="<?= $announce->getId() ?>">
                                    <button type="submit" class="btn-discreet">
                                        Clôturer
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="muted">Clôturée</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune annonce publiée pour le moment.</p>
    <?php endif; ?>
</div>