let selectedCarId = null;

    // Funkcja otwierająca główne menu dodawania
    function openMenuAdd(carId) {
        selectedCarId = carId;
        document.getElementById('menu-add').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca główne menu dodawania
    function closeMenuAdd() {
        selectedCarId = null;
        document.getElementById('menu-add').style.display = 'none';
      document.getElementById('overlay').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przebiegu
    function menuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przebiegu
    function closeMenuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją ubezpieczenia
    function menuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją ubezpieczenia
    function closeMenuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przeglądu
    function menuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przeglądu
    function closeMenuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'none';
    }

    // Funkcja otwierająca okno z wymianą części
    function menuNewPart() {
        document.getElementById('new-part').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z wymianą części
    function closeMenuNewPart() {
        document.getElementById('new-part').style.display = 'none';
    }

   // Funkcja otwierająca okno z tankowaniem
    function menuNewFuel() {
        document.getElementById('new-fuel').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z tankowaniem
    function closeMenuNewFuel() {
        document.getElementById('new-fuel').style.display = 'none';
    }