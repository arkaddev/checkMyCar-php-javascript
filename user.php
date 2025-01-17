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

   
    <style>
        /* Podstawowe style strony */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            margin: 0;
            padding: 0;
            abackground-color: #f4f4f4;
            background: linear-gradient(to bottom right, #e3f2fd, #bbdefb); /* Tło gradientowe */
           
        }
        
      
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

      
      
.user-container {
    width: 100%; /* Szerokość taka sama jak menu-container */
    padding: 5px 30px; /* Wewnętrzny padding */
    text-align: right; /* Wyśrodkowanie tekstu */
    box-sizing: border-box; /* Uwzględnienie paddingu w szerokości */
   background-color: silver;
      border-radius: 5px;
      
     
         
            display: flex;
            justify-content: space-between;
            align-items: center;
      
}
   
       .title {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }
      
      .username {
    font-weight: bold;
    color: #4caf50;
    font-size: 18px;
   
}
        
 
      
            /* Stylowanie tabeli */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0056b3;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
     
      
      
      
      
      
      /* Stylizacja dla przycisku usuwania samochodu */
.delete-car-button {
    background-color: #2196F3;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 5px 10px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-car-button:hover {
    background-color: #3F51B5;
}
      
      
      
            /* KOPIA Z FUEL PHP   Dodawanie tankowania, wyglad okna */
      
    
       /* Nakładka tła */
#overlay {
    display: none; /* Domyślnie ukryta */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Czarny z przezroczystością */
    z-index: 999; /* Pod modalami */
    transition: background 0.3s ease;
}

       /* glowne menu dodawania*/
        #add-car{
           display: none; /* Domyślnie ukryte */
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -20%);
    width: 40%;
    max-width: 900px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0px 4px 16px rgba(0, 0, 0, 0.1);
    padding: 10px;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
        }
      
     
      
      
      /* Przycisk w formularzach */
#add-car button {
    display: block;
    width: 100%;
    margin: 10px 0;
    padding: 12px 20px;
    background-color: #0056b3;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
     #add-car button:hover{
    background-color: #004085;
    transform: translateY(-2px);
}
      
      
/* Styl dla okien modalnych - pola formularzy */
form input[type="number"], form input[type="date"], form input[type="text"], form select {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

form input[type="number"]:focus, form input[type="date"]:focus, form input[type="text"]:focus, form select:focus {
    border-color: #0056b3;
    outline: none;
}

/* Przyciski anulowania */
form button[type="button"] {
    background-color: #e74c3c;
}

form button[type="button"]:hover {
    background-color: #c0392b;
}
      
      
      
    </style>
  
</head>
<body>
  
  
   <div class="menu-container">
        <div class="user-container">
          <span class="title">Panel użytkownika</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
     
     
     
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
     
     
     
     <h3>Hasło:</h3>
     
     
     
  <label for="new_password">Nowe hasło:</label>
  <input type="password" id="new_password" name="new_password" required>
   <button onclick="newPassword()">Zmien hasło</button>
  
 <h3>Samochody:</h3>
     
<button class="add-car-button" onclick="menuAddNewCar()">Nowy samochód</button>
     
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
      
        <button type="submit" onclick="addNewCar()">Zatwierdź</button>
        <button type="button" onclick="closeMenuNewCar()">Anuluj</button>
    </form>
  
  
  
  
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
    function menuAddNewCar() {
        document.getElementById('add-car').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Funkcja zamykająca okno z dodawaniem samochodu
    function closeMenuNewCar() {
  
        document.getElementById('add-car').style.display = 'none';
  document.getElementById('overlay').style.display = 'none';
    }
  
  
</script>
