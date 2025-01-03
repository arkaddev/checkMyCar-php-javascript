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
}

// Zmienna przechowująca dane sesji
$userId = $_SESSION['id'];

// Zapytanie do bazy
$query = "SELECT * FROM notes WHERE user_id = $userId";
$result = $conn->query($query);

$notes = []; // Tablica do przechowywania notatek

if ($result->num_rows > 0) {
    $notes = $result->fetch_all(MYSQLI_ASSOC);  // Pobranie wszystkich notatek
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje notatki</title>
  
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
      min-height: 400px;
   
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
        
       
     
      
    




/* glowne menu dodawania */
       #new-note {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            width: 300px;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

#new-note button{
            display: block;
            width: 100%;
            margin: 5px 0;
        }
    
        #new-note input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
        }

      
      
      #overlay {
    display: none; /* Domyślnie ukryta */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Czarny kolor z 50% przezroczystością */
    z-index: 999; /* Nakładka nad innymi elementami, ale pod oknem modalnym */
}
      
      
    </style>
  
  
  
  
</head>
<body>
   <div class="menu-container">
        <div class="user-container">
          <span class="title">Twoje notatki</span>
         <button onclick="menuNewNote()">Nowa notatka</button>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
 
  
  
   <?php if (!empty($notes)): ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?>:</strong> 
                    <?php echo htmlspecialchars($note['content']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nie masz żadnych notatek.</p>
    <?php endif; ?>
  
  
      
  <div id="overlay"></div>
      
    
      <form id="new-note">
        <h2>Nowa notatka:</h2>
    
        <label for="add-note">Tytuł:</label>
        <input type="text" id="add-note-title-input" name="" required><br>

        <label for="add-note">Treść:</label>
          <textarea id="add-note-content-input" name="message" rows="4" cols="40" placeholder="Wpisz swoją wiadomość tutaj..."></textarea><br>
    
      
        <button type="submit" onclick="addNewNote()">Zatwierdź</button>
        <button type="button" onclick="closeMenuNewNote()">Anuluj</button>
    </form>
  
      
      
  
  
  </div>
</body>
</html>

<script>
function menuNewNote() {
        document.getElementById('new-note').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

   
    function closeMenuNewNote() {
        document.getElementById('new-note').style.display = 'none';
   document.getElementById('overlay').style.display = 'none';
    }

</script>
