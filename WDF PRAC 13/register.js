document.getElementById("registerForm").addEventListener("submit", function(event) {

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let mobile = document.getElementById("mobile").value;
    let institute = document.getElementById("institute").value;
    let year = document.getElementById("year").value;
    let course = document.getElementById("course").value;
    let rollNo = document.getElementById("rollNo").value;
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirmPassword").value;

    if (name == "" || email == "" || mobile == "" ||
        institute == "" || year == "" || course == "" ||
        rollNo == "" || password == "" || confirmPassword == "") {

        event.preventDefault();
        alert("Please fill all the fields.");
        return;
    }

    if (!/^[0-9]{10}$/.test(mobile)) {
        event.preventDefault();
        alert("Please enter a valid 10-digit mobile number.");
        return;
    }

    if (password.length < 8) {
        event.preventDefault();
        alert("Password must contain at least 8 characters.");
        return;
    }

    if (!/[A-Z]/.test(password)) {
        event.preventDefault();
        alert("Password must contain one uppercase letter.");
        return;
    }

    if (!/[a-z]/.test(password)) {
        event.preventDefault();
        alert("Password must contain one lowercase letter.");
        return;
    }

    if (!/[0-9]/.test(password)) {
        event.preventDefault();
        alert("Password must contain one number.");
        return;
    }

    if (password != confirmPassword) {
        event.preventDefault();
        alert("Passwords do not match.");
        return;
    }

});