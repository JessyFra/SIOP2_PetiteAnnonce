document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        let element = event.target;

        while (element && !element.classList.contains("announce")) {
            element = element.parentElement;
        }

        if (!element) {
            return;
        }

        let id = element.getAttribute("id");
        console.log(id);

        window.location.href = "index.php?page=annonce&&id=" + id;
    });

});
