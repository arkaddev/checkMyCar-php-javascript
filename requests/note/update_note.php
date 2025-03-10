<?php

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


// usuwanie notatki
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_id'])) {
    $note_id = intval($_POST['note_id']); // Zabezpieczenie przed SQL Injection

    // Zapytanie SQL do usunięcia notatki
    $delete_query = "DELETE FROM notes WHERE id = $note_id";

    // Wykonanie zapytania
    if ($conn->query($delete_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Notatka została usunięta']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas usuwania notatki']);
    }
    exit();
}

?>