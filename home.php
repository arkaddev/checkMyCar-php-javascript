<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>
  
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
</head>
<body>
  
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


      
   




<?php
// Parametry połączenia
$config = include('config/db_config.php');
// Tworzymy połączenie
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Sprawdzamy, czy połączenie się powiodło
if (!$conn) {
    // Jeśli połączenie nie uda się, wyświetli się błąd
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
} else {
    // Jeśli połączenie jest udane, wyświetlamy komunikat
    echo "Połączenie z bazą danych powiodło się!";
}

// Sprawdzenie połączenia inny spososb
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}

// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  

  
  
// Przygotowanie zapytania SQL, aby pobrać dane samochodów
$sql = "SELECT id, model, year, mileage, user_id FROM cars";

// Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $sql .= " WHERE user_id = '" . $conn->real_escape_string($_SESSION['id']) . "'";
}

// Wykonaj zapytanie
$result = $conn->query($sql);

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
    echo '<a href="add_part_form.php?car_id=' . htmlspecialchars($row['id']) . '">Dodaj wymiane</a>';
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






