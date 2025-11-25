let modified = false;

document.addEventListener("DOMContentLoaded", function() {
    window.addEventListener("load", () => {
        const bioDiv = document.getElementById("bio");
        bioDiv.innerHTML = bioDiv.dataset.bio;
    });
});

function toggleEdit(field) {
    const display = document.getElementById(field + "-display");
    const input = document.getElementById(field + "-input");

    if (display && input) {
        display.hidden = true;
        input.hidden = false;
        showSaveCancel();
    }
}

function togglePassword() {
    const display = document.getElementById("password-display");
    const input = document.getElementById("password-input");

    display.hidden = true;
    input.hidden = false;
    showSaveCancel();
}

function showSaveCancel() {
    if (!modified) {
        document.getElementById("save-btn").hidden = false;
        document.getElementById("cancel-btn").hidden = false;
        modified = true;
    }
}

function cancelEdits() {
    // Réinitialiser tous les champs en mode lecture seule
    const fields = ["name", "display_name", "biography"];
    fields.forEach((field) => {
        const display = document.getElementById(field + "-display");
        const input = document.getElementById(field + "-input");
        if (display && input) {
            display.hidden = false;
            input.hidden = true;
        }
    });

    // Réinitialiser mot de passe
    document.getElementById("password-display").hidden = false;
    document.getElementById("password-input").hidden = true;

    // Cacher Enregistrer et Annuler
    document.getElementById("save-btn").hidden = true;
    document.getElementById("cancel-btn").hidden = true;
    modified = false;
}
