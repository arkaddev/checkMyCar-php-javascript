<?php
require 'config/session.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">
   
  <style>
       
        
      
        .menu-container {
   
        border-radius: 5px;
    padding: 0px 0px 10px 0px;
    width: 90vw; /* Kontener zajmuje 90% szerokości ekranu */
    max-width: 700px; /* Ograniczenie maksymalnej szerokości */
    margin: 50px auto; /* Wyśrodkowanie */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
      
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    
    background-color: #fff; /* Białe tło dla lepszego efektu */
}

      
      
        
        .top-menu, .bottom-menu {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

      
      
        .menu-item {
            color: white;
            width: 200px;
            height: 200px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 500;
            display: flex;
            justify-content: center;
            margin: 0 10px; /* Odstęp między przyciskami */
            text-align: center;
            text-decoration: none;
            flex-direction: column;
            align-items: center;
            transition: background-color 0.3s ease; /* Efekt przejścia */
            box-sizing: border-box; /* Uwzględnia padding w szerokości i wysokości */
        }
      
     
        .menu-item i {
            font-size: 40px; /* Rozmiar ikon */
            margin-bottom: 10px; /* Odstęp poniżej ikony */
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

      
      /* Efekt hover */
        .menu-item:hover {
      opacity: 0.8;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .menu-item:active {
            transform: scale(0.95);
            box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.3);
        }
      
    </style>
</head>
<body>
      
    <div class="menu-container">
        <div class="user-container">
          
          
          <span class="title">
            <a href="menu.php" class=""><i class="fas fa-home"></i></a>
             &nbsp; Menu</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          &nbsp;
          <a href="logout.php" title="Wyloguj">
          <i class="fas fa-sign-out-alt"></i></a>
          
          </p>
           
      </div>
        
        <div class="top-menu">
            <a href="list.php" class="menu-item menu-item-1">
                <i class="fas fa-car"></i>
                Lista pojazdów
            </a>
            <a href="" class="menu-item menu-item-6">
                <i class="fas fa-tools"></i>
                Serwis
            </a>
            <a href="fuel.php" class="menu-item menu-item-3">
                <i class="fas fa-gas-pump"></i>
                Spalanie
            </a>
        </div>
        
        <div class="bottom-menu">
            <a href="statistic.php" class="menu-item menu-item-4">
                <i class="fas fa-chart-line"></i>
                Statystyki
            </a>
            <a href="user.php" class="menu-item menu-item-5">
                <i class="fas fa-user-cog"></i>
                Ustawienia
            </a>
            <a href="note.php" class="menu-item menu-item-2">
                <i class="fas fa-book"></i>
                Notatnik
            </a>
        </div>
    
     
  </div>
</body>
</html>
