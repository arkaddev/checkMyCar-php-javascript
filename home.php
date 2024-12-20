<?php
require 'config/session.php';
require 'config/db_connection.php';
require 'requests/update_car.php';
require 'requests/add_part.php';
require 'requests/add_fuel.php';
require 'requests/car_info.php';
require 'views/car_table.php';
require 'helpers/functions.php';





?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
  <script src="js/menuActions.js"></script>
  <script src="js/carDataActions.js"></script>
  <script src="js/partActions.js"></script>
  <script src="js/fuelActions.js"></script>
  <script src="js/infoActions.js"></script>
  <script src="js/historyActions.js"></script>
  <script src="js/serviceActions.js"></script>
 
    <style>
        /* glowne menu dodawania, okno z edycja przebiegu */
        #menu-add, #edit-mileage, #edit-insurance, #edit-inspection, #new-part, #new-fuel, #menu-info {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            width: 300px;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
      
      
      #menu-history, #menu-service {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            width: 1000px;
            background: white;
            padding: 20px;
            aborder: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
      
        #menu-add button, #edit-mileage button, #edit-insurance button, #edit-inspection button, #new-part button, #new-fuel button, #menu-info button {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    
        #edit-mileage input[type="number"], #edit-insurance input[type="date"], #edit-inspection input[type="date"], #add-part-input input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
      
      
      #overlay {
    display: none; /* Domyślnie ukryta */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Czarny kolor z 50% przezroczystością */
    z-index: 999; /* Nakładka nad innymi elementami, ale pod oknem modalnym */
}
      
      .insurance-expired {
       background-color: #f8d7da;
        
    }
    .insurance-warning {
        background-color: #fff3cd;
        
    }
    .insurance-valid {
        background-color: #d4edda;
      
    }
       
    </style>
</head>
<body>
  
 
  <div id="overlay"></div>
  
    <div id="menu-add">
        <h2>Dodaj wymianę</h2>
        <p>Wybierz opcję:</p>
        <button onclick="menuEditMileage()">Aktualizacja przebiegu</button>
        <button onclick="menuNewPart()">Wymiana części</button>
        <button onclick="menuEditInsurance()">Nowe ubezpieczenie</button>
        <button onclick="menuEditInspection()">Nowy przegląd</button>
        <button onclick="menuEditTire()">Wymiana opon</button>
        <button onclick="menuNewFuel()">Tankowanie</button>
        <button onclick="closeMenuAdd()">Anuluj</button>
    </div>

    <form id="edit-mileage">
        <h2>Aktualizacja przebiegu:</h2>
        <label for="mileage-input">Podaj nowy przebieg w km (6 cyfr):</label>
        <input type="number" id="mileage-input" name="mileage" min="100000" max="999999" required>
        <button type="submit" onclick="editMileage()">Zatwierdź</button>
        <button type="button" onclick="closeMenuEditMileage()">Anuluj</button>
    </form>
  
    <form id="edit-insurance">
        <h2>Nowe ubezpieczenie:</h2>
        <label for="insurance-input">Podaj termin ważności:</label>
        <input type="date" id="insurance-input" name="insurance" required>
        <button type="submit" onclick="editInsurance()">Zatwierdź</button>
        <button type="button" onclick="closeMenuEditInsurance()">Anuluj</button>
    </form>
  
    <form id="edit-inspection">
        <h2>Nowy przegląd:</h2>
        <label for="insurance-input">Podaj termin ważności:</label>
        <input type="date" id="inspection-input" name="inspection" required>
        <button type="submit" onclick="editInspection()">Zatwierdź</button>
        <button type="button" onclick="closeMenuEditInspection()">Anuluj</button>
    </form>
    
    <form id="new-part">
        <h2>Wymiana części:</h2>
    
        <label for="add-part">Nazwa części:</label>
        <input type="text" id="add-part-name-input" name="" required><br>

        <label for="add-part">Numer seryjny:</label>
        <input type="text" id="add-part-number-input" name="" required><br>
      
        <label for="add-part">Koszt części:</label>
        <input type="number" id="add-part-price-input" name="" step="0.01" required><br>

        <label for="add-part">Data instalacji:</label><br>
        <input type="date" id="add-part-date-input" name="" required><br>

        <label for="add-part">Przebieg:</label><br>
        <input type="number" id="add-part-mileage-input" name="" required><br>
      
        <label for="add-part">Następna wymiana (km):</label>
        <input type="number" id="add-part-next-input" name="" required><br>
      
        <button type="submit" onclick="addNewPart()">Zatwierdź</button>
        <button type="button" onclick="closeMenuNewPart()">Anuluj</button>
    </form>
  
  
  <form id="new-fuel">
        <h2>Nowe tankowanie:</h2>
        
        <label for="add-fuel">Litry:</label>
        <input type="number" id="add-fuel-liters-input" name="" required><br>

        <label for="add-fuel-type-input">Rodzaj paliwa:</label>
    <select id="add-fuel-type-input" name="fuel_type" required>
        <option value="petrol">Benzyna</option>
        <option value="diesel">Diesel</option>
        <option value="lpg">LPG</option>
    </select><br>
      
        <label for="add-fuel">Koszt za litr:</label>
        <input type="number" id="add-fuel-price-input" name="" step="0.01" required><br>

        <label for="add-fuel">Data tankowania:</label><br>
        <input type="date" id="add-fuel-date-input" name="" required><br>

        <label for="add-fuel">Dystans w km:</label><br>
        <input type="number" id="add-fuel-distance-input" name="" required><br>
      
        <button type="submit" onclick="addNewFuel()">Zatwierdź</button>
        <button type="button" onclick="closeMenuNewFuel()">Anuluj</button>
    </form>
  
 
  
  <div id="menu-info">
    <h2>Informacje o samochodzie:</h2>
    <div id="info-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuInfo()">Zamknij</button>
</div>
  
  <div id="menu-history">
    <h2>Informacje o wymianach części:</h2>
    <div id="history-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuHistory()">Zamknij</button>
</div>
  
  <div id="menu-service">
    <h2>Informacje o serwisie:</h2>
    <div id="service-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuService()">Zamknij</button>
</div>
  
</body>
</html>