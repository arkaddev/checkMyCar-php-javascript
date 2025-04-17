<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}


$userId = $_SESSION['id'];



$config = include('config/db_config.php');
// Tworzymy połączenie
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    $db_message = "Brak połączenia: " . mysqli_connect_error();
} else {
    $db_message = "Połączono.";
}


?>





<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statystyki</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
  
    <style>
.tab-menu ul {
    list-style: none;
    padding: 0;
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.tab-menu ul li a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    transition: background 0.3s;
}

.tab-menu ul li a:hover {
    background-color: #eee;
}

.tab-content {
    display: none;
    margin-top: 20px;
}

.tab-content:target {
    display: block;
}
    </style>
  
</head>
<body>
  <div id="overlay"></div>
  
   <div class="main-container">
        <div class="user-container">
          <span class="title">
            <a href="menu.php" class=""><i class="fas fa-home"></i></a>
            &nbsp; Statystyki</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
     
     
     <!-- MENU ZAKŁADKI -->
    <nav class="tab-menu">
        <ul>
            <li><a href="#general">Ogólne</a></li>
            <li><a href="#activity">Koszty</a></li>
            <li><a href="#settings">Inne</a></li>
        </ul>
    </nav>

    <!-- TREŚĆ ZAKŁADEK -->
    <div class="tab-content" id="general">
        <h2>Statystyki Ogólne</h2>
        <p>Tu znajdziesz ogólne statystyki.</p>
    </div>
     
     <div class="tab-content" id="activity">
    <h2>Koszty</h2>
    <?php
    // Określ miesiąc i rok, np. bieżący miesiąc
    $month = date('m'); // aktualny miesiąc
    $year = date('Y');  // aktualny rok

    // Zapytanie SQL
    $sql = "SELECT SUM(price) AS total_cost FROM parts WHERE MONTH(exchange_date) = ? AND YEAR(exchange_date) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $month, $year); // wiązanie parametrów (miesiąc i rok)
    $stmt->execute();
    $stmt->bind_result($total_cost);
    $stmt->fetch();

    // Sprawdzenie i wyświetlenie wyników
    if ($total_cost !== null) {
        echo "<p>Całkowite koszty za miesiąc $month/$year: " . number_format($total_cost, 2) . " PLN</p>";
    } else {
        echo "<p>Brak danych dla tego miesiąca.</p>";
    }

    // Zamknięcie połączenia
    $stmt->close();
    ?>
</div>
     
     
     
     
     
     
     
     

    <div class="tab-content" id="settings">
        <h2>Inne</h2>
        <p>Opcje inne.</p>
    </div>
     
     
     
  </div>

  
</body>
</html>
