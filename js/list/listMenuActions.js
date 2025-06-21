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

   // nowe ubezpieczenie
    function listMenuEditInsurance() {
        document.getElementById('list-edit-insurance').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

   
    function closeListMenuEditInsurance() {
        document.getElementById('list-edit-insurance').style.display = 'none';
    }

    // nowy przeglad
    function listMenuEditInspection() {
        document.getElementById('list-edit-inspection').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    
    function closeListMenuEditInspection() {
        document.getElementById('list-edit-inspection').style.display = 'none';
    }

    // nowe tankowanie
    function listMenuNewFuel() {
        document.getElementById('list-new-fuel').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    function closeListMenuNewFuel() {
        document.getElementById('list-new-fuel').style.display = 'none';
    }