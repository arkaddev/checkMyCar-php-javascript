<?php
// dodawanie nowej czesci
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['partName'])) {
    $car_id = intval($_POST['car_id']);
    $part_name = $conn->real_escape_string($_POST['partName']);
    $part_number = $conn->real_escape_string($_POST['partNumber']);
    $part_price = intval($_POST['partPrice']);
    $part_date = $conn->real_escape_string($_POST['partDate']);
    $part_mileage = intval($_POST['partMileage']);
    $part_next = intval($_POST['partNext']);
    
    $update_query = "INSERT INTO parts (id, name, number, price, exchange_date, kilometers_status, next_exchange_km, car_id, is_replaced) 
                     VALUES (NULL, '$part_name', '$part_number', '$part_price', '$part_date', '$part_mileage', '$part_next', '$car_id', '0')";
  
  $userId = $_SESSION['id'];
  
    if ($conn->query($update_query) === TRUE) {
      log_action($conn, $userId, "part added", "success", "part name: " . $part_name);
        echo json_encode(['status' => 'success', 'message' => 'Część została dodana']);
    } else {
      log_action($conn, $userId, "part updated", "failure", "part name: " . $part_name);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania części']);
    }
    exit();
}


// ustawianie czesci jako wymieniona
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['part_id_service']))  {
    $part_id = intval($_POST['part_id_service']);
    
    $query = "UPDATE parts SET is_replaced = 1 WHERE parts.id = $part_id; ";
  
  $userId = $_SESSION['id'];
   if ($conn->query($query) === TRUE) {
     log_action($conn, $userId, "part replaced", "success", "part id: " . $part_id);
        echo json_encode(['status' => 'success', 'message' => 'Dane zostały zaktualizowane']);
    } else {
     log_action($conn, $userId, "part replaced", "failure", 0);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji danych']);
    }
  
  update_service();
  
    exit();
}

// funkcja wykonuje sie klicknieciu w przycisk ktory potwierdza wymienienie czesci
// sprawdzenie czy są jakies czesci do wymiany, jezeli nie ma to ustawienie service_flag na 0
function update_service(){
    global $conn;

  
  $car_id = intval($_POST['car_id_service']);
  
    $query = "
        SELECT
            parts.id,
            parts.car_id, 
            parts.name, 
            parts.number, 
            parts.price, 
            parts.exchange_date, 
            parts.kilometers_status, 
            parts.next_exchange_km,
            (parts.next_exchange_km + parts.kilometers_status  - cars.mileage) AS when_exchange
        FROM parts 
        JOIN cars ON parts.car_id = cars.id
        WHERE parts.car_id = $car_id AND parts.is_replaced = 0
        ORDER BY when_exchange ASC
    ";
  
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
      
      
      // Sprawdź, czy którakolwiek część ma when_exchange < 0
            $requires_service = false;
            foreach ($data as $part) {
                if ($part['when_exchange'] < 0) {
                    $requires_service = true;
                    break;
                }
            }

            // Ustaw service_flag = 1 jeśli potrzebna jest wymiana
            if (!$requires_service) {
                $updateQuery = "UPDATE cars_info SET service_flag = 0 WHERE car_id = $car_id";
                $conn->query($updateQuery);
            }
      
      
      
       // echo json_encode(['status' => 'success', 'data' => $data]);
       //} else {
       // echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();

}



// usuwanie czesci
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['part_id_delete'])) {
    $part_id = intval($_POST['part_id_delete']); // Zabezpieczenie przed SQL Injection

    // Zapytanie SQL do usunięcia notatki
    $delete_query = "DELETE FROM parts WHERE id = $part_id";

   $userId = $_SESSION['id'];
  
    // Wykonanie zapytania
    if ($conn->query($delete_query) === TRUE) {
      log_action($conn, $userId, "part deleted", "success", "part id: " . $part_id);
        echo json_encode(['status' => 'success', 'message' => 'Część została usunięta']);
    } else {
      log_action($conn, $userId, "part deleted", "failure", "part id: " . $part_id);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas usuwania części']);
    }
    exit();
}


?>