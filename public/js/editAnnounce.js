// Gestion du type d'annonce (afficher/masquer le prix)
document.querySelectorAll('input[name="type"]').forEach((radio) => {
    radio.addEventListener("change", function () {
        const priceInput = document.getElementById("price");
        const priceRequired = document.getElementById("priceRequired");

        if (this.value === "request") {
            priceInput.required = false;
            priceInput.value = "0";
            if (priceRequired) priceRequired.style.display = "none";
        } else {
            priceInput.required = true;
            if (priceRequired) priceRequired.style.display = "inline";
        }
    });
});

// ========================================
// GESTION DE LA SUPPRESSION DES IMAGES EXISTANTES
// ========================================
document.addEventListener("DOMContentLoaded", function () {
    // Attacher les événements aux boutons de suppression des images existantes
    const deleteExistingButtons = document.querySelectorAll(
        ".btn-delete-existing"
    );

    deleteExistingButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const imageId = this.getAttribute("data-image-id");
            deleteExistingImage(imageId);
        });
    });
});

function deleteExistingImage(imageId) {
    console.log("Tentative de suppression de l'image:", imageId);

    // CORRECTION: Utiliser le bon sélecteur
    const imageElement = document.querySelector(
        `div.image-preview-item[data-image-id="${imageId}"]`
    );

    if (imageElement) {
        console.log("Image trouvée, marquage pour suppression");

        // Marquer visuellement comme supprimée
        imageElement.classList.add("to-delete");
        imageElement.style.opacity = "0.3";
        imageElement.style.position = "relative";
        imageElement.style.pointerEvents = "none";

        // Ajouter un overlay "À supprimer"
        const overlay = document.createElement("div");
        overlay.className = "delete-overlay";
        imageElement.appendChild(overlay);

        // Masquer le bouton de suppression
        const deleteBtn = imageElement.querySelector(".delete");
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.style.display = "none";
        }

        console.log("Image marquée visuellement pour suppression");
    } else {
        console.error("❌ Image non trouvée avec l'ID:", imageId);
        console.log(
            "Sélecteur utilisé:",
            `div.image-preview-item[data-image-id="${imageId}"]`
        );

        // Debug: afficher tous les éléments avec data-image-id
        const allImages = document.querySelectorAll("[data-image-id]");
        console.log("Images trouvées dans le DOM:", allImages.length);
        allImages.forEach((img) => {
            console.log("- Image ID:", img.getAttribute("data-image-id"));
        });
    }

    // Ajouter un champ caché pour marquer l'image à supprimer côté serveur
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "delete_images[]";
    input.value = imageId;
    input.className = "delete-marker-" + imageId;
    document.getElementById("announceForm").appendChild(input);

    console.log("✅ Champ caché ajouté pour l'image:", imageId);
}

// ========================================
// GESTION DE L'UPLOAD DE NOUVELLES IMAGES
// ========================================
const uploadZone = document.getElementById("uploadZone");
const imageInput = document.getElementById("imageInput");
const imagePreview = document.getElementById("imagePreview");
let selectedFiles = [];

if (uploadZone && imageInput) {
    uploadZone.addEventListener("click", () => imageInput.click());
    imageInput.addEventListener("change", handleFiles);

    // Drag & Drop
    uploadZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadZone.classList.add("drag-over");
    });

    uploadZone.addEventListener("dragleave", () => {
        uploadZone.classList.remove("drag-over");
    });

    uploadZone.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadZone.classList.remove("drag-over");

        const files = Array.from(e.dataTransfer.files).filter((file) =>
            file.type.startsWith("image/")
        );

        if (files.length > 0) {
            imageInput.files = createFileList([...selectedFiles, ...files]);
            handleFiles();
        }
    });
}

function handleFiles() {
    const files = Array.from(imageInput.files);

    // Compter les images existantes (non marquées pour suppression)
    const existingImagesContainer = document.getElementById("existingImages");
    const existingCount = existingImagesContainer
        ? existingImagesContainer.querySelectorAll(
              ".image-preview-item:not(.to-delete)"
          ).length
        : 0;

    // Ajouter les nouveaux fichiers à la liste au lieu de la remplacer
    const newFiles = files.filter((file) => {
        return !selectedFiles.some(
            (existing) =>
                existing.name === file.name && existing.size === file.size
        );
    });

    const totalImages = existingCount + selectedFiles.length + newFiles.length;

    if (totalImages > 5) {
        alert(
            `Vous ne pouvez avoir que 5 images maximum au total. Vous avez ${existingCount} image(s) existante(s) et ${selectedFiles.length} nouvelle(s) image(s) déjà sélectionnée(s).`
        );
        imageInput.value = "";
        return;
    }

    // Ajouter les nouveaux fichiers
    selectedFiles = [...selectedFiles, ...newFiles];
    imageInput.files = createFileList(selectedFiles);
    displayPreviews();
}

function displayPreviews() {
    if (!imagePreview) return;

    imagePreview.innerHTML = "";

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const div = document.createElement("div");
            div.className = "image-preview-item";
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <div class="image-actions">
                    <button type="button" class="image-action-btn delete" data-index="${index}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
            imagePreview.appendChild(div);

            // Ajouter l'événement de suppression
            const deleteBtn = div.querySelector(".delete");
            deleteBtn.addEventListener("click", function () {
                removeNewImage(parseInt(this.getAttribute("data-index")));
            });
        };
        reader.readAsDataURL(file);
    });
}

function removeNewImage(index) {
    selectedFiles.splice(index, 1);
    imageInput.files = createFileList(selectedFiles);
    displayPreviews();
}

function createFileList(files) {
    const dataTransfer = new DataTransfer();
    files.forEach((file) => dataTransfer.items.add(file));
    return dataTransfer.files;
}

// ========================================
// VALIDATION DU FORMULAIRE
// ========================================
const announceForm = document.getElementById("announceForm");
if (announceForm) {
    announceForm.addEventListener("submit", function (e) {
        const categories = document.querySelectorAll(
            'input[name="categories[]"]:checked'
        );

        if (categories.length === 0) {
            e.preventDefault();
            alert("Veuillez sélectionner au moins une catégorie");
            return false;
        }

        // Vérifier qu'il reste au moins une image (existantes non supprimées + nouvelles)
        const existingImagesContainer =
            document.getElementById("existingImages");
        const remainingExistingCount = existingImagesContainer
            ? existingImagesContainer.querySelectorAll(
                  ".image-preview-item:not(.to-delete)"
              ).length
            : 0;

        const totalImages = remainingExistingCount + selectedFiles.length;

        if (totalImages === 0) {
            e.preventDefault();
            alert("Vous devez conserver au moins une image pour votre annonce");
            return false;
        }

        console.log(
            "Soumission du formulaire - Images restantes:",
            totalImages
        );
        console.log(
            "Images à supprimer:",
            document.querySelectorAll('input[name="delete_images[]"]').length
        );
    });
}
