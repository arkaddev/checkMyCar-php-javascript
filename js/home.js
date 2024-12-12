    let selectedCarId = null;

    // Funkcja otwierająca główne menu dodawania
    function openMenuAdd(carId) {
        selectedCarId = carId;
        document.getElementById('menu-add').style.display = 'block';
    }

    // Funkcja zamykająca główne menu dodawania
    function closeMenuAdd() {
        selectedCarId = null;
        document.getElementById('menu-add').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przebiegu
    function menuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przebiegu
    function closeMenuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją ubezpieczenia
    function menuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją ubezpieczenia
    function closeMenuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przeglądu
    function menuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przeglądu
    function closeMenuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'none';
    }

    // Funkcja otwierająca okno z wymianą części
    function menuNewPart() {
        document.getElementById('new-part').style.display = 'block';
    }

    // Funkcja zamykająca okno z wymianą części
    function closeMenuNewPart() {
        document.getElementById('new-part').style.display = 'none';
    }

   // Funkcja otwierająca okno z tankowaniem
    function menuNewFuel() {
        document.getElementById('new-fuel').style.display = 'block';
    }

    // Funkcja zamykająca okno z tankowaniem
    function closeMenuNewFuel() {
        document.getElementById('new-fuel').style.display = 'none';
    }
   
    // Funkcja aktualizująca przebieg w bazie danych
    function editMileage() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const mileage = document.getElementById('mileage-input').value;

        if (!selectedCarId) {
            alert("Nie wybrano samochodu.");
            return;
        }

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&mileage=${mileage}`
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

    // Funkcja aktualizująca ubezpieczenie w bazie danych
    function editInsurance() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const insurance = document.getElementById('insurance-input').value;

        if (!selectedCarId) {
            alert("Nie wybrano samochodu.");
            return;
        }

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&insurance=${insurance}`
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
            alert("Wystąpił błąd podczas aktualizacji ubezpieczenia.");
        });
    }

    // Funkcja aktualizująca przegląd w bazie danych
    function editInspection() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const inspection = document.getElementById('inspection-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&inspection=${inspection}`
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
            alert("Wystąpił błąd podczas aktualizacji przeglądu.");
        });
    }

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
   
   
   // Funkcja dodająca nową część do bazy danych
    function addNewFuel() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const fuelLiters = document.getElementById('add-fuel-liters-input').value;
        const fuelType = document.getElementById('add-fuel-type-input').value;
        const fuelPrice = document.getElementById('add-fuel-price-input').value;
        const fuelDate = document.getElementById('add-fuel-date-input').value;
        const fuelDistance = document.getElementById('add-fuel-distance-input').value;
       
        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&fuelLiters=${fuelLiters}&fuelType=${fuelType}&fuelPrice=${fuelPrice}&fuelDate=${fuelDate}&fuelDistance=${fuelDistance}`
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
   
   
     
   
   
   
   
   
   
   
   function openInfo(carId) {
    selectedCarId = carId;
    document.getElementById('info-content').innerHTML = "Ładowanie danych..."; // Wiadomość oczekiwania
    document.getElementById('menu-info').style.display = 'block';

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
            const infoContent = document.getElementById('info-content');
            infoContent.innerHTML = ""; // Wyczyszczenie zawartości
            data.data.forEach(info => {
                const infoDiv = document.createElement('div');
                infoDiv.innerHTML = `
                    <p><strong>Id samochodu:</strong> ${info.car_id}</p>
                    <p><strong>Silnik:</strong> ${info.engine_number}</p>
                    <p><strong>Data produkcji:</strong> ${info.production_date}</p>
                    <p><strong>Moc km/kw:</strong> ${info.km_kw}</p>
                    <p><strong>Olej:</strong> ${info.oil_number}</p>
                    <p><strong>Filtr oleju:</strong> ${info.oil_filter_number}</p>
                    <p><strong>Filtr powietrza:</strong> ${info.air_filter_number}</p>
                `;
                infoContent.appendChild(infoDiv);
            });
        } else {
            document.getElementById('info-content').innerHTML = data.message;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        document.getElementById('info-content').innerHTML = "Wystąpił błąd podczas pobierania danych.";
    });
}

function closeMenuInfo() {
    selectedCarId = null;
    document.getElementById('menu-info').style.display = 'none';
}
  
   
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
  
   
 function openService(carId) {
    selectedCarId = carId;

    const serviceContent = document.getElementById('service-content');
    serviceContent.innerHTML = "<p>Ładowanie danych...</p>"; // Wiadomość oczekiwania
    document.getElementById('menu-service').style.display = 'block';

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
                            <button onclick="makeReplaced('${service.id}')">Oznacz jako wymienioną</button>
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
function closeMenuService() {
    selectedCarId = null;
    document.getElementById('menu-service').style.display = 'none';
}
  
  
   
   
   
   
   
   function makeReplaced(partId) {
   selectedPartId = partId;
   event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `part_id_service=${selectedPartId}`
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