// dodanie nowego tankowania
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
            alert("Wystąpił błąd podczas dodawania tankowania.");
        });
    }

// usuniecie tankowania
function deleteFuel(fuelId) {
   selectedFuelId = fuelId;
   event.preventDefault(); // Zapobiega domyślnemu działaniu formularza
  
        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `fuel_id_history=${selectedFuelId}`
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
            alert("Wystąpił błąd podczas usuwania tankowania.");
        });
}

let currentPage = 1;

// otwarcie historii tankowan
function openMenuFuelHistory(carId) {
    selectedCarId = carId;

    const fuelHistoryContent = document.getElementById('fuel-history-content');
    fuelHistoryContent.innerHTML = "<p>Ładowanie danych...</p>"; // Wiadomość oczekiwania
    document.getElementById('menu-fuel-history').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_history=${selectedCarId}&page=${currentPage}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let tableHTML = `
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                          <th>Id samochodu</th>
                            <th>Litry</th>
                            <th>Koszt za litr</th>
                            <th>Rodzaj paliwa</th>
                            <th>Data tankowania</th>
                            <th>Dystans w km</th> 
                          <th>Szczegóły</th>  
                          <th>Spalanie na 100 km</th>
                          <th>Usuń</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(history => {
                tableHTML += `
                    <tr>
                        <td>${history.car_id}</td>
                        <td>${history.liters}</td>  
                        <td>${history.price}</td>
                        <td>${history.fuel_type}</td>
                        <td>${history.refueling_date}</td>
                        <td>${history.distance}</td>
                      <td>${history.details}</td>
                       <td>${history.average_fuel_consumption}</td>
                      <td><button class="delete-button" onclick="deleteFuel('${history.id}')">Usuń</button></td>
                    </tr>
                `;
            });

            tableHTML += `
                      </tbody>
                </table>

               <div class="pagination-container">
    <button onclick="changePage(-1)" class="button-pagination" ${currentPage === 1 ? 'disabled' : ''}><</button>
    <span>Strona ${currentPage}</span>
    <button onclick="changePage(1)" class="button-pagination" ${data.data.length < 5 ? 'disabled' : ''}>></button>
</div>

            `;
          
            fuelHistoryContent.innerHTML = tableHTML; // Wstawienie tabeli do kontenera
        } else {
            fuelHistoryContent.innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        historyContent.innerHTML = "<p>Wystąpił błąd podczas pobierania danych.</p>";
    });
}

function changePage(direction) {
    if (currentPage + direction < 1) return;
    currentPage += direction;
    openMenuFuelHistory(selectedCarId);
}
     
// zamkniecie historii tankowan
function closeMenuFuelHistory() {
    selectedCarId = null;
    document.getElementById('menu-fuel-history').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
  
  currentPage = 1;
}

