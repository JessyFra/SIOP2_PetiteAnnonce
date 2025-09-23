document.addEventListener("DOMContentLoaded", () => {
    const box = document.getElementById("messageBox");
    if (box) {
        const msg = box.dataset.message;

        // Détection du type de message
        let type = "error";
        if (msg.includes("réussie") || msg.includes("connecter")) {
            type = "success";
        }

        const popup = document.createElement("div");
        popup.className = `popup-message ${type}`;
        popup.textContent = msg;

        document.body.appendChild(popup);

        // Suppression automatique après animation
        setTimeout(() => {
            popup.remove();
        }, 5000);
    }
});
