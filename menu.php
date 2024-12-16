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
    <title>Menu z przyciskami w ramce</title>
    <style>
        /* Podstawowe style strony */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .menu-container {
            border: 2px solid #333; /* Ramka wokół całego menu */
            border-radius: 10px; /* Zaokrąglone rogi ramki */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 700px;
            margin: 50px auto; /* Wyśrodkowanie na stronie */
            background-color: #fff; /* Tło menu */
        }

        .user-container {
            border: 2px solid #333; /* Ramka wokół nagłówka */
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%; /* Rozciągnięcie na całą szerokość menu */
            padding: 0px 20px;
            background-color: #fff; /* Tło nagłówka */
            text-align: center;
        }
        
        .top-menu, .bottom-menu {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .menu-item {
            color: white;
            padding: 20px 30px; /* Wymiary przycisków */
            margin: 0 10px; /* Odstęp między przyciskami */
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            width: 200px; /* Stała szerokość */
            height: 200px; /* Stała wysokość */
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease; /* Efekt przejścia */
            box-sizing: border-box; /* Uwzględnia padding w szerokości i wysokości */
        }

        /* Kolory przycisków */
        .menu-item-1 {
            background-color: #4CAF50; /* Kolor tła przycisku 1 */
        }
        .menu-item-2 {
            background-color: #2196F3; /* Kolor tła przycisku 2 */
        }
        .menu-item-3 {
            background-color: #FF5722; /* Kolor tła przycisku 3 */
        }
        .menu-item-4 {
            background-color: #FFC107; /* Kolor tła przycisku 4 */
        }
        .menu-item-5 {
            background-color: #9C27B0; /* Kolor tła przycisku 5 */
        }
        .menu-item-6 {
            background-color: #3F51B5; /* Kolor tła przycisku 6 */
        }

        /* Efekt hover dla każdego przycisku */
        .menu-item:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
      
    <div class="menu-container">
        <div class="user-container">
            <p>Witaj na naszej stronie!</p>
        </div>
        
        <div class="top-menu">
            <a href="home.php" class="menu-item menu-item-1">Lista pojazdów</a>
            <a href="#" class="menu-item menu-item-2">Przycisk 2</a>
            <a href="#" class="menu-item menu-item-3">Przycisk 3</a>
        </div>
        
        <div class="bottom-menu">
            <a href="#" class="menu-item menu-item-4">Przycisk 4</a>
            <a href="user.php" class="menu-item menu-item-5">Ustawienia</a>
            <a href="logout.php" class="menu-item menu-item-6">Wyloguj</a>
        </div>
    </div>
</body>
</html>
</body>
</html>