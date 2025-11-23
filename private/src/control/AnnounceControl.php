<?php

include_once 'private/src/model/DAO/AnnounceDAO.php';
include_once 'private/src/model/DAO/CategoryDAO.php';
include_once 'private/src/model/DAO/CityDAO.php';

class AnnounceControl
{

    public function announces()
    {
        // Récupération des filtres depuis l'URL
        $filters = [];

        if (!empty($_GET['category'])) {
            $filters['category_id'] = intval($_GET['category']);
        }

        if (!empty($_GET['city'])) {
            $filters['city_id'] = intval($_GET['city']);
        }

        if (!empty($_GET['type'])) {
            $filters['type'] = $_GET['type'];
        }

        if (!empty($_GET['search'])) {
            $filters['search'] = $_GET['search'];
        }

        // Récupération des annonces filtrées
        if (!empty($filters)) {
            $announces = AnnounceDAO::search($filters);
        } else {
            $announces = AnnounceDAO::getAll();
        }

        // Récupération des catégories et villes pour les filtres
        $categories = CategoryDAO::getAll();
        $cities = CityDAO::getAll();

        // Vue de la liste des annonces
        include_once 'private/src/view/announces.php';
    }

    public function announce()
    {
        // Vue de l'annonce
        include_once 'private/src/view/announce.php';
    }

    // Page de création d'annonce
    public function create()
    {
        // Vérification de l'authentification
        if (!isset($_SESSION['userID'])) {
            header("Location: index.php?page=auth");
            exit;
        }

        $error = null;

        // Traitement du formulaire
        if (isset($_POST['createAnnounce'])) {
            // Récupération des données
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $type = $_POST['type'];
            $cityId = intval($_POST['city_id']);
            $authorId = $_SESSION['userID'];
            $categoryIds = $_POST['categories'] ?? [];

            // Validation
            if (empty($title)) {
                $error = "Le titre est obligatoire.";
            } elseif (empty($description)) {
                $error = "La description est obligatoire.";
            } elseif (empty($cityId) || $cityId == 0) {
                $error = "Veuillez sélectionner une ville.";
            } elseif (empty($categoryIds)) {
                $error = "Veuillez sélectionner au moins une catégorie.";
            } elseif ($type == 'offer' && $price <= 0) {
                $error = "Le prix doit être supérieur à 0 pour une offre.";
            } elseif (!isset($_FILES['images']) || !isset($_FILES['images']['name']) || empty($_FILES['images']['name'][0])) {
                $error = "Veuillez ajouter au moins une image.";
            } elseif ($_FILES['images']['error'][0] === UPLOAD_ERR_NO_FILE) {
                $error = "Aucune image n'a été téléchargée.";
            } else {
                // Insertion de l'annonce
                require_once 'private/src/model/DAO/AnnounceDAO.php';
                $announceId = AnnounceDAO::insert($title, $description, $price, $type, $cityId, $authorId, $categoryIds);

                if ($announceId) {
                    // Upload des images
                    require_once 'private/src/model/DAO/AnnounceImageDAO.php';
                    $uploadSuccess = $this->handleImageUpload($_FILES['images'], $announceId);

                    if ($uploadSuccess) {
                        header("Location: index.php?page=annonce&id=" . $announceId);
                        exit;
                    } else {
                        $error = "Erreur lors de l'upload des images.";
                    }
                } else {
                    $error = "Erreur lors de la création de l'annonce.";
                }
            }
        }

        // Récupération des catégories et villes
        $categories = CategoryDAO::getAll();
        $cities = CityDAO::getAll();

        include_once 'private/src/view/createAnnounce.php';
    }

