window.addEventListener("load", async () => {
    const events = await getEvents();
    displayEvents(events);
})

async function getEvents() {
    const req = await fetch("https://data.kyxar.fr/tests/events.js");

    if (!req.ok) {
        displayError("Impossible de récupérer les événements depuis le serveur.");
    } else {
        return await req.json();
    }
    return [];
}

function displayEvents(events) {
    const firstDayDisplayMonthInput = document.getElementById("firstDayDisplayMonthInput");
    const [year, month, day] = firstDayDisplayMonthInput.value.split("-");

    for (const event of events) {
        const [eventYear, eventMonth, eventDay] = event["event_date"].split("-");
        if (eventYear === year && eventMonth === month) {
            const cellEvent = document.getElementById("day" + eventDay);
            cellEvent.classList.add("event-cell");
            cellEvent.textContent += " - " + event["event_name"];
        }
    }
}

function displayError(message) {
    const messageParagraph = document.getElementById("message");
    messageParagraph.classList.add("error");
    messageParagraph.textContent = message;
}