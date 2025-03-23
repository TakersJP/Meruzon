document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    form.addEventListener("submit", function (event) {
        let hasErrors = false;

        function setError(input, message) {
            input.value = message;
            input.style.color = "red";
            hasErrors = true;
            input.addEventListener("focus", function () {
                input.value = "";
                input.style.color = "black";
            }, { once: true });
        }

        const fields = [
            { field: form.firstName, message: "First name required." },
            { field: form.lastName, message: "Last name required." },
            { field: form.email, message: "Valid email required.", customCheck: val => val.includes("@") },
            { field: form.phonenum, message: "Numbers only.", customCheck: val => /^\d+$/.test(val) },
            { field: form.address, message: "Address is required." },
            { field: form.city, message: "City is required." },
            { field: form.state, message: "State is required." },
            { field: form.postalCode, message: "Postal Code is required."},
            { field: form.country, message: "Country is required." },
            { field: form.username, message: "Username is required."},
            { field: form.password, message: "Password is required (At least 6 characters).", customCheck: val => val.length >= 6 }
        ];
        

        fields.forEach(({ field, message, customCheck }) => {
            if (field.value.trim() === "" || (customCheck && !customCheck(field.value.trim()))) {
                event.preventDefault();
                setError(field, message);
            }
        });
    });
});
