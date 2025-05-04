document.addEventListener("DOMContentLoaded", function() {
    const dateInput = document.getElementById("date_limite");
    const today = new Date().toISOString().split("T")[0];
    dateInput.setAttribute("min", today);
    const form = document.querySelector("form");
    form.addEventListener("submit", function(e) {
        const selectedDate = new Date(dateInput.value);
        const currentDate = new Date(today);

        if (selectedDate < currentDate) {
            e.preventDefault();
            alert("Veuillez choisir une date d'expiration aujourd'hui ou dans le futur.");
        }
    });
});

