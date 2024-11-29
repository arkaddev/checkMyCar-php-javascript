<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Pobranie ID samochodu z parametru GET
if (!isset($_GET['car_id'])) {
    die("Brak ID samochodu.");
}
$car_id = htmlspecialchars($_GET['car_id']);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj część</title>
</head>
<body>
    <h1>Dodaj nową część dla samochodu ID: <?= $car_id ?></h1>
    <form action="add_part.php" method="post">

        <label for="name_form">Nazwa części:</label>
        <input type="text" id="name_form" name="name_form" required>

        <label for="number_form">Numer seryjny:</label>
        <input type="text" id="number_form" name="number_form" required>
      
        <label for="price_form">Koszt części:</label>
        <input type="number" id="price_form" name="price_form" step="0.01" required>

        <label for="exchange_date_form">Data instalacji:</label>
        <input type="date" id="exchange_date_form" name="exchange_date_form" required>

        <label for="kilometers_status_form">Przebieg:</label>
        <input type="number" id="kilometers_status_form" name="kilometers_status_form" step="0.01" required>
        <label for="next_exchange_km_form">Następna wymiana (km):</label>
        <input type="number" id="next_exchange_km_form" name="next_exchange_km_form" required>
        
        <input type="hidden" name="car_id" value="<?= $car_id ?>">
      
        <button type="submit">Zapisz</button>
    </form>
    <a href="home.php">Powrót do listy samochodów</a>
</body>
</html>