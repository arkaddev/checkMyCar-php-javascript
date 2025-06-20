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
    <title>Statystyki - Auto Serwis Online</title>
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
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          &nbsp;
          <a href="logout.php" title="Wyloguj">
          <i class="fas fa-sign-out-alt"></i></a>
          
          </p>
           
      </div>
     
     
     <!-- MENU ZAKŁADKI -->
    <nav class="tab-menu">
        <ul>
            <li><a href="#general">Ogólne</a></li>
            <li><a href="#cost">Koszty</a></li>
            <li><a href="#settings">Inne</a></li>
        </ul>
    </nav>

    <!-- TREŚĆ ZAKŁADEK -->
    <div class="tab-content" id="general">
        <h2>Statystyki Ogólne</h2>
        <p>Tu znajdziesz ogólne statystyki.</p>
    </div>
     

     
     
<div class="tab-content" id="cost">
    <h2>Koszty</h2>
    <?php
    $sql = "
        SELECT 
            YEAR(exchange_date) AS year, 
            MONTH(exchange_date) AS month, 
            car_id,
            SUM(price) AS total_cost 
        FROM parts 
        GROUP BY year, month, car_id 
        ORDER BY year DESC, month DESC, car_id
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<ul>";
        while ($row = $result->fetch_assoc()) {
            $formattedMonth = str_pad($row['month'], 2, '0', STR_PAD_LEFT); // np. 04 zamiast 4
            echo "<li>Miesiąc {$formattedMonth}/{$row['year']}, Samochód ID {$row['car_id']}: " . number_format($row['total_cost'], 2) . " PLN</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Brak danych kosztów w bazie.</p>";
    }
    ?>
</div>
     
     
     
     
     

    <div class="tab-content" id="settings">
        <h2>Inne</h2>
        <p>Opcje inne.</p>
    </div>
     
     
     
  </div>

  
</body>
</html>