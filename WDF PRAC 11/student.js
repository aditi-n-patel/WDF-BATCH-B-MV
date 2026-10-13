let name = localStorage.getItem("studentName");
let email = localStorage.getItem("studentEmail");

if (name != null) {
    document.getElementById("welcome").innerText = "Welcome, " + name + "!";
}

if (email != null) {
    document.getElementById("studentEmail").innerText = "Email: " + email;
}