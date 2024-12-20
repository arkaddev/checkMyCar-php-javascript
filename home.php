<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
// Baza danych
$config = include('config/db_config.php');
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
}

// Obsługa zapisu przebiegu w bazie danych
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['mileage'])) {
    $car_id = intval($_POST['car_id']);
    $mileage = intval($_POST['mileage']);

    $update_query = "UPDATE cars SET mileage = $mileage WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Przebieg został zaktualizowany']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przebiegu']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['insurance'])) {
    $car_id = intval($_POST['car_id']);
    $insurance = $conn->real_escape_string($_POST['insurance']);
    
    $update_query = "UPDATE cars SET insurance = '$insurance' WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Ubezpieczenie zostało zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji ubezpieczenia']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['inspection'])) {
    $car_id = intval($_POST['car_id']);
    $inspection = $conn->real_escape_string($_POST['inspection']);
    
    $update_query = "UPDATE cars SET technical_inspection = '$inspection' WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Przegląd został zaktualizowany']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przeglądu']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['partName'])) {
    $car_id = intval($_POST['car_id']);
    $part_name = $conn->real_escape_string($_POST['partName']);
    $part_number = $conn->real_escape_string($_POST['partNumber']);
    $part_price = intval($_POST['partPrice']);
    $part_date = $conn->real_escape_string($_POST['partDate']);
    $part_mileage = intval($_POST['partMileage']);
    $part_next = intval($_POST['partNext']);
    
    $update_query = "INSERT INTO parts (id, name, number, price, exchange_date, kilometers_status, next_exchange_km, car_id, is_replaced) 
                     VALUES (NULL, '$part_name', '$part_number', '$part_price', '$part_date', '$part_mileage', '$part_next', '$car_id', '0')";
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Część została dodana']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania części']);
    }
    exit();
}


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
        echo json_encode(['status' => 'success', 'message' => 'Część została dodana']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania części']);
    }
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_info']))  {
    $car_id = intval($_POST['car_id_info']);
    
    $query = "SELECT * FROM cars_info WHERE car_id = $car_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_history']))  {
    $car_id = intval($_POST['car_id_history']);
    
    $query = "SELECT * FROM parts WHERE car_id = $car_id ORDER BY kilometers_status ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}
  
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_service']))  {
    $car_id = intval($_POST['car_id_service']);
    
    //$query = "SELECT * FROM parts WHERE car_id = $car_id";
    
    $query = "
        SELECT
            parts.id,
            parts.car_id, 
            parts.name, 
            parts.number, 
            parts.price, 
            parts.exchange_date, 
            parts.kilometers_status, 
            parts.next_exchange_km,
            (parts.next_exchange_km + parts.kilometers_status  - cars.mileage) AS when_exchange
        FROM parts 
        JOIN cars ON parts.car_id = cars.id
        WHERE parts.car_id = $car_id AND parts.is_replaced = 0
        ORDER BY when_exchange ASC
    ";
    
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}
  

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['part_id_service']))  {
    $part_id = intval($_POST['part_id_service']);
    
    $query = "UPDATE parts SET is_replaced = 1 WHERE parts.id = $part_id; ";
   if ($conn->query($query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dane zostały zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji danych']);
    }
    exit();
}

    
 
   
       

  


// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  
// Przygotowanie zapytania SQL, aby pobrać dane samochodów

$sql = "SELECT id, model, year, user_id, insurance, technical_inspection, mileage FROM cars";
/*$sql = "
    SELECT 
        c.id, 
        c.model, 
        c.year, 
        c.user_id, 
        c.insurance, 
        c.technical_inspection, 
        c.mileage,
        (f.distance / f.liters) AS fuel_consumption
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
";
*/
//bez zaokraglania do 2 miejsc po przecinku
//IFNULL(SUM(f.distance) / SUM(f.liters), 'Brak danych') AS average_fuel_consumption

$sql = "
    SELECT 
        c.id, 
        c.model, 
        c.year, 
        c.user_id, 
        c.insurance, 
        c.technical_inspection, 
        c.mileage,
        IFNULL(ROUND(SUM(f.distance) / SUM(f.liters), 2), 'Brak danych') AS average_fuel_consumption
        
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
      
        
    GROUP BY 
        c.id, c.model, c.year, c.user_id, c.insurance, c.technical_inspection, c.mileage

";



 //Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $sql .= " WHERE user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
  


}

 


$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Marka i model</th>';
    echo '<th>Rok</th>';
    echo '<th>Użytkownik</th>';
    echo '<th>Ubezpieczenie</th>';
    echo '<th>Przeglad</th>';
    echo '<th>Przebieg</th>';
    echo '<th>Spalanie</th>';
    echo '<th>Opcje</th>';
    echo '</tr>';
    
    while ($row = mysqli_fetch_assoc($result)) {
    
      
      $insuranceDate = $row['insurance'];
    $currentDate = date('Y-m-d'); // Pobieramy aktualną datę w formacie Y-m-d
    
   // Obliczenie różnicy w dniach między datą ubezpieczenia a bieżącą datą
    $dateDiff = (strtotime($insuranceDate) - strtotime($currentDate)) / (60 * 60 * 24);
    
    // Określenie klasy CSS na podstawie różnicy w dniach
    if ($dateDiff < 0) {
        $insuranceClass = 'insurance-expired'; // Czerwony dla przeterminowanego ubezpieczenia
    } elseif ($dateDiff <= 30) {
        $insuranceClass = 'insurance-warning'; // Pomarańczowy dla ubezpieczenia bliskiego terminu
    } else {
        $insuranceClass = 'insurance-valid'; // Zielony dla ważnego ubezpieczenia
    }
    
      
      
   
      
      
      
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td class="' . $insuranceClass . '">' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td>' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
       echo '<td>' . (isset($row['average_fuel_consumption']) ? htmlspecialchars($row['average_fuel_consumption']) : 'Brak danych') . '</td>';
      
      
        
        
        echo '<td>';
        echo '<button onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')">Dodaj</button>';
        echo '<button onclick="openInfo(' . htmlspecialchars($row['id']) . ')">Info</button>';
        echo '<button onclick="openHistory(' . htmlspecialchars($row['id']) . ')">Historia</button>';
        echo '<button onclick="openService(' . htmlspecialchars($row['id']) . ')">Serwis</button>';
        echo '</td>';
        
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo "Brak danych.";
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
