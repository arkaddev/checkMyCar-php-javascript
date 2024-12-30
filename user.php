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
        
       
    </style>
  
</head>
<body>
  
  
   <div class="menu-container">
        <div class="user-container">
          <span class="title">Panel użytkownika</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
     
     
     
     
  
  <label for="new_password">Nowe hasło:</label>
  <input type="password" id="new_password" name="new_password" required>
   <button onclick="newPassword()">Zmien hasło</button>
  
 
  
  
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
  
  
    </script>
