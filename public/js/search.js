// Recherche en temps réel dans la navbar
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const searchResults = document.getElementById("searchResults");
    let searchTimeout = null;

    if (!searchInput || !searchResults) {
        return;
    }

    // Événement de saisie
    searchInput.addEventListener("input", function () {
        const query = this.value.trim();

        // Effacer le timeout précédent
        clearTimeout(searchTimeout);

        // Si moins de 2 caractères, cacher les résultats
        if (query.length < 2) {
            searchResults.style.display = "none";
            searchResults.innerHTML = "";
            return;
        }

        // Attendre 300ms avant de lancer la recherche
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Cacher les résultats quand on clique ailleurs
    document.addEventListener("click", function (e) {
        if (
            !searchInput.contains(e.target) &&
            !searchResults.contains(e.target)
        ) {
            searchResults.style.display = "none";
        }
    });

    // Afficher les résultats quand on focus le champ
    searchInput.addEventListener("focus", function () {
        if (searchResults.children.length > 0) {
            searchResults.style.display = "block";
        }
    });

    // Navigation au clavier (flèches haut/bas)
    searchInput.addEventListener("keydown", function (e) {
        const items = searchResults.querySelectorAll(".list-group-item");

        if (items.length === 0) return;

        let currentIndex = -1;
        items.forEach((item, index) => {
            if (item.classList.contains("active")) {
                currentIndex = index;
            }
        });

        if (e.key === "ArrowDown") {
            e.preventDefault();
            const nextIndex =
                currentIndex + 1 < items.length ? currentIndex + 1 : 0;
            selectItem(items, nextIndex);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            const prevIndex =
                currentIndex - 1 >= 0 ? currentIndex - 1 : items.length - 1;
            selectItem(items, prevIndex);
        } else if (e.key === "Enter" && currentIndex >= 0) {
            e.preventDefault();
            items[currentIndex].click();
        }
    });

    function selectItem(items, index) {
        items.forEach((item) => item.classList.remove("active"));
        items[index].classList.add("active");
        items[index].scrollIntoView({ block: "nearest" });
    }

    function performSearch(query) {
        // Afficher un loader
        searchResults.innerHTML = `
            <li class="list-group-item text-center">
                <i class="fa-solid fa-spinner fa-spin"></i> Recherche...
            </li>
        `;
        searchResults.style.display = "block";

        // Requête AJAX
        fetch(`index.php?page=search-api&q=${encodeURIComponent(query)}`)
            .then((response) => response.json())
            .then((data) => {
                displayResults(data);
            })
            .catch((error) => {
                console.error("Erreur:", error);
                searchResults.innerHTML = `
                    <li class="list-group-item text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i> Erreur de recherche
                    </li>
                `;
            });
    }

    function displayResults(announces) {
        searchResults.innerHTML = "";

        if (announces.length === 0) {
            searchResults.innerHTML = `
                <li class="list-group-item text-muted">
                    <i class="fa-solid fa-magnifying-glass"></i> Aucun résultat trouvé
                </li>
            `;
            searchResults.style.display = "block";
            return;
        }

        announces.forEach((announce) => {
            const li = document.createElement("li");
            li.className =
                "list-group-item list-group-item-action search-result-item";
            li.style.cursor = "pointer";

            // Badge type
            const typeBadge =
                announce.type === "offer"
                    ? '<span class="badge bg-success me-2">Offre</span>'
                    : '<span class="badge bg-primary me-2">Demande</span>';

            // Prix ou pas
            const priceHtml =
                announce.type === "offer"
                    ? `<span class="text-success fw-bold">${announce.price} €</span>`
                    : '<span class="text-muted">Recherche</span>';

            li.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="mb-1">
                            ${typeBadge}
                            <strong>${highlightQuery(
                                announce.title,
                                searchInput.value
                            )}</strong>
                        </div>
                        <small class="text-muted">
                            <i class="fa-solid fa-location-dot"></i> ${
                                announce.city
                            }
                            ${
                                announce.status === "closed"
                                    ? '<span class="badge bg-secondary ms-2">Clôturée</span>'
                                    : ""
                            }
                        </small>
                    </div>
                    <div class="text-end">
                        ${priceHtml}
                    </div>
                </div>
            `;

            li.addEventListener("click", () => {
                window.location.href = `index.php?page=annonce&id=${announce.id}`;
            });

            searchResults.appendChild(li);
        });

        // Ajouter un lien "Voir tous les résultats"
        if (announces.length >= 5) {
            const li = document.createElement("li");
            li.className =
                "list-group-item list-group-item-action text-center text-primary fw-bold";
            li.style.cursor = "pointer";
            li.innerHTML =
                '<i class="fa-solid fa-arrow-right"></i> Voir tous les résultats';
            li.addEventListener("click", () => {
                window.location.href = `index.php?page=annonces&search=${encodeURIComponent(
                    searchInput.value
                )}`;
            });
            searchResults.appendChild(li);
        }

        searchResults.style.display = "block";
    }

    function highlightQuery(text, query) {
        if (!query) return text;

        const regex = new RegExp(`(${escapeRegex(query)})`, "gi");
        return text.replace(regex, "<mark>$1</mark>");
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    }
});
