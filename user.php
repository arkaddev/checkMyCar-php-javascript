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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    // Sprawdzenie, czy nowe hasło jest ustawione
    if (empty($_POST['new_password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę wprowadzić nowe hasło']);
        exit();
    }

    // Hasło wprowadzone przez użytkownika
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Zapytanie SQL do aktualizacji hasła
    $update_query = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
    
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Hasło zostało zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji hasła: ' . $conn->error]);
    }
    exit();
}


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
          <span class="title">Panel użytkownika</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
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

        <label for="add-car">Moc:</label><br>
        <input type="text" id="add-car-kw-input" name="" required><br>

        <label for="add-car">Olej:</label><br>
        <input type="text" id="add-car-oil-input" name="" required><br>
      
        <label for="add-car">Filtr oleju:</label>
        <input type="text" id="add-car-olifilter-input" name="" required><br>
    
        <label for="add-car">Filtr powietrza:</label>
        <input type="text" id="add-car-airfilter-input" name="" required><br>
      
        <button type="submit" onclick="addCar()">Zatwierdź</button>
        <button type="button" onclick="closeMenuAddCar()">Anuluj</button>
    </form>
  </div>
  
  
  
</body>
</html>

<script>
  
  
  
   function newPassword() {
    var newPassword = document.getElementById('new_password').value;

    if (!newPassword) {
        alert("Proszę wprowadzić nowe hasło");
        return;
    }

    fetch("", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `new_password=${encodeURIComponent(newPassword)}`
    })
    .then(response => response.json()) // Parsowanie odpowiedzi jako JSON
    .then(data => {
        if (data.status === "success") {
            alert(data.message); // Wyświetlenie komunikatu sukcesu
            location.reload(); // Odświeżenie strony
  
        } else {
            alert(data.message); // Wyświetlenie komunikatu błędu
        }
    })
    alert("Hasło zostało zmienione.");
}
  
  function deleteCar(carId) {
   
    alert("Opcja dostępna tylko dla administratora.");
}
  
  
  
  
  
  
  // Funkcja otwierająca okno z dodwaniem samochodu
    function menuAddCar() {
        document.getElementById('add-car').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z dodawaniem samochodu
    function closeMenuAddCar() {
  
        document.getElementById('add-car').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
    }
  
  
</script>
