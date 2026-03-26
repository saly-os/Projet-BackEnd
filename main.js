document.addEventListener("DOMContentLoaded", function () {
    const navToggle = document.getElementById("navToggle");
    const navMenu = document.getElementById("navMenu");

    if (navToggle && navMenu) {
        navToggle.addEventListener("click", function () {
            navMenu.classList.toggle("is-open");
        });
    }

    const filterForm = document.getElementById("filterForm");
    const categorySelect = document.getElementById("categorie");

    if (filterForm && categorySelect) {
        categorySelect.addEventListener("change", function () {
            filterForm.submit();
        });
    }

    const togglePassword = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("mot_de_passe");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const hidden = passwordInput.type === "password";
            passwordInput.type = hidden ? "text" : "password";
            togglePassword.textContent = hidden ? "Masquer" : "Afficher";
        });
    }

    const forms = document.querySelectorAll("form[data-validate='true']");

    forms.forEach(function (form) {
        const fields = form.querySelectorAll("[data-required='true']");

        fields.forEach(function (field) {
            field.addEventListener("input", function () {
                validateField(field);
            });

            field.addEventListener("blur", function () {
                validateField(field);
            });
        });

        form.addEventListener("submit", function (event) {
            let isValid = true;

            fields.forEach(function (field) {
                if (!validateField(field)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        const label = field.dataset.label || "ce champ";
        const errorId = field.dataset.error;
        const errorElement = errorId ? document.getElementById(errorId) : null;
        let message = "";

        if (!value) {
            message = "Le champ " + label + " est obligatoire.";
        }

        if (message) {
            field.classList.add("input-error");
            if (errorElement) {
                errorElement.textContent = message;
            }
            return false;
        }

        field.classList.remove("input-error");
        if (errorElement) {
            errorElement.textContent = "";
        }
        return true;
    }
});