    // Page de modification d'annonce
    public function edit()
    {
        // Vérification de l'authentification
        if (!isset($_SESSION['userID'])) {
            header("Location: index.php?page=auth");
            exit;
        }

        $error = null;
        $success = null;

        // Traitement du formulaire
        if (isset($_POST['updateAnnounce'])) {
            $announceId = intval($_GET['id']);

            // Récupération des données
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $price = floatval($_POST['price']);
            $type = $_POST['type'];
            $cityId = intval($_POST['city_id']);
            $categoryIds = $_POST['categories'] ?? [];

            // Validation
            if (empty($title)) {
                $error = "Le titre est obligatoire.";
            } elseif (empty($description)) {
                $error = "La description est obligatoire.";
            } elseif (empty($cityId)) {
                $error = "Veuillez sélectionner une ville.";
            } elseif (empty($categoryIds)) {
                $error = "Veuillez sélectionner au moins une catégorie.";
            } elseif ($type == 'offer' && $price <= 0) {
                $error = "Le prix doit être supérieur à 0 pour une offre.";
            } else {
                // Mise à jour de l'annonce
                require_once 'private/src/model/DAO/AnnounceDAO.php';
                $updateSuccess = AnnounceDAO::update($announceId, $title, $description, $price, $type, $cityId, $categoryIds);

                // Suppression des images marquées
                if (!empty($_POST['delete_images'])) {
                    require_once 'private/src/model/DAO/AnnounceImageDAO.php';
                    foreach ($_POST['delete_images'] as $imageId) {
                        $image = AnnounceImageDAO::get($imageId);
                        if ($image && file_exists($image->getFullPath())) {
                            unlink($image->getFullPath());
                        }
                        AnnounceImageDAO::delete($imageId);
                    }
                }

                // Upload de nouvelles images
                if (!empty($_FILES['new_images']['name'][0])) {
                    require_once 'private/src/model/DAO/AnnounceImageDAO.php';

                    // Vérifier si on a déjà une image principale
                    $existingImages = AnnounceImageDAO::getByAnnounce($announceId);
                    $hasMainImage = false;
                    foreach ($existingImages as $img) {
                        if ($img->isMain()) {
                            $hasMainImage = true;
                            break;
                        }
                    }

                    $this->handleImageUpload($_FILES['new_images'], $announceId, !$hasMainImage);
                }

                if ($updateSuccess) {
                    $success = "Annonce modifiée avec succès !";
                    header("Location: index.php?page=annonce&id=" . $announceId);
                    exit;
                } else {
                    $error = "Erreur lors de la modification de l'annonce.";
                }
            }
        }

        include_once 'private/src/view/editAnnounce.php';
    }

    //  Gère l'upload des images
    private function handleImageUpload($files, $announceId, $setFirstAsMain = true)
    {
        require_once 'private/src/model/DAO/AnnounceImageDAO.php';

        $uploadDir = 'public/assets/img/';

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        $uploadedCount = 0;
        $errors = [];

        // Vérifie qu'on a bien un tableau de fichiers
        if (!isset($files['name']) || !is_array($files['name'])) {
            return false;
        }

        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            // Ignore les fichiers vides ou avec erreurs
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE || empty($files['name'][$i])) {
                continue;
            }

            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                $errors[] = "Erreur lors de l'upload du fichier {$files['name'][$i]}";
                continue;
            }

            $fileType = $files['type'][$i];
            $fileSize = $files['size'][$i];

            // Validation du type
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = "Type de fichier non autorisé pour {$files['name'][$i]}";
                continue;
            }

            // Validation de la taille
            if ($fileSize > $maxSize) {
                $errors[] = "Fichier trop volumineux : {$files['name'][$i]} (max 5MB)";
                continue;
            }

            // Génération d'un nom de fichier UNIQUE
            $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename = $announceId . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            // Déplacement du fichier
            if (move_uploaded_file($files['tmp_name'][$i], $filepath)) {
                // La première image uploadée est principale
                $isMain = ($setFirstAsMain && $uploadedCount === 0) ? 1 : 0;
                AnnounceImageDAO::insert($announceId, $filename, $isMain);
                $uploadedCount++;
            } else {
                $errors[] = "Échec du déplacement du fichier {$files['name'][$i]}";
            }
        }

        return $uploadedCount > 0;
    }

    // API de recherche pour l'autocomplétion
    public function searchApi()
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';

        if (empty($query)) {
            echo json_encode([]);
            exit;
        }

        $announces = AnnounceDAO::search(['search' => $query]);

        $results = [];
        foreach ($announces as $announce) {
            $results[] = [
                'id' => $announce->getId(),
                'title' => $announce->getTitle(),
                'type' => $announce->getType(),
                'price' => number_format($announce->getPrice(), 2, ',', ' '),
                'city' => $announce->getCityName(),
                'status' => $announce->getStatus()
            ];
        }

        echo json_encode($results);
        exit;
    }
}
