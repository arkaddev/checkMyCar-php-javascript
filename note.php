<?php

require 'config/session.php';
require 'config/db_connection.php';


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
   
  <script src="js/note/noteActions.js"></script>
  
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
