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


require 'requests/fuel/add_fuel.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_history']))  {
    $car_id = intval($_POST['car_id_history']);
    
    $query = "SELECT
    id,
    car_id,
    liters, 
    price, 
    fuel_type, 
    refueling_date, 
    distance,
    details,
    ROUND((liters/distance)*100,2) AS average_fuel_consumption
    FROM fuel WHERE car_id = $car_id ORDER BY refueling_date ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}
  


// usuwanie tankowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fuel_id_history']))  {
    $fuel_id = intval($_POST['fuel_id_history']);
    
    $query = "DELETE FROM fuel WHERE id = $fuel_id";
  
   if ($conn->query($query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dane zostały zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji danych']);
    }
    exit();
}




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
      
        <button type="submit" onclick="addFuel()">Zatwierdź</button>
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

<script>
  
  













function openMenuFuelHistory(carId) {
    selectedCarId = carId;

    const fuelHistoryContent = document.getElementById('fuel-history-content');
    fuelHistoryContent.innerHTML = "<p>Ładowanie danych...</p>"; // Wiadomość oczekiwania
    document.getElementById('menu-fuel-history').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';

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
                            <th>Litry</th>
                            <th>Rodzaj paliwa</th>
                            <th>Koszt za litr</th>
                            <th>Data tankowania</th>
                            <th>Dystans w km</th> 
                          <th>Szczegóły</th>  
                          <th>Spalanie na 100 km</th>
                          <th>Usuń</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(history => {
                tableHTML += `
                    <tr>
                        <td>${history.car_id}</td>
                        <td>${history.liters}</td>  
                        <td>${history.price}</td>
                        <td>${history.fuel_type}</td>
                        <td>${history.refueling_date}</td>
                        <td>${history.distance}</td>
                      <td>${history.details}</td>
                       <td>${history.average_fuel_consumption}</td>
                      <td><button onclick="deleteFuel('${history.id}')">Usuń</button></td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            fuelHistoryContent.innerHTML = tableHTML; // Wstawienie tabeli do kontenera
        } else {
            fuelHistoryContent.innerHTML = `<p>${data.message}</p>`;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        historyContent.innerHTML = "<p>Wystąpił błąd podczas pobierania danych.</p>";
    });
}






function closeMenuFuelHistory() {
    selectedCarId = null;
    document.getElementById('menu-fuel-history').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}




function deleteFuel(fuelId) {
   selectedFuelId = fuelId;
   event.preventDefault(); // Zapobiega domyślnemu działaniu formularza
  
        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `fuel_id_history=${selectedFuelId}`
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
            alert("Wystąpił błąd podczas usuwania tankowania.");
        });
}

  
function openFuelHistoryChart(carId) {
    selectedCarId = carId;

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
            const labels = data.data.map(entry => entry.refueling_date);
            const values = data.data.map(entry => entry.average_fuel_consumption);

            const ctx = document.getElementById("fuelChart").getContext("2d");
            
            if (window.fuelChartInstance) {
                window.fuelChartInstance.destroy(); // Usuwamy stary wykres, jeśli istnieje
            }

            window.fuelChartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Spalanie (l/100km)",
                        data: values,
                        borderColor: "rgba(75, 192, 192, 1)",
                        backgroundColor: "rgba(75, 192, 192, 0.2)",
                        borderWidth: 2,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            document.getElementById("chart-fuel-history").style.display = "block";
  document.getElementById('overlay').style.display = 'block';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Błąd pobierania danych wykresu:", error);
        alert("Błąd pobierania danych.");
    });
}

function closeFuelHistoryChart() {
    document.getElementById("chart-fuel-history").style.display = "none";
  document.getElementById('overlay').style.display = 'none';
}
  

</script>