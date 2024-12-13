function openHistory(carId) {
    selectedCarId = carId;

    const historyContent = document.getElementById('history-content');
    historyContent.innerHTML = "<p>Ładowanie danych...</p>"; // Wiadomość oczekiwania
    document.getElementById('menu-history').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_history=${selectedCarId}`
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
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(history => {
                tableHTML += `
                    <tr>
                        <td>${history.car_id}</td>
                        <td>${history.name}</td>
                        <td>${history.number}</td>
                        <td>${history.price}</td>
                        <td>${history.exchange_date}</td>
                        <td>${history.kilometers_status}</td>
                        <td>${history.next_exchange_km}</td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            historyContent.innerHTML = tableHTML; // Wstawienie tabeli do kontenera
        } else {
            historyContent.innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        historyContent.innerHTML = "<p>Wystąpił błąd podczas pobierania danych.</p>";
    });
}


function closeMenuHistory() {
    selectedCarId = null;
    document.getElementById('menu-history').style.display = 'none';
}