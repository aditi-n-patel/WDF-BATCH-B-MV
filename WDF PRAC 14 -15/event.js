
let events = [];
let currentPage = 1;
let eventsPerPage = 4;

const eventContainer = document.getElementById("event-container");
const searchEvent = document.getElementById("searchEvent");
const categoryFilter = document.getElementById("categoryFilter");
const sortEvent = document.getElementById("sortEvent");
const pagination = document.getElementById("pagination");

fetch("events_api.php")
    .then(response => {
        if (!response.ok) {
            throw new Error("Unable to load events.");
        }
        return response.json();
    })
    .then(data => {
        if (!Array.isArray(data)) {
            throw new Error("Invalid event data.");
        }

        events = data;
        displayEvent();
    })
    .catch(error => {
        eventContainer.innerHTML =
            "<p>Unable to load events. Please try again.</p>";
        console.error(error);
    });

function displayEvent() {
    let filteredEvents = [...events];

    let searchText = searchEvent.value.toLowerCase().trim();
    let category = categoryFilter.value;
    let sortType = sortEvent.value;

    if (searchText !== "") {
        filteredEvents = filteredEvents.filter(event =>
            event.title.toLowerCase().includes(searchText)
        );
    }

    if (category !== "All") {
        filteredEvents = filteredEvents.filter(event =>
            event.category === category
        );
    }

    if (sortType === "Date") {
        filteredEvents.sort((a, b) =>
            new Date(a.event_date) - new Date(b.event_date)
        );
    }

    if (sortType === "Title") {
        filteredEvents.sort((a, b) =>
            a.title.localeCompare(b.title)
        );
    }

    let totalPages = Math.ceil(
        filteredEvents.length / eventsPerPage
    );

    if (totalPages === 0) {
        currentPage = 1;
    } else if (currentPage > totalPages) {
        currentPage = totalPages;
    }

    let start = (currentPage - 1) * eventsPerPage;
    let end = start + eventsPerPage;

    let pageEvents = filteredEvents.slice(start, end);

    eventContainer.innerHTML = "";

    if (pageEvents.length === 0) {
        eventContainer.innerHTML = "<p>No events found.</p>";
    }

    pageEvents.forEach(event => {
        let card = document.createElement("div");
        card.className = "event-card";

        let title = document.createElement("h3");
        title.textContent = event.title;

        let categoryText = document.createElement("p");
        categoryText.textContent = "Category: " + event.category;

        let dateText = document.createElement("p");
        dateText.textContent = "Date: " + event.event_date;

        let descriptionText = document.createElement("p");
        descriptionText.textContent = event.description;

        let venueText = document.createElement("p");
        venueText.textContent = "Venue: " + event.venue;

        let registerButton = document.createElement("button");
        registerButton.textContent = "Register";

        registerButton.addEventListener("click", function() {
            registerForEvent(event.id, registerButton);
        });

        card.appendChild(title);
        card.appendChild(categoryText);
        card.appendChild(dateText);
        card.appendChild(descriptionText);
        card.appendChild(venueText);
        card.appendChild(registerButton);

        eventContainer.appendChild(card);
    });

    createPagination(totalPages);
}

searchEvent.addEventListener("input", function() {
    currentPage = 1;
    displayEvent();
});

searchEvent.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        currentPage = 1;
        displayEvent();
    }
});

categoryFilter.addEventListener("change", function() {
    currentPage = 1;
    displayEvent();
});

sortEvent.addEventListener("change", function() {
    currentPage = 1;
    displayEvent();
});

function createPagination(totalPages) {
    pagination.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
        let button = document.createElement("button");
        button.textContent = i;

        if (i === currentPage) {
            button.disabled = true;
        }

        button.addEventListener("click", function() {
            currentPage = i;
            displayEvent();
        });

        pagination.appendChild(button);
    }
}

function registerForEvent(eventId, registerButton) {
    let confirmRegistration = confirm(
        "Do you want to register for this event?"
    );

    if (!confirmRegistration) {
        return;
    }

    registerButton.disabled = true;
    registerButton.textContent = "Registering...";

    let formData = new FormData();
    formData.append("event_id", eventId);

    fetch("register_event.php", {
        method: "POST",
        body: formData
    })
    .then(async response => {
        let data = await response.json();

        if (!response.ok && response.status !== 401) {
            throw new Error(
                data.message || "Registration failed."
            );
        }

        return data;
    })
    .then(data => {
        alert(data.message);

        if (data.success) {
            registerButton.textContent = "Registered";
        } else {
            registerButton.disabled = false;
            registerButton.textContent = "Register";
        }
    })
    .catch(error => {
        alert(error.message || "Something went wrong. Please try again.");
        registerButton.disabled = false;
        registerButton.textContent = "Register";
        console.error(error);
    });
}
