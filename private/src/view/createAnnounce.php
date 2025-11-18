<?php
// Vérification de l'authentification
if (!isset($_SESSION['userID'])) {
    header("Location: index.php?page=auth");
    exit;
}

// Récupération des catégories et villes
require_once 'private/src/model/DAO/CategoryDAO.php';
require_once 'private/src/model/DAO/CityDAO.php';

$categories = CategoryDAO::getAll();
$cities = CityDAO::getAll();
?>

<div class="create-announce-container">
    <div class="announce-form-card">
        <div class="form-header">
            <h1>
                <i class="fa-solid fa-plus-circle"></i>
                Créer une annonce
            </h1>
            <p>Remplissez le formulaire ci-dessous pour publier votre annonce</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data" id="announceForm">

            <!-- Section: Type d'annonce -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fa-solid fa-tag"></i>
                    Type d'annonce
                </h3>

                <div class="type-selector">
                    <div class="type-option">
                        <input type="radio" name="type" id="type-offer" value="offer" checked>
                        <label for="type-offer" class="type-label">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <span>Je vends (Offre)</span>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="type" id="type-request" value="request">
                        <label for="type-request" class="type-label">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span>Je cherche (Demande)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section: Informations générales -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fa-solid fa-circle-info"></i>
                    Informations générales
                </h3>

                <div class="form-group">
                    <label for="title">
                        Titre <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="title"
                        name="title"
                        maxlength="100"
                        placeholder="Ex: Vends MacBook Pro 2023"
                        required>
                    <small class="form-text">Maximum 100 caractères</small>
                </div>

                <div class="form-group">
                    <label for="description">
                        Description <span class="required">*</span>
                    </label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        maxlength="2000"
                        placeholder="Décrivez votre annonce en détail..."
                        required></textarea>
                    <small class="form-text">Maximum 2000 caractères</small>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">
                                Prix (€) <span id="priceRequired" class="required">*</span>
                            </label>
                            <input
                                type="number"
                                class="form-control"
                                id="price"
                                name="price"
                                min="0"
                                step="0.01"
                                placeholder="0.00">
                            <small class="form-text">Laissez vide ou 0 pour les demandes</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">
                                Ville <span class="required">*</span>
                            </label>
                            <select class="form-control" id="city" name="city_id" required>
                                <option value="">Sélectionnez une ville</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city->getId() ?>">
                                        <?= htmlspecialchars($city->getName()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Catégories -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fa-solid fa-folder-open"></i>
                    Catégories <span class="required">*</span>
                </h3>

                <div class="categories-grid">
                    <?php foreach ($categories as $category): ?>
                        <div class="category-option">
                            <input
                                type="checkbox"
                                name="categories[]"
                                id="cat-<?= $category->getId() ?>"
                                value="<?= $category->getId() ?>">
                            <label for="cat-<?= $category->getId() ?>">
                                <?= htmlspecialchars($category->getName()) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="form-text">Sélectionnez au moins une catégorie</small>
            </div>

            <!-- Section: Photos -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fa-solid fa-images"></i>
                    Photos <span class="required">*</span>
                </h3>

                <div class="image-upload-zone" id="uploadZone">
                    <div class="upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div class="upload-text">Cliquez ou glissez vos images ici</div>
                    <div class="upload-subtext">PNG, JPG jusqu'à 5MB - Maximum 5 images</div>
                    <input
                        type="file"
                        id="imageInput"
                        name="images[]"
                        accept="image/png,image/jpeg,image/jpg"
                        multiple>
                </div>

                <div class="image-preview-grid" id="imagePreview"></div>
                <small class="form-text">La première image sera l'image principale</small>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="index.php?page=annonces" class="btn btn-secondary">
                    <i class="fa-solid fa-times"></i>
                    Annuler
                </a>
                <button type="submit" name="createAnnounce" class="btn publishBtn">
                    <i class="fa-solid fa-check"></i>
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
</div>