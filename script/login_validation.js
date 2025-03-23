document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("form");
    form.addEventListener("submit", function(event) {
        const username = document.querySelector('input[name="username"]').value.trim();
        const password = document.querySelector('input[name="password"]').value.trim();
        const errorContainer = document.getElementById("errorMessage");
        let errorMessages = [];

        if (username === "") {
            errorMessages.push("Username is required.");
        }

        if (password === "") {
            errorMessages.push("Password is required.");
        } else if (password.length < 6) {
            // Only check length if password is not empty
            errorMessages.push("Password must be at least 6 characters.");
        }

        if (errorMessages.length > 0) {
            event.preventDefault();
            errorContainer.innerText = errorMessages.join("\n");
        } else {
            errorContainer.innerText = ""; // clear previous errors if valid
        }
    });
});
