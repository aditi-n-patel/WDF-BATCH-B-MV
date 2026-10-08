let events = [];

let currentPage = 1;

let eventsPerPage = 4;

const eventContainer = document.getElementById("event-container");

const searchEvent = document.getElementById("searchEvent");

const categoryFilter = document.getElementById("categoryFilter");

const sortEvent = document.getElementById("sortEvent");

const pagination = document.getElementById("pagination");


fetch("event.json")
    .then(response => response.json())
    .then(data => {
        events = data;
        displayEvent();
    });


function displayEvent() {

    let filteredEvents = [...events];

    let searchText = searchEvent.value.toLowerCase();

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
            new Date(a.date) - new Date(b.date)
        );

    }


    if (sortType === "Title") {

        filteredEvents.sort((a, b) =>
            a.title.localeCompare(b.title)
        );

    }


    let totalPages = Math.ceil(filteredEvents.length / eventsPerPage);


    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }


    let start = (currentPage - 1) * eventsPerPage;

    let end = start + eventsPerPage;

    let pageEvents = filteredEvents.slice(start, end);


    eventContainer.innerHTML = "";


    pageEvents.forEach(event => {

        let card = document.createElement("div");

        card.className = "event-card";

        card.innerHTML = `
            <h3>${event.title}</h3>
            <p><b>Category:</b> ${event.category}</p>
            <p><b>Date:</b> ${event.date}</p>
            <p>${event.description}</p>
        `;

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

        button.innerText = i;


        button.addEventListener("click", function() {

            currentPage = i;

            displayEvent();

        });


        pagination.appendChild(button);

    }

}