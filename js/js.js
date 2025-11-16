document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que recargue la página

    let role = document.getElementById("role").value.toLowerCase().trim();

    if (role === "admin") {
        document.getElementById("adminMenu").style.display = "block";
    } else {
        document.getElementById("adminMenu").style.display = "none";
    }
});