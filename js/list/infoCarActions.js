// szczegolowe inforacje samochodu
function openInfoCar(carId) {
    selectedCarId = carId;
    document.getElementById('list-info-car-content').innerHTML = "Ładowanie danych..."; // Wiadomość oczekiwania
    document.getElementById('list-info-car').style.display = 'block';
   document.getElementById('overlay').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_info=${selectedCarId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const infoContent = document.getElementById('list-info-car-content');
            infoContent.innerHTML = ""; // Wyczyszczenie zawartości
            data.data.forEach(info => {
                const infoDiv = document.createElement('div');
                infoDiv.innerHTML = `
                    <p><strong>Id samochodu:</strong> ${info.car_id}</p>
                    <p><strong>Silnik:</strong> ${info.engine_number}</p>
                    <p><strong>Moc km/kw:</strong> ${info.km_kw}</p>
                    <p><strong>Olej:</strong> ${info.oil_number}</p>
                    <p><strong>Filtr oleju:</strong> ${info.oil_filter_number}</p>
                    <p><strong>Filtr powietrza:</strong> ${info.air_filter_number}</p>
                    <p><strong>Opony:</strong> ${info.tires}</p>
                    <p><strong>Inne informacje:</strong> ${info.other_info}</p>
                `;
                infoContent.appendChild(infoDiv);
            });
        } else {
            document.getElementById('list-info-car-content').innerHTML = data.message;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        document.getElementById('info-content').innerHTML = "Wystąpił błąd podczas pobierania danych.";
    });
}

function closeListInfoCar() {
    selectedCarId = null;
    document.getElementById('list-info-car').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}


// historia przebiegu

 function openInfoMileage(carId) {
    selectedCarId = carId;
    document.getElementById('list-info-mileage-content').innerHTML = "Ładowanie danych..."; // Wiadomość oczekiwania
    document.getElementById('list-info-mileage').style.display = 'block';
   document.getElementById('overlay').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_info_mileage=${selectedCarId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const infoContent = document.getElementById('list-info-mileage-content');
            infoContent.innerHTML = ""; // Wyczyszczenie zawartości
            data.data.forEach(info => {
                const infoDiv = document.createElement('div');
                infoDiv.innerHTML = `
                    <strong>Data:</strong> ${info.date}
                    <strong>Przebieg:</strong> ${info.mileage}
               
                `;
                infoContent.appendChild(infoDiv);
            });
        } else {
            document.getElementById('list-info-mileage-content').innerHTML = data.message;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        document.getElementById('info-content').innerHTML = "Wystąpił błąd podczas pobierania danych.";
    });
}

function closeListInfoMileage() {
    selectedCarId = null;
    document.getElementById('list-info-mileage').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}