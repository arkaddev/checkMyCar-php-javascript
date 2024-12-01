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

// Sprawdzamy, czy połączenie się powiodło
if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
} else {
    echo "Połączenie z bazą danych powiodło się!";
}


// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  


         

// Zamykamy połączenie
mysqli_close($conn);
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

