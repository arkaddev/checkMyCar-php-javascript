// Funkcja dodająca nową część do bazy danych
    function addNewPart() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const partName = document.getElementById('add-part-name-input').value;
        const partNumber = document.getElementById('add-part-number-input').value;
        const partPrice = document.getElementById('add-part-price-input').value;
        const partDate = document.getElementById('add-part-date-input').value;
        const partMileage = document.getElementById('add-part-mileage-input').value;
        const partNext = document.getElementById('add-part-next-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&partName=${partName}&partNumber=${partNumber}&partPrice=${partPrice}&partDate=${partDate}&partMileage=${partMileage}&partNext=${partNext}`
        })
        .then(response => response.json()) // Parsowanie odpowiedzi jako JSON
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Wyświetlenie komunikatu sukcesu
                location.reload(); // Odświeżenie strony
            } else {
                alert(data.message); // Wyświetlenie komunikatu błędu
            }
        })
        .catch(error => {
            console.error("Wystąpił błąd:", error);
            alert("Wystąpił błąd podczas dodawania części.");
        });
    }