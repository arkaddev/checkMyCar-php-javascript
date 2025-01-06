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
$query = "SELECT * FROM notes WHERE user_id = $userId ORDER BY id DESC";
$result = $conn->query($query);

$notes = []; // Tablica do przechowywania notatek

if ($result->num_rows > 0) {
    $notes = $result->fetch_all(MYSQLI_ASSOC);  // Pobranie wszystkich notatek
}












// dodawanie nowej notatki
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['noteTitle'])) {
    $user_id = intval($_POST['user_id']);
    $note_title = $conn->real_escape_string($_POST['noteTitle']);
    $note_contnet = $conn->real_escape_string($_POST['noteContent']);
   
    $update_query = "INSERT INTO notes (id, title, content, user_id) 
                     VALUES (NULL, '$note_title', '$note_contnet', '$user_id')";
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Notatka została dodana']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania notatki']);
    }
    exit();
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
      
 .user-container button {
            font-size: 22px;
            font-weight: bold;
            color: #333;
      
    
        }

      
      /* Stylizacja dla listy notatek */
ul {
    list-style-type: none;
    padding: 2px;
      font-family: 'Courier New', Courier, monospace;
}

ul li {
    background-color: #f9f9f9;
    margin: 5px 0;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Stylizacja dla tytułu notatki */
ul li strong {
    font-size: 1.2em;
    color: #333;
    padding: 0 15px 0 0;
}
      ul li p {
    font-size: 1.1em;
    color: #5e4e3b; /* Ciemniejszy brąz */
    line-height: 1.5;
    margin-bottom: 10px;
    font-style: italic; /* Kursywa nadająca retro charakter */
}
      

/* Stylizacja dla przycisku usuwania */
.delete-button {
    background-color: #e74c3c;
    color: #fff;
    border: none;
    border-radius: 3px;
    padding: 5px 10px;
    font-size: 0.9em;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.delete-button:hover {
    background-color: #c0392b;
}
      
    </style>
  
  
  
  
</head>
<body>
   <div class="menu-container">
        <div class="user-container">
          <span class="title">Twoje notatki</span>
         <button onclick="menuNewNote()" >
           <i class="fas fa-plus"></i>
           Nowa notatka</button>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
 
  
  
   <?php if (!empty($notes)): ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?>:</strong>
                  <p><?php echo htmlspecialchars($note['content']); ?></p>
                 
                 <?php  echo '<button class="delete-button" onclick="deleteNote(' . htmlspecialchars($note['id']) . ')">Usuń</button>'; ?>
                  
                
                  
                  
                  
                  
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
    
      
        <button type="submit" onclick="addNewNote(<?php echo $userId; ?>)">Zatwierdź</button>
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

  
  
  // Funkcja dodająca nową notatkę do bazy danych
    function addNewNote(userId) {
  
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        const noteTitle = document.getElementById('add-note-title-input').value;
        const noteContent = document.getElementById('add-note-content-input').value;

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `user_id=${userId}&noteTitle=${noteTitle}&noteContent=${noteContent}`
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
        .catch(error => {
            console.error("Wystąpił błąd:", error);
            alert("Wystąpił błąd podczas dodawania notatki.");
        });
    }
  
  
  
  
  
  
  
  
  
  
  
  
  
</script>
