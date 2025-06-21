function openService(carId) {
    selectedCarId = carId;

    const serviceContent = document.getElementById('list-service-content');
    serviceContent.innerHTML = "<p>Ładowanie danych...</p>"; // Wiadomość oczekiwania
    document.getElementById('list-service').style.display = 'block';
  document.getElementById('overlay').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_service=${selectedCarId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let tableHTML = `
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            
                            <th>Id samochodu</th>
                            <th>Nazwa części</th>
                            <th>Numer seryjny części</th>
                            <th>Cena</th>
                            <th>Data wymiany</th>
                            <th>Przebieg</th>
                            <th>Następna wymiana</th>
                            <th>Kiedy wymiana</th>
                            <th>Czy wymieniono?</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(service => {
                // Pomiń wpisy, gdzie next_exchange_km wynosi 0
                if (service.next_exchange_km === "0") return;

                tableHTML += `
                    <tr>
                      
                        <td>${service.car_id}</td>
                        <td>${service.name}</td>
                        <td>${service.number}</td>
                        <td>${service.price}</td>
                        <td>${service.exchange_date}</td>
                        <td>${service.kilometers_status}</td>
                        <td>${service.next_exchange_km}</td>
                        <td>${service.when_exchange}</td>
                        <td>
                            <button class="delete-button" onclick="makeReplaced('${service.id}', '${service.car_id}')">Wymień</button>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            serviceContent.innerHTML = tableHTML; // Wstawienie tabeli do kontenera
        } else {
            serviceContent.innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        serviceContent.innerHTML = "<p>Wystąpił błąd podczas pobierania danych.</p>";
    });
}

function closeListService() {
    selectedCarId = null;
    document.getElementById('list-service').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}
  
     
   
   
   function makeReplaced(partId, carId) {
   selectedPartId = partId;
   selectedCarId = carId;
     
   event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `part_id_service=${selectedPartId}&car_id_service=${selectedCarId}`
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
            alert("Wystąpił błąd podczas aktualizacji przebiegu.");
        });
        
       
    
     
}