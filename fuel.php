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

   
    <style>
        /* Podstawowe style strony */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            abackground-color: #f4f4f4;
            background: linear-gradient(to bottom right, #e3f2fd, #bbdefb); /* Tło gradientowe */
           
        }
        
      
        .menu-container {
   
        border-radius: 5px;
    padding: 0px 0px 10px 0px;
    width: 90vw; /* Kontener zajmuje 90% szerokości ekranu */
    max-width: 700px; /* Ograniczenie maksymalnej szerokości */
    margin: 50px auto; /* Wyśrodkowanie */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
      
     box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    
    background-color: #fff; /* Białe tło dla lepszego efektu */
}

      
      
.user-container {
    width: 100%; /* Szerokość taka sama jak menu-container */
    padding: 5px 30px; /* Wewnętrzny padding */
    text-align: right; /* Wyśrodkowanie tekstu */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
   background-color: silver;
      border-radius: 5px;
      
     
         
            display: flex;
            justify-content: space-between;
            align-items: center;
      
}
   
       .title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
      
      .username {
    font-weight: bold;
    color: #4caf50;
    font-size: 18px;
   
}
     
       /* Stylowanie tabeli */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0056b3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
     
      
      
      
      
      
      /* Stylizacja dla przycisku dodawania */
.new-fuel-button {
    background-color: #2196F3;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 5px 10px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.new-fuel-button:hover {
    background-color: #3F51B5;
}
      
       /* Dodawanie tankowania, wyglad okna */
      
    
       /* Nakładka tła */
#overlay {
    display: none; /* Domyślnie ukryta */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Czarny z przezroczystością */
    z-index: 999; /* Pod modalami */
    transition: background 0.3s ease;
}

       /* glowne menu dodawania*/
        #new-fuel{
           display: none; /* Domyślnie ukryte */
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -20%);
    width: 40%;
    max-width: 900px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
    padding: 30px;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
        }
      
     
      
      
      /* Przycisk w formularzach */
#new-fuel button {
    display: block;
    width: 100%;
    margin: 10px 0;
    padding: 12px 20px;
    background-color: #0056b3;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
     #new-fuel button:hover{
    background-color: #004085;
    transform: translateY(-2px);
}
      
      
/* Styl dla okien modalnych - pola formularzy */
form input[type="number"], form input[type="date"], form input[type="text"], form select {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

form input[type="number"]:focus, form input[type="date"]:focus, form input[type="text"]:focus, form select:focus {
    border-color: #0056b3;
    outline: none;
}

/* Przyciski anulowania */
form button[type="button"] {
    background-color: #e74c3c;
}

form button[type="button"]:hover {
    background-color: #c0392b;
}
      
    </style>
  
</head>
<body>
  <div id="overlay"></div>
  
   <div class="menu-container">
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
                      
                      echo '<td><button class="new-fuel-button" onclick="menuNewFuel(' . htmlspecialchars($row['id']) . ')">Dodaj</button></td>';

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
     
     
     
    
   
  </div>
  
  
  
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
  
</body>
</html>

<script>
  // Funkcja otwierająca okno z tankowaniem
    function menuNewFuel(carId) {
  selectedCarId = carId;
        document.getElementById('new-fuel').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z tankowaniem
    function closeMenuNewFuel() {
  selectedCarId = null;
        document.getElementById('new-fuel').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
    }
  
  
  
  function addNewFuel() {
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
</script>
