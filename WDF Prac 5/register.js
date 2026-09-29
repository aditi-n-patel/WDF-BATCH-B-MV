document.getElementById("registerForm").addEventListener("submit", function(event) {

    event.preventDefault();

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

        alert("Please fill all the fields.");
        return;
    }

    if (!/^[A-Za-z][A-Za-z ]*$/.test(name)) {
        alert("Name should contain only letters and spaces and must start with a letter.");
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert("Please enter a valid email address.");
        return;
    }

    if (!/^[0-9]{10}$/.test(mobile)) {
        alert("Please enter a valid 10-digit mobile number.");
        return;
    }

    if (!/^[A-Za-z][A-Za-z ]*$/.test(institute)) {
        alert("Institute name should contain only letters and spaces.");
        return;
    }

    if (!/^[A-Za-z][A-Za-z ]*$/.test(course)) {
        alert("Course should contain only letters and spaces.");
        return;
    }

    if (!/^[A-Za-z0-9]+$/.test(rollNo)) {
        alert("Roll number should contain only letters and numbers.");
        return;
    }

    if (password.length < 8) {
        alert("Password must contain at least 8 characters.");
        return;
    }

    if (!/[A-Z]/.test(password)) {
        alert("Password must contain one uppercase letter.");
        return;
    }

    if (!/[a-z]/.test(password)) {
        alert("Password must contain one lowercase letter.");
        return;
    }

    if (!/[0-9]/.test(password)) {
        alert("Password must contain one number.");
        return;
    }

    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return;
    }

    localStorage.setItem("studentName", name);
    localStorage.setItem("studentEmail", email);
    localStorage.setItem("studentMobile", mobile);
    localStorage.setItem("studentInstitute", institute);
    localStorage.setItem("studentYear", year);
    localStorage.setItem("studentCourse", course);
    localStorage.setItem("studentRollNo", rollNo);
    localStorage.setItem("studentPassword", password);

    alert("Registration successful!");

    window.location.href = "student.html";
});