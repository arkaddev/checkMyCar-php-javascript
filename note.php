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


require 'requests/note/update_note.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twoje notatki</title>
  
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
<link rel="stylesheet" href="css/style.css">
   
    <style>
     

   /* kontener glowny */
.main-container {
    max-width: 1100px; /* Ograniczenie maksymalnej szerokości */
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
      


      
    </style>
  
  
  
  
</head>
<body>
   <div class="main-container">
        <div class="user-container">
          <span class="title">Twoje notatki</span>
         
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
 
  
     <form>
      <button type="button" onclick="menuAddNote()">Nowa notatka</button>
     </form>
     
     
  
   <?php if (!empty($notes)): ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <strong><?php echo htmlspecialchars($note['title']); ?>:</strong>
                  <p><?php echo htmlspecialchars($note['content']); ?></p>
                 
                 <?php  echo '<button type="button" class="delete-note-button" onclick="deleteNote(' . htmlspecialchars($note['id']) . ')">Usuń</button>'; ?>
                  
                
                  
                  
                  
                  
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nie masz żadnych notatek.</p>
    <?php endif; ?>
  
  
      
  <div id="overlay"></div>
      
    
      <form id="note-add-note">
        <h2>Nowa notatka:</h2>
    
        <label for="add-note">Tytuł:</label>
        <input type="text" id="add-note-title-input" name="" required><br>

        <label for="add-note">Treść:</label>
          <textarea id="add-note-content-input" name="message" rows="4" cols="80" placeholder="Wpisz swoją wiadomość tutaj..."></textarea><br>
    
      
        <button type="submit" onclick="addNote(<?php echo $userId; ?>)">Zatwierdź</button>
        <button type="button" onclick="closeMenuAddNote()">Anuluj</button>
    </form>
  
      
      
  
  
  </div>
</body>
</html>

<script>
function menuAddNote() {
        document.getElementById('note-add-note').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

   
function closeMenuAddNote() {
        document.getElementById('note-add-note').style.display = 'none';
   document.getElementById('overlay').style.display = 'none';
    }

  
  
  // Funkcja dodająca nową notatkę do bazy danych
    function addNote(userId) {
  
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
  
  
  
  
  
    function deleteNote(noteId) {
  
        event.preventDefault(); // Zapobiega domyślnemu działaniu formularza

        // Wysłanie zapytania POST do serwera
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `note_id=${noteId}`
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
            alert("Wystąpił błąd podczas usuwania notatki.");
        });
  
  
  
  }
  
  
  
  
</script>