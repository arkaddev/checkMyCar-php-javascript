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
    aborder: 2px solid #333; /* Ramka wokół całego menu */
        border-radius: 5px;
    apadding: 20px;
    width: 90vw; /* Kontener zajmuje 90% szerokości ekranu */
    max-width: 800px; /* Ograniczenie maksymalnej szerokości */
    margin: 50px auto; /* Wyśrodkowanie */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
      
      border: 2px solid gray; /* Zmieniony kolor ramki (zielony) */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Dodanie cienia */
    background-color: #fff; /* Białe tło dla lepszego efektu */
}

.user-container {
    width: 100%; /* Szerokość taka sama jak menu-container */
    aborder: 2px solid #333; /* Ramka wokół nagłówka */
    padding: 5px 0; /* Wewnętrzny padding */
    text-align: center; /* Wyśrodkowanie tekstu */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
   background-color: silver;
      border-radius: 5px;
      
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
            width: 250px; /* Stała szerokość */
            height: 250px; /* Stała wysokość */
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s ease; /* Efekt przejścia */
            box-sizing: border-box; /* Uwzględnia padding w szerokości i wysokości */
        }

        /* Kolory przycisków */
        .menu-item-1 {
            background-color: #3F51B5; /* Kolor tła przycisku 1 */
        }
        .menu-item-2 {
            background-color: #2196F3; /* Kolor tła przycisku 2 */
        }
        .menu-item-3 {
            background-color: #3F51B5; /* Kolor tła przycisku 3 */
        }
        .menu-item-4 {
            background-color: #2196F3; /* Kolor tła przycisku 4 */
        }
        .menu-item-5 {
            background-color: #3F51B5; /* Kolor tła przycisku 5 */
        }
        .menu-item-6 {
            background-color: #2196F3; /* Kolor tła przycisku 6 */
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
            <a href="#" class="menu-item menu-item-2">Spalanie</a>
            <a href="#" class="menu-item menu-item-3">Notatnik</a>
        </div>
        
        <div class="bottom-menu">
            <a href="#" class="menu-item menu-item-4">Statystyki</a>
            <a href="user.php" class="menu-item menu-item-5">Ustawienia</a>
            <a href="logout.php" class="menu-item menu-item-6">Wyloguj</a>
        </div>
    </div>
</body>
</html>
