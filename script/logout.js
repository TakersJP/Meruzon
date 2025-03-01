document.addEventListener("DOMContentLoaded", function() {
    let loggedInUser = localStorage.getItem("loggedInUser");

    if (loggedInUser) {
        document.getElementById("userLoginSection").innerHTML = `
            <span style="display: flex; align-items: center;">
                Signed in as: <b>${loggedInUser}</b> |
                <a href="#" id="logoutButton">Logout</a>
            </span>
        `;
        document.getElementById("logoutButton").addEventListener("click", function() {
            localStorage.removeItem("loggedInUser"); 
            window.location.href = "shop.html"; 
        });
    }
});
