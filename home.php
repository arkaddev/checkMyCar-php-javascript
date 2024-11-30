<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>
 <?php
$config = include('config/db_config.php');
// Tworzymy połączenie
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Sprawdzamy, czy połączenie się powiodło
if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
} else {
    echo "Połączenie z bazą danych powiodło się!";
}


// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  


// Przygotowanie zapytania SQL, aby pobrać dane samochodów
$sql = "SELECT id, model, year, mileage, user_id FROM cars";

// Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $sql .= " WHERE user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
}


// Wykonaj zapytanie
$result = mysqli_query($conn, $sql);
// Sprawdzenie, czy zapytanie zwróciło jakieś wyniki
if ($result->num_rows > 0) {
    // Wyświetlanie danych w tabeli HTML
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Marka i model</th>';
    echo '<th>Rok</th>';
    echo '<th>Użytkownik</th>';
    echo '<th>Ubezpieczenie</th>';
    echo '<th>Przeglad</th>';
    echo '<th>Przebieg</th>';
    echo '</tr>';
    
    // Wyświetlanie danych samochodów
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td>' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
     

// Link do formularza dodawania części
    echo '<td>';
    //echo '<a href="add_part_form.php?car_id=' . htmlspecialchars($row['id']) . '">Dodaj wymiane php</a>';
      
         
      
    echo '</td>';
      
echo '<td>';
echo '<button onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')">Dodaj wymianę js</button>';
echo '</td>';
      
    echo '</tr>';
   
      

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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
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
        
    
  
  
  
  
  
  
  
  <?php if (isset($_SESSION['id'])): ?>
        <h1>Twoje id to: <?= htmlspecialchars($_SESSION['id']) ?>!</h1>
    <?php else: ?>
        <p>Nie przypisano id.</p>
    <?php endif; ?>
  
  <?php if (isset($_SESSION['role'])): ?>
  <h1>Twoja rola to: <?= htmlspecialchars($_SESSION['role']) ?>!</h1>
    <?php else: ?>
        <p>Nie przypisano roli.</p>
    <?php endif; ?>
  
  
    <h1>Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
  
    <p>Jesteś zalogowany.</p>
    <a href="logout.php">Wyloguj się</a>
  
  
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
            closeMenuAdd();
        }
   
        // Funkcja zamykająca okno z edycja przebiegu
        function closeMenuEditMileage() {
            document.getElementById('edit-mileage').style.display = 'none';
           
        }
   
       // Funkcja aktualizująca przebieg w bazie danych
        function editMileage() {
           
        }
    </script>








