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





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['fuelLiters'])) {
    $car_id = intval($_POST['car_id']);
    $fuel_liters = $conn->real_escape_string($_POST['fuelLiters']);
    $fuel_price = intval($_POST['fuelPrice']);
    $fuel_type = $conn->real_escape_string($_POST['fuelType']);
    $fuel_date = $conn->real_escape_string($_POST['fuelDate']);
    $fuel_distance = intval($_POST['fuelDistance']);
   
    
    $update_query = "INSERT INTO fuel (id, liters, price, fuel_type, refueling_date, distance, car_id) 
                     VALUES (NULL, '$fuel_liters', '$fuel_price', '$fuel_type', '$fuel_date', '$fuel_distance', '$car_id')";
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Tankowanie zostało dodane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania tankowania']);
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
    <style>
       #menu-fuel-history {
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
    </style>
  
</head>
<body>
  <div id="overlay"></div>
  
   <div class="main-container">
        <div class="user-container">
          <span class="title">Spalanie</span>
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
                    <th>Akcja</th>
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
                      
                      echo '<td><button class="add-fuel-button" onclick="menuAddFuel(' . htmlspecialchars($row['id']) . ')">Dodaj</button></td>';
                      
                      echo '<td><button class="history-fuel-button" onclick="openMenuFuelHistory(' . htmlspecialchars($row['id']) . ')">Historia</button></td>';

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
      
        <button type="submit" onclick="addFuel()">Zatwierdź</button>
        <button type="button" onclick="closeMenuAddFuel()">Anuluj</button>
    </form>
  
  
  <div id="menu-fuel-history">
    <h2>Informacje o wymianach części:</h2>
    <div id="fuel-history-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuFuelHistory()">Zamknij</button>
</div>
</body>
</html>

<script>
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
  
  
  
  function addFuel() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza
alert(selectedCarId);
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
            alert("Wystąpił błąd podczas dodawania tankowania.");
        });
    }













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
                            
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(history => {
                tableHTML += `
                    <tr>
                        <td>${history.car_id}</td>
                        <
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


function closeMenuFuelHistory() {
    selectedCarId = null;
    document.getElementById('menu-fuel-history').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
}


</script>
