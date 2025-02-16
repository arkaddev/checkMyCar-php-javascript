let selectedCarId = null;

    // menu glowne
    function openListMenu(carId) {
        selectedCarId = carId;
        document.getElementById('list-menu').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    function closeListMenu() {
        selectedCarId = null;
        document.getElementById('list-menu').style.display = 'none';
      document.getElementById('overlay').style.display = 'none';
    }

    // aktualizacja przebiegu
    function listMenuEditMileage() {
        document.getElementById('list-edit-mileage').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    function closeListMenuEditMileage() {
        document.getElementById('list-edit-mileage').style.display = 'none';
    }

    // wymiana czesci
    function listMenuPartReplacement() {
        document.getElementById('list-part-replacement').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    function closeListMenuPartReplacement() {
        document.getElementById('list-part-replacement').style.display = 'none';
    }




