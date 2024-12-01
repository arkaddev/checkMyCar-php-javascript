<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>


<?php
//baza danych
$config = include('config/db_config.php');
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());} 





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







// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  
// Przygotowanie zapytania SQL, aby pobrać dane samochodów
$sql = "SELECT id, model, year, user_id, insurance, technical_inspection, mileage FROM cars";

// Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $sql .= " WHERE user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";}


$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0){
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
      echo '<button onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')">Dodaj wymianę js</button>';
      echo '</td>';
      
      echo '</tr>';}
    
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
        #menu-add, #edit-mileage {
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
      
        #menu-add button, #edit-mileage button{
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    
        #edit-mileage input[type="number"] {
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
        <button onclick="menuAddOption('editInsurance')">Nowe ubezpieczenie</button>
        <button onclick="menuAddOption('editInspection')">Nowy przegląd</button>
        <button onclick="menuAddOption('editTire')">Wymiana opon</button>
        <button onclick="closeMenuAdd()">Anuluj</button>
    </div>
  
  

    <form id="edit-mileage">
        <h2>Aktualizacja przebiegu:</h2>
       
            <label for="mileage-input">Podaj nowy przebieg w km (6 cyfr):</label>
            <input type="number" id="mileage-input" name="mileage" min="100000" max="999999" required>
            <button type="submit" onclick="editMileage()">Zatwierdź</button>
            <button type="button" onclick="closeMenuEditMileage()">Anuluj</button>
        </form>
        
    
</body>
</html>


 <script>
        let selectedCarId = null;

        // Funkcja otwierająca glowne menu dodawania
        function openMenuAdd(carId) {
            selectedCarId = carId;
            document.getElementById('menu-add').style.display = 'block';
           
        }

        // Funkcja zamykająca glowne menu dodawania
        function closeMenuAdd() {
            selectedCarId = null;
            document.getElementById('menu-add').style.display = 'none';
           
        }

        // Funkcja otwierająca okno z edycja przebiegu
        function menuEditMileage() {
        
        document.getElementById('edit-mileage').style.display = 'block';        
        }
   
        // Funkcja zamykająca okno z edycja przebiegu
        function closeMenuEditMileage() {
            document.getElementById('edit-mileage').style.display = 'none';
           
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
   
    </script>
