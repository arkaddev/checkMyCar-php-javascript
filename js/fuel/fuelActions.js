function addNewFuel() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const fuelLiters = document.getElementById('add-fuel-liters-input').value;
        const fuelType = document.getElementById('add-fuel-type-input').value;
        const fuelPrice = document.getElementById('add-fuel-price-input').value;
        const fuelDate = document.getElementById('add-fuel-date-input').value;
        const fuelDistance = document.getElementById('add-fuel-distance-input').value;
  const fuelDetails = document.getElementById('add-fuel-details-input').value;
       
        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&fuelLiters=${fuelLiters}&fuelType=${fuelType}&fuelPrice=${fuelPrice}&fuelDate=${fuelDate}&fuelDistance=${fuelDistance}&fuelDetails=${fuelDetails}`
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