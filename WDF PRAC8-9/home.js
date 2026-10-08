

let titles = [
    "Your Digital Student Information Portal",
    "Manage Your Academic Information",
    "Stay Updated With Student Events",
    "Join StudentHub"
];

let texts = [
    "Access your academic information easily and securely.",
    "View your profile, timetable, fees and results in one place.",
    "Explore upcoming college events, activities and important programs.",
    "Create your student account and access your information easily."
];

let slideIndex = 0;

function showSlide() {

    document.getElementById("slideTitle").innerText = titles[slideIndex];

    document.getElementById("slideText").innerText = texts[slideIndex];

}

function nextSlide() {

    slideIndex++;

    if (slideIndex >= titles.length) {
        slideIndex = 0;
    }

    showSlide();

}

function previousSlide() {

    slideIndex--;

    if (slideIndex < 0) {
        slideIndex = titles.length - 1;
    }

    showSlide();

}




function changeTheme() {

    document.body.classList.toggle("dark-theme");

    let button = document.getElementById("themeButton");

    if (document.body.classList.contains("dark-theme")) {

        button.innerText = "Light Mode";

        localStorage.setItem("theme", "dark");

    } else {

        button.innerText = "Dark Mode";

        localStorage.setItem("theme", "light");

    }

}



let savedTheme = localStorage.getItem("theme");

if (savedTheme == "dark") {

    document.body.classList.add("dark-theme");

    document.getElementById("themeButton").innerText = "Light Mode";

}