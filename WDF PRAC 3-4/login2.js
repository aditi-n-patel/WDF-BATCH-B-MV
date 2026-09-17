document.getElementById("loginForm").addEventListener("submit", function(event) {

    event.preventDefault();

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let registeredEmail = localStorage.getItem("studentEmail");
    let registeredPassword = localStorage.getItem("studentPassword");


    if (registeredEmail == null || registeredPassword == null) {

        alert("Please register first.");

        window.location.href = "register.html";

        return;
    }


    if (email != registeredEmail || password != registeredPassword) {

        alert("Invalid email or password.");

        return;
    }


    localStorage.setItem("isLoggedIn", "true");

    alert("Login successful!");

    window.location.href = "index.html";

});