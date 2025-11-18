<?php
// Vérification de l'authentification
if (!isset($_SESSION['userID'])) {
    header("Location: index.php?page=auth");
    exit;
}

// Vérification de l'ID de l'annonce
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?page=profil");
    exit;
}

$announceId = intval($_GET['id']);

// Récupération de l'annonce
require_once 'private/src/model/DAO/AnnounceDAO.php';
require_once 'private/src/model/DAO/AnnounceCategoryDAO.php';
require_once 'private/src/model/DAO/AnnounceImageDAO.php';

$announce = AnnounceDAO::get($announceId);

// Vérification que l'annonce existe et appartient à l'utilisateur
if (!$announce || $announce->getAuthorId() != $_SESSION['userID']) {
    header("Location: index.php?page=profil");
    exit;
}

// Récupération des catégories et villes
require_once 'private/src/model/DAO/CategoryDAO.php';
require_once 'private/src/model/DAO/CityDAO.php';

$categories = CategoryDAO::getAll();
$cities = CityDAO::getAll();

// Récupération des catégories de l'annonce
$announceCategories = AnnounceCategoryDAO::getAll();
$selectedCategoryIds = [];
foreach ($announceCategories as $ac) {
    if ($ac->getAnnounceId() == $announceId) {
        $selectedCategoryIds[] = $ac->getCategoryId();
    }
}

// Récupération des images existantes
$existingImages = AnnounceImageDAO::getByAnnounce($announceId);
?>

<div class="create-announce-container">
    <div class="announce-form-card">
        <div class="form-header">
            <h1>
                <i class="fa-solid fa-pen-to-square"></i>
                Modifier l'annonce
            </h1>
            <p>Modifiez les informations de votre annonce</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?= htmlspecialchars($success) ?></span>
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
                        <input type="radio" name="type" id="type-offer" value="offer"
                            <?= $announce->getType() == 'offer' ? 'checked' : '' ?>>
                        <label for="type-offer" class="type-label">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <span>Je vends (Offre)</span>
                        </label>
                    </div>
                    <div class="type-option">
                        <input type="radio" name="type" id="type-request" value="request"
                            <?= $announce->getType() == 'request' ? 'checked' : '' ?>>
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
                        value="<?= htmlspecialchars($announce->getTitle()) ?>"
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
                        required><?= htmlspecialchars($announce->getDescription()) ?></textarea>
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
                                value="<?= $announce->getPrice() ?>"
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
                                    <option value="<?= $city->getId() ?>"
                                        <?= $city->getId() == $announce->getCityId() ? 'selected' : '' ?>>
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
                                value="<?= $category->getId() ?>"
                                <?= in_array($category->getId(), $selectedCategoryIds) ? 'checked' : '' ?>>
                            <label for="cat-<?= $category->getId() ?>">
                                <?= htmlspecialchars($category->getName()) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <small class="form-text">Sélectionnez au moins une catégorie</small>
            </div>

            <!-- Section: Photos existantes -->
            <?php if (!empty($existingImages)): ?>
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fa-solid fa-images"></i>
                        Photos actuelles
                    </h3>

                    <div class="image-preview-grid" id="existingImages">
                        <?php foreach ($existingImages as $index => $image): ?>
                            <div class="image-preview-item <?= $image->isMain() ? 'main-image' : '' ?>" data-image-id="<?= $image->getId() ?>">
                                <img src="<?= $image->getFullPath() ?>" alt="Image">
                                <?php if ($image->isMain()): ?>
                                    <span class="main-badge"><i class="fa-solid fa-star"></i> Principale</span>
                                <?php endif; ?>
                                <div class="image-actions">
                                    <button type="button" class="image-action-btn delete btn-delete-existing" data-image-id="<?= $image->getId() ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Section: Ajouter de nouvelles photos -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fa-solid fa-plus"></i>
                    Ajouter de nouvelles photos
                </h3>

                <div class="image-upload-zone" id="uploadZone">
                    <div class="upload-icon">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                    </div>
                    <div class="upload-text">Cliquez ou glissez vos images ici</div>
                    <div class="upload-subtext">PNG, JPG jusqu'à 5MB - Maximum 5 images au total</div>
                    <input
                        type="file"
                        id="imageInput"
                        name="new_images[]"
                        accept="image/png,image/jpeg,image/jpg"
                        multiple>
                </div>

                <div class="image-preview-grid" id="imagePreview"></div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="index.php?page=profil" class="btn btn-secondary">
                    <i class="fa-solid fa-times"></i>
                    Annuler
                </a>
                <button type="submit" name="updateAnnounce" class="btn editBtn">
                    <i class="fa-solid fa-check"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>