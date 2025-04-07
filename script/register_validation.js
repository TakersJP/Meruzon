document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    form.addEventListener("submit", function (event) {
        let hasErrors = false;

        function setError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorSpan = document.getElementById(`error-${fieldId}`);
            errorSpan.textContent = message;
            field.classList.add("input-error");
            hasErrors = true;
        }

        function clearError(fieldId) {
            const field = document.getElementById(fieldId);
            const errorSpan = document.getElementById(`error-${fieldId}`);
            errorSpan.textContent = "";
            field.classList.remove("input-error");
        }

        const validations = [
            { id: "firstName", message: "First name required." },
            { id: "lastName", message: "Last name required." },
            { id: "email", message: "Valid email required.", customCheck: val => val.includes("@") },
            { id: "phonenum", message: "Numbers only.", customCheck: val => /^\d+$/.test(val) },
            { id: "address", message: "Address is required." },
            { id: "city", message: "City is required." },
            { id: "state", message: "State is required." },
            { id: "postalCode", message: "Postal Code is required." },
            { id: "country", message: "Country is required." },
            { id: "username", message: "Username is required." },
            { id: "password", message: "Password is required (At least 6 characters).", customCheck: val => val.length >= 6 }
        ];

        validations.forEach(({ id, message, customCheck }) => {
            const value = document.getElementById(id).value.trim();
            clearError(id);
            if (value === "" || (customCheck && !customCheck(value))) {
                event.preventDefault();
                setError(id, message);
            }
        });
    });
});
