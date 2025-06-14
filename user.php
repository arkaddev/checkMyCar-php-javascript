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

// Sprawdzamy, czy połączenie się powiodło
/*
if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
} else {
    echo "Połączenie z bazą danych powiodło się!";
}
*/
if (!$conn) {
    $db_message = "Brak połączenia: " . mysqli_connect_error();
} else {
    $db_message = "Połączono.";
}




// Sprawdzamy rolę użytkownika
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  




// Zapytanie SQL, aby pobrać dane z tabeli 'cars'
$query = "
    SELECT 
        c.id,
        c.model, 
        c.year, 
        c.user_id
    FROM 
        cars c
  
    
";

        
        //Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
if ($user_role !== 'admin') {
    $query .= " WHERE user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
 
}
    $query .= " ORDER BY c.model ASC";

$result = mysqli_query($conn, $query);















         

// Zmienna przechowująca dane sesji
$username = $_SESSION['username'];








require 'requests/user/update_user.php';

// Zamykamy połączenie
mysqli_close($conn);
?> 
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
  
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="css/style.css">
   
  <script src="js/user/userActions.js"></script>
    <style>
        
        
     /* Styl zakładek */
        .tabs {
            display: flex;
        }

        .tabs a {
            padding: 10px 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ccc;
            border-bottom: none;
            background: #f9f9f9;
            margin-right: 5px;
        }

        .tabs a:hover {
            background: #eee;
        }

        .tabs a:target {
            background: #fff;
            font-weight: bold;
        }
      
      

      
       /* Styl treści */
        .tab-content {
            display: none;
            border: 1px solid #ccc;
            background: #fff;
        }

        .tab-content:target {
            display: block;
        }

        /* Domyślnie otwarta pierwsza zakładka */
        .tab-content:first-of-type {
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
             &nbsp; Ustawienia</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          &nbsp;
          <a href="logout.php" title="Wyloguj">
          <i class="fas fa-sign-out-alt"></i></a>
          
          </p>
           
      </div>
     
     
     <div class="tabs">
        <a href="#tab-cars">Samochody</a>
        <a href="#tab-data">Dane użytkownika</a>
        <a href="#tab-password">Hasło</a>
    </div>
     
     
     <div id="tab-data" class="tab-content">  
     <h3>Dane użytkownika:</h3>
       
   <?php if (isset($_SESSION['id'])): ?>
        <p>Id: <?= htmlspecialchars($_SESSION['id']) ?></p>
    <?php else: ?>
        <p>Nie przypisano id.</p>
    <?php endif; ?>
  
    <?php if (isset($_SESSION['role'])): ?>
    <p>Rola: <?= htmlspecialchars($_SESSION['role']) ?></p>
    <?php else: ?>
        <p>Nie przypisano roli.</p>
    <?php endif; ?>
     
     
     <p>Połączenie z bązą danych: <?php echo htmlspecialchars($db_message); ?></p>
     </div>
       
       
       
       
     <div id="tab-password" class="tab-content">  
       
       
       
       
       
     <h3>Hasło:</h3>
     
     
     
  <label for="new_password">Nowe hasło:</label>
  <input type="password" id="new_password" name="new_password" required>
   <button onclick="newPassword()">Zmien hasło</button>
     </div>
     
     
     
     
 <div id="tab-cars" class="tab-content">    
 <h3>Samochody:</h3>
     
<button class="add-car-button" onclick="menuAddCar()">Nowy samochód</button>
     
   <!-- Tabela z danymi z bazy -->
        <table>
            <thead>
                <tr>
                    <th>Model</th>
                    <th>Rok</th>
                  
                    <th>Akcja</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Wyświetlanie danych z tabeli 'cars'
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                        
                      
                      echo '<td><button class="delete-car-button" onclick="deleteCar(' . htmlspecialchars($row['id']) . ')">Usuń</button></td>';

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
 
  
  
    
   
  </div>
  
  
  
  
  
  
  <form id="add-car">
        <h2>Dodaj nowy samochód:</h2>
    
        <label for="add-car">Nazwa samochodu:</label>
        <input type="text" id="add-car-model-input" name="" required><br>

        <label for="add-car">Rok produkcji:</label>
        <input type="text" id="add-car-year-input" name="" required><br>
      
        <label for="add-car">Silnik:</label>
        <input type="text" id="add-car-engine-input" name="" step="0.01" required><br>

        <label for="add-car">Moc (kw/km):</label><br>
        <input type="text" id="add-car-kw-input" name="" required><br>

        <label for="add-car">Olej:</label><br>
        <input type="text" id="add-car-oil-input" name="" required><br>
      
        <label for="add-car">Filtr oleju:</label>
        <input type="text" id="add-car-oilfilter-input" name="" required><br>
    
        <label for="add-car">Filtr powietrza:</label>
        <input type="text" id="add-car-airfilter-input" name="" required><br>
    
        <label for="add-car">Opony:</label>
        <input type="text" id="add-car-tires-input" name="" required><br>
          
        <label for="add-car">Inne informacje:</label>
        <input type="text" id="add-car-otherinfo-input" name="" required><br>
      
        <button type="submit" onclick="addCar(<?php echo $userId; ?>)">Zatwierdź</button>
        <button type="button" onclick="closeMenuAddCar()">Anuluj</button>
    </form>
  </div>
  
  
  
</body>
</html>