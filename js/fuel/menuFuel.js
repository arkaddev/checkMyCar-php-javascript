// Funkcja otwierająca okno z tankowaniem
    function menuAddFuel(carId) {
  selectedCarId = carId;
        document.getElementById('add-fuel').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z tankowaniem
    function closeMenuAddFuel() {
  selectedCarId = null;
        document.getElementById('add-fuel').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
    }