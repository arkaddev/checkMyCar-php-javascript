<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}


$config = include('config/db_config.php');
// Tworzymy połączenie
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);


// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  


         
// Zapytanie SQL, aby pobrać dane z tabeli 'cars'
$query = "
    SELECT 
        c.id,
        c.model, 
        c.year, 
        c.user_id,
        IFNULL(ROUND(SUM(f.liters)*100 / SUM(f.distance), 2), 'Brak danych') AS average_fuel_consumption,
        (
        SELECT 
            ROUND((f1.liters/f1.distance)*100,2)
        FROM 
            fuel f1
        WHERE 
            f1.car_id = c.id
        ORDER BY 
            f1.refueling_date DESC
        LIMIT 1
    ) AS last_fuel_consumption
       
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
        
    GROUP BY 
       c.model
       
";

        
        //Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $query .= " HAVING user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
}
    
$query .= " ORDER BY c.model ASC";

$result = mysqli_query($conn, $query);


require 'requests/fuel/update_fuel.php';




// Zamykamy połączenie
mysqli_close($conn);
?> 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
  
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">
  
  <script src="js/fuel/menuFuel.js"></script>
  <script src="js/fuel/fuelActions.js"></script>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    #menu-fuel-history{
   width: 1100px;
    font-size: 14px;
}
    
    </style>
  
</head>
<body>
  <div id="overlay"></div>
  
   <div class="main-container">
        <div class="user-container">
          <span class="title">
            <a href="menu.php" class=""><i class="fas fa-home"></i></a>
            &nbsp; Spalanie</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
     
     
     
     
      <!-- Tabela z danymi z bazy -->
        <table>
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Rok</th>
                    <th>Średnie spalanie</th>
                  <th>Ostatnie spalanie</th>
                    <th>Opcje</th>
                 
                </tr>
            </thead>
            <tbody>
                <?php
                // Wyświetlanie danych z tabeli 'cars'
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                        
                      echo '<td>' . (isset($row['average_fuel_consumption']) ? htmlspecialchars($row['average_fuel_consumption']) : 'Brak danych') . '</td>';
                        
                      echo '<td>' . (isset($row['last_fuel_consumption']) ? htmlspecialchars($row['last_fuel_consumption']) : 'Brak danych') . '</td>';
                      
                      echo '<td>
                      
                      <button class="add-fuel-button" onclick="menuAddFuel(' . htmlspecialchars($row['id']) . ')" title="Dodaj tankowanie"><i class="fas fa-plus"></i></button>
                      
                      <button class="history-fuel-button" onclick="openMenuFuelHistory(' . htmlspecialchars($row['id']) . ')" title="Historia"><i class="fas fa-history"></i></button>
                      
                      <button class="history-fuel-button" onclick="openFuelHistoryChart(' . htmlspecialchars($row['id']) . ') "title="Wykres"><i class="fas fa-chart-line"></i></button>
                      
                      
                      </td>';
                      
                     
                      
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
     
     
     
    
   
  </div>
  
  
  
  <form id="add-fuel">
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
        <button type="button" onclick="closeMenuAddFuel()">Anuluj</button>
    </form>
  
  
  <div id="menu-fuel-history">
    <h2>Informacje o tankowaniach:</h2>
    <div id="fuel-history-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuFuelHistory()">Zamknij</button>
   
</div>
  
  <div id="chart-fuel-history" style="display: none;">
    <h2>Wykres spalania</h2>
    <canvas id="fuelChart"></canvas>
    <button onclick="closeFuelHistoryChart()">Zamknij</button>
</div>

 
</body>
</html>