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

// Gestion de l'upload d'images
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
            // Ajouter les fichiers droppés aux fichiers existants
            const allFiles = [...selectedFiles, ...files];
            imageInput.files = createFileList(allFiles);
            handleFiles();
        }
    });
}

function handleFiles() {
    const files = Array.from(imageInput.files);

    // Ajouter les nouveaux fichiers à la liste existante au lieu de la remplacer
    const newFiles = files.filter((file) => {
        // Vérifier que le fichier n'est pas déjà dans la liste
        return !selectedFiles.some(
            (existing) =>
                existing.name === file.name && existing.size === file.size
        );
    });

    const totalFiles = selectedFiles.length + newFiles.length;

    if (totalFiles > 5) {
        alert(
            `Vous ne pouvez télécharger que 5 images maximum. Vous avez déjà ${selectedFiles.length} image(s) sélectionnée(s).`
        );
        imageInput.value = ""; // Réinitialiser l'input
        return;
    }

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
            div.className =
                "image-preview-item" + (index === 0 ? " main-image" : "");
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                ${
                    index === 0
                        ? '<span class="main-badge"><i class="fa-solid fa-star"></i> Principale</span>'
                        : ""
                }
                <div class="image-actions">
                    <button type="button" class="image-action-btn delete" data-index="${index}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            `;
            imagePreview.appendChild(div);

            // Ajouter l'événement de suppression après avoir ajouté l'élément au DOM
            const deleteBtn = div.querySelector(".delete");
            deleteBtn.addEventListener("click", function () {
                const idx = parseInt(this.getAttribute("data-index"));
                removeImage(idx);
            });
        };
        reader.readAsDataURL(file);
    });

    // Afficher le nombre d'images
    if (selectedFiles.length > 0) {
        const subtextElement = uploadZone.querySelector(".upload-subtext");
        if (subtextElement) {
            subtextElement.textContent = `${selectedFiles.length}/5 image(s) sélectionnée(s) - PNG, JPG jusqu'à 5MB`;
        }
    }
}

function removeImage(index) {
    // Supprimer le fichier de la liste
    selectedFiles.splice(index, 1);

    // Recréer la FileList
    imageInput.files = createFileList(selectedFiles);

    // Réafficher les prévisualisations
    displayPreviews();

    // Réinitialiser le texte si plus d'images
    if (selectedFiles.length === 0) {
        const subtextElement = uploadZone.querySelector(".upload-subtext");
        if (subtextElement) {
            subtextElement.textContent =
                "PNG, JPG jusqu'à 5MB - Maximum 5 images";
        }
    }
}

function createFileList(files) {
    const dataTransfer = new DataTransfer();
    files.forEach((file) => dataTransfer.items.add(file));
    return dataTransfer.files;
}

// Validation du formulaire
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

        // Vérifier qu'il y a au moins une image
        if (selectedFiles.length === 0) {
            e.preventDefault();
            alert("Veuillez ajouter au moins une image");
            return false;
        }
    });
}
