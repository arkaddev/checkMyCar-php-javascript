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
    
    $update_query = "INSERT INTO parts (id, name, number, price, exchange_date, kilometers_status, next_exchange_km, car_id) 
                     VALUES (NULL, '$part_name', '$part_number', '$part_price', '$part_date', '$part_mileage', '$part_next', '$car_id')";
  
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


  
  
  




// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  
// Przygotowanie zapytania SQL, aby pobrać dane samochodów
$sql = "SELECT id, model, year, user_id, insurance, technical_inspection, mileage FROM cars";

// Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
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
    echo '<th>Opcje</th>';
    echo '</tr>';
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td>' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
        
        echo '<td>';
        echo '<button onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')">Dodaj</button>';
        echo '<button onclick="openInfo(' . htmlspecialchars($row['id']) . ')">Info</button>';
        echo '<button onclick="o1(' . htmlspecialchars($row['id']) . ')">Historia</button>';
        echo '<button onclick="o2(' . htmlspecialchars($row['id']) . ')">Serwis</button>';
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
    <style>
        /* glowne menu dodawania, okno z edycja przebiegu */
        #menu-add, #edit-mileage, #edit-insurance, #edit-inspection, #new-part, #menu-info {
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
      
        #menu-add button, #edit-mileage button, #edit-insurance button, #edit-inspection button, #new-part button, #menu-info button {
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    
        #edit-mileage input[type="number"], #edit-insurance input[type="date"], #edit-inspection input[type="date"], #add-part-input input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
      
       
    </style>
</head>
<body>
    <div id="menu-add">
        <h2>Dodaj wymianę</h2>
        <p>Wybierz opcję:</p>
        <button onclick="menuEditMileage()">Aktualizacja przebiegu</button>
        <button onclick="menuNewPart()">Wymiana części</button>
        <button onclick="menuEditInsurance()">Nowe ubezpieczenie</button>
        <button onclick="menuEditInspection()">Nowy przegląd</button>
        <button onclick="menuEditTire()">Wymiana opon</button>
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
  
 
  
  <div id="menu-info">
    <h2>Informacje o samochodzie:</h2>
    <div id="info-content">
        <!-- dane z tabeli cars_info -->
    </div>
    <button onclick="closeMenuInfo()">Zamknij</button>
</div>
  
  
</body>
</html>


 <script>
    let selectedCarId = null;

    // Funkcja otwierająca główne menu dodawania
    function openMenuAdd(carId) {
        selectedCarId = carId;
        document.getElementById('menu-add').style.display = 'block';
    }

    // Funkcja zamykająca główne menu dodawania
    function closeMenuAdd() {
        selectedCarId = null;
        document.getElementById('menu-add').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przebiegu
    function menuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przebiegu
    function closeMenuEditMileage() {
        document.getElementById('edit-mileage').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją ubezpieczenia
    function menuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją ubezpieczenia
    function closeMenuEditInsurance() {
        document.getElementById('edit-insurance').style.display = 'none';
    }

    // Funkcja otwierająca okno z edycją przeglądu
    function menuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'block';
    }

    // Funkcja zamykająca okno z edycją przeglądu
    function closeMenuEditInspection() {
        document.getElementById('edit-inspection').style.display = 'none';
    }

    // Funkcja otwierająca okno z wymianą części
    function menuNewPart() {
        document.getElementById('new-part').style.display = 'block';
    }

    // Funkcja zamykająca okno z wymianą części
    function closeMenuNewPart() {
        document.getElementById('new-part').style.display = 'none';
    }

    // Funkcja aktualizująca przebieg w bazie danych
    function editMileage() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const mileage = document.getElementById('mileage-input').value;

        if (!selectedCarId) {
            alert("Nie wybrano samochodu.");
            return;
        }

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&mileage=${mileage}`
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
            alert("Wystąpił błąd podczas aktualizacji przebiegu.");
        });
    }

    // Funkcja aktualizująca ubezpieczenie w bazie danych
    function editInsurance() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const insurance = document.getElementById('insurance-input').value;

        if (!selectedCarId) {
            alert("Nie wybrano samochodu.");
            return;
        }

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&insurance=${insurance}`
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
            alert("Wystąpił błąd podczas aktualizacji ubezpieczenia.");
        });
    }

    // Funkcja aktualizująca przegląd w bazie danych
    function editInspection() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const inspection = document.getElementById('inspection-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&inspection=${inspection}`
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
            alert("Wystąpił błąd podczas aktualizacji przeglądu.");
        });
    }

    // Funkcja dodająca nową część do bazy danych
    function addNewPart() {
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const partName = document.getElementById('add-part-name-input').value;
        const partNumber = document.getElementById('add-part-number-input').value;
        const partPrice = document.getElementById('add-part-price-input').value;
        const partDate = document.getElementById('add-part-date-input').value;
        const partMileage = document.getElementById('add-part-mileage-input').value;
        const partNext = document.getElementById('add-part-next-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `car_id=${selectedCarId}&partName=${partName}&partNumber=${partNumber}&partPrice=${partPrice}&partDate=${partDate}&partMileage=${partMileage}&partNext=${partNext}`
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
            alert("Wystąpił błąd podczas dodawania części.");
        });
    }
   
   
   
   function openInfo(carId) {
    selectedCarId = carId;
    document.getElementById('info-content').innerHTML = "Ładowanie danych..."; // Wiadomość oczekiwania
    document.getElementById('menu-info').style.display = 'block';

    // Wysłanie zapytania POST do serwera
    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `car_id_info=${selectedCarId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            const infoContent = document.getElementById('info-content');
            infoContent.innerHTML = ""; // Wyczyszczenie zawartości
            data.data.forEach(info => {
                const infoDiv = document.createElement('div');
                infoDiv.innerHTML = `
                    <p><strong>Id samochodu:</strong> ${info.car_id}</p>
                    <p><strong>Silnik:</strong> ${info.engine_number}</p>
                    <p><strong>Data produkcji:</strong> ${info.production_date}</p>
                    <p><strong>Moc km/kw:</strong> ${info.km_kw}</p>
                    <p><strong>Olej:</strong> ${info.oil_number}</p>
                    <p><strong>Filtr oleju:</strong> ${info.oil_filter_number}</p>
                    <p><strong>Filtr powietrza:</strong> ${info.air_filter_number}</p>
                `;
                infoContent.appendChild(infoDiv);
            });
        } else {
            document.getElementById('info-content').innerHTML = data.message;
        }
    })
    .catch(error => {
        console.error("Wystąpił błąd:", error);
        document.getElementById('info-content').innerHTML = "Wystąpił błąd podczas pobierania danych.";
    });
}

function closeMenuInfo() {
    selectedCarId = null;
    document.getElementById('menu-info').style.display = 'none';
}
  
   
   
   
</script>
