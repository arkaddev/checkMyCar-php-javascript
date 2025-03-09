<?php
require 'config/session.php';
require 'config/db_connection.php';

require 'helpers/functions.php';

require 'requests/update_car_data.php';
require 'requests/car_info.php';
require 'requests/add_part.php';
require 'requests/add_fuel.php';


?>


<?php

$query = "
    SELECT 
        c.id, 
        c.model, 
        c.year, 
        c.user_id, 
        c.insurance, 
        c.technical_inspection, 
        c.mileage,
        IFNULL(ROUND(SUM(f.liters)*100 / SUM(f.distance), 2), 'Brak danych') AS average_fuel_consumption
        
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
      
        
    GROUP BY 
        c.id, c.model, c.year, c.user_id, c.insurance, c.technical_inspection, c.mileage

";




if ($user_role !== 'admin') {
    $query .= " HAVING user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
}

 

$result = mysqli_query($conn, $query);

    

// Zamykamy połączenie
mysqli_close($conn);
?> 

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista samochodów</title>

<script src="js/listMenuActions.js"></script>
<script src="js/carDataActions.js"></script>
<script src="js/infoCarActions.js"></script>
<script src="js/partActions.js"></script>
<script src="js/fuelActions.js"></script>
<script src="js/historyActions.js"></script>
<script src="js/serviceActions.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
  
  
  <style>
  /* kontener glowny */
.main-container {
    max-width: 1100px; /* Ograniczenie maksymalnej szerokości */
}
    #list-history, #list-service{
   width: 1100px;
    font-size: 14px;
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
  
  <div class="main-container">
        <div class="user-container">
          <span class="title">Lista pojazdów</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
  
   
      <!-- Tabela z danymi z bazy -->
        <table>
            <thead>
                <tr>
                  
                   <th>ID</th>
                   <th>Marka i model</th>
                   <th>Rok</th>
                   <th>Użytkownik</th>
                   <th>Ubezpieczenie</th>
                   <th>Przeglad</th>
                   <th>Przebieg</th>
                   <th>Spalanie</th>
                   <th>Opcje</th>
        
                </tr>
            </thead>
            <tbody>
                <?php
                // Wyświetlanie danych z tabeli 'cars'
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        
                      
                      
                      
                      
                      
      $insuranceClass = getInsuranceClass($row['insurance']);
      $inspectionClass = getInsuranceClass($row['technical_inspection']);
 
      
      
      
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) .' 
        
        <button class="list-info-button" onclick="openInfoCar(' . htmlspecialchars($row['id']) . ')" title="Informacje"><i class="fas fa-info"></i></button>
          
          </td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td class="' . $insuranceClass . '">' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td class="' . $inspectionClass . '">' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
       echo '<td>' . (isset($row['average_fuel_consumption']) ? htmlspecialchars($row['average_fuel_consumption']) : 'Brak danych') . '</td>';
      
      
        
        echo '<td>
      
      <button class="list-menu-button" onclick="openListMenu(' . htmlspecialchars($row['id']) . ')" title="Dodaj"><i class="fas fa-plus"></i></button>
          
      <button class="list-menu-button" onclick="openHistory(' . htmlspecialchars($row['id']) . ')"><i class="fas fa-history"></i></button>
      
      <button class="list-menu-button" onclick="openService(' . htmlspecialchars($row['id']) . ')"><i class="fas fa-tools"></i></button>
      
      </td>';
        
        echo '</tr>';   
                      
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
  
  </div>
  
 
  
  
  <div id="overlay"></div>
  
    <div id="list-menu">
        <h2>Dodaj wymianę</h2>
        <p>Wybierz opcję:</p>
        <button onclick="listMenuEditMileage()">Aktualizacja przebiegu</button>
        <button onclick="listMenuPartReplacement()">Wymiana części</button>
        <button onclick="listMenuEditInsurance()">Nowe ubezpieczenie</button>
        <button onclick="listMenuEditInspection()">Nowy przegląd</button>
        <button onclick="listMenuTireReplacement()">Wymiana opon</button>
        <button onclick="listMenuNewFuel()">Tankowanie</button>
        <button class="exit-button" onclick="closeListMenu()">Anuluj</button>
    </div>
  
  <form id="list-edit-mileage">
        <h2>Aktualizacja przebiegu:</h2>
        <label>Podaj nowy przebieg w km (6 cyfr):</label>
        <input type="number" id="mileage-input" name="mileage" min="100000" max="999999" required>
        <button type="submit" onclick="editMileage()">Zatwierdź</button>
        <button type="button" onclick="closeListMenuEditMileage()">Anuluj</button>
    </form>
  
  <form id="list-part-replacement">
        <h2>Wymiana części:</h2>
    
        <label>Nazwa części:</label>
        <input type="text" id="add-part-name-input" name="" required><br>

        <label>Numer seryjny:</label>
        <input type="text" id="add-part-number-input" name="" required><br>
      
        <label>Koszt części:</label>
        <input type="number" id="add-part-price-input" name="" step="0.01" required><br>

        <label>Data instalacji:</label><br>
        <input type="date" id="add-part-date-input" name="" required><br>

        <label>Przebieg:</label><br>
        <input type="number" id="add-part-mileage-input" name="" required><br>
      
        <label>Następna wymiana (km):</label>
        <input type="number" id="add-part-next-input" name="" required><br>
      
        <button type="submit" onclick="addNewPart()">Zatwierdź</button>
        <button type="button" onclick="closeListMenuPartReplacement()">Anuluj</button>
    </form>
  
<form id="list-edit-insurance">
        <h2>Nowe ubezpieczenie:</h2>
        <label>Podaj termin ważności:</label>
        <input type="date" id="insurance-input" name="insurance" required>
        <button type="submit" onclick="editInsurance()">Zatwierdź</button>
        <button type="button" onclick="closeListMenuEditInsurance()">Anuluj</button>
    </form>
  
    <form id="list-edit-inspection">
        <h2>Nowy przegląd:</h2>
        <label>Podaj termin ważności:</label>
        <input type="date" id="inspection-input" name="inspection" required>
        <button type="submit" onclick="editInspection()">Zatwierdź</button>
        <button type="button" onclick="closeListMenuEditInspection()">Anuluj</button>
    </form>
  
   <form id="list-new-fuel">
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
    
        <label for="add-fuel">Szczegóły:</label>
        <input type="text" id="add-fuel-details-input" name="" required><br>
      
        <button type="submit" onclick="addNewFuel()">Zatwierdź</button>
        <button type="button" onclick="closeListMenuNewFuel()">Anuluj</button>
    </form>
  
  <form id="list-info-car">
    <h2>Informacje o samochodzie:</h2>
    <div id="list-info-car-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button type="button" onclick="closeListInfoCar()">Zamknij</button>
</form>
  
  <form id="list-history">
    <h2>Informacje o wymianach części:</h2>
    <div id="list-history-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button type="button" onclick="closeListHistory()">Zamknij</button>
</form>
  
  <form id="list-service">
    <h2>Informacje o serwisie:</h2>
    <div id="list-service-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button type="button" onclick="closeListService()">Zamknij</button>
</form>
  
  
  
</body>
</html>
