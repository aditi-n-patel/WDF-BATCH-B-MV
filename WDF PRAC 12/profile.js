let name = localStorage.getItem("studentName");
let rollNo = localStorage.getItem("studentRollNo");
let institute = localStorage.getItem("studentInstitute");
let year = localStorage.getItem("studentYear");
let course = localStorage.getItem("studentCourse");
let email = localStorage.getItem("studentEmail");
let mobile = localStorage.getItem("studentMobile");


if (name != null) {
    document.getElementById("studentName").innerText = name;
}

if (rollNo != null) {
    document.getElementById("studentRollNo").innerText = rollNo;
}

if (institute != null) {
    document.getElementById("studentInstitute").innerText = institute;
}

if (year != null) {
    document.getElementById("studentYear").innerText = year;
}

if (course != null) {
    document.getElementById("studentCourse").innerText = course;
}

if (email != null) {
    document.getElementById("studentEmail").innerText = email;
}

if (mobile != null) {
    document.getElementById("studentMobile").innerText = mobile;
}