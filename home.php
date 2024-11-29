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
// Odczytaj dane z pliku JSON
$cars_json = file_get_contents('data/cars.json');
$cars = json_decode($cars_json, true);

// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  
      
// Sprawdź, czy dane zostały załadowane poprawnie
if ($cars === null) {
    echo "Błąd przy wczytywaniu danych.";
    exit;
}

// Wybrane nagłówki (kolumny), które mają być wyświetlone
$selected_headers = ['id', 'brand', 'model', 'year', 'username', 'mileage'];

// Rozpocznij tabelę HTML
echo '<table border="1" cellpadding="5" cellspacing="0">'; // Ustalamy wygląd tabeli

// Wyświetl nagłówki tabeli
echo '<tr>';
foreach ($selected_headers as $header) {
    echo "<th>" . ucfirst($header) . "</th>";
}
echo '</tr>';

      
      
      
      
      foreach ($cars as $car) {
    // Jeśli użytkownik jest "user", to sprawdzamy, czy "username" odpowiada zalogowanemu użytkownikowi
    if ($user_role !== 'admin' && $car['username'] !== $_SESSION['username']) {
        continue; // Jeśli nie jest to jego rekord, to przechodzimy do następnego
    }

    // Wyświetl dane dla danego samochodu
    echo '<tr>';
    foreach ($selected_headers as $header) {
        echo "<td>" . htmlspecialchars($car[$header]) . "</td>";
    }
    echo '</tr>';
}
      
      
      

// Zakończ tabelę
echo '</table>';
?>