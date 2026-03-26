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

    const loginForm = document.getElementById("loginForm");
    const loginInput = document.getElementById("login");
    const passwordInput = document.getElementById("mot_de_passe");
    const loginError = document.getElementById("loginError");
    const passwordError = document.getElementById("passwordError");
    const togglePassword = document.getElementById("togglePassword");

    function setError(input, errorElement, message) {
        if (!input || !errorElement) return;
        input.classList.add("input-error");
        errorElement.textContent = message;
    }

    function clearError(input, errorElement) {
        if (!input || !errorElement) return;
        input.classList.remove("input-error");
        errorElement.textContent = "";
    }

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const hidden = passwordInput.type === "password";
            passwordInput.type = hidden ? "text" : "password";
            togglePassword.textContent = hidden ? "Masquer" : "Afficher";
        });
    }

    if (loginForm && loginInput && passwordInput) {
        loginForm.addEventListener("submit", function (event) {
            let isValid = true;

            clearError(loginInput, loginError);
            clearError(passwordInput, passwordError);

            if (!loginInput.value.trim()) {
                setError(loginInput, loginError, "Le login est obligatoire.");
                isValid = false;
            }

            if (!passwordInput.value.trim()) {
                setError(passwordInput, passwordError, "Le mot de passe est obligatoire.");
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    }
});
