 function newPassword() {
    var newPassword = document.getElementById('new_password').value;

    if (!newPassword) {
        alert("Proszę wprowadzić nowe hasło");
        return;
    }

    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `new_password=${encodeURIComponent(newPassword)}`
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
    alert("Hasło zostało zmienione.");
}
  
  function deleteCar(carId) {
   
  alert(carId);
    event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${carId}`
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
            alert("Wystąpił błąd podczas usuwania pojazdu.");
        });
}
  
  
  
  
  
  
  // Funkcja otwierająca okno z dodwaniem samochodu
    function menuAddCar() {
        document.getElementById('add-car').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z dodawaniem samochodu
    function closeMenuAddCar() {
  
        document.getElementById('add-car').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
    }
  
  
  
  
  // Funkcja dodająca nowy pojazd do bazy danych
    
  function addCar(userId) {
 alert(userId);
        
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const carModel = document.getElementById('add-car-model-input').value;
        const carYear = document.getElementById('add-car-year-input').value;
        const carEngine = document.getElementById('add-car-engine-input').value;
        const carKw = document.getElementById('add-car-kw-input').value;
        const carOil = document.getElementById('add-car-oil-input').value;
        const carOilFilter = document.getElementById('add-car-oilfilter-input').value;
        const carAirFilter = document.getElementById('add-car-airfilter-input').value;
        const carOtherInfo = document.getElementById('add-car-otherinfo-input').value;
  
        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `user_id=${userId}&carModel=${carModel}&carYear=${carYear}&carEngine=${carEngine}&carKw=${carKw}&carOil=${carOil}&carOilFilter=${carOilFilter}&carAirFilter=${carAirFilter}&carOtherInfo=${carOtherInfo}`
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
            alert("Wystąpił błąd podczas dodawania pojazdu.");
        });
    }
  