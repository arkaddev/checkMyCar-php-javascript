<?php

// aktualizacja przebiegu

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['mileage'])) {
    $car_id = intval($_POST['car_id']);
    $mileage = intval($_POST['mileage']);


    $update_query = "UPDATE cars SET mileage = $mileage WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        $current_date = date('Y-m-d');
        $insert_query = "INSERT INTO mileages (car_id, mileage, date) VALUES ($car_id, $mileage, '$current_date')";

        $userId = $_SESSION['id'];
        if ($conn->query($insert_query) === TRUE) {
            
            log_action($conn, $userId, "mileage updated", "car id: " . $car_id, $mileage);
          
          echo json_encode(['status' => 'success', 'message' => 'Przebieg został zaktualizowany']);
        } else {
            echo json_encode(['status' => 'warning', 'message' => 'Przebieg zaktualizowany, ale nie zapisano historii']);
        }
    } else {
        log_action($conn, $userId, "mileage updated", "failure", 0);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przebiegu']);
    }
set_service();
    exit();

}
// aktualizacja ubezpieczenia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['insurance'])) {
    $car_id = intval($_POST['car_id']);
    $insurance = $conn->real_escape_string($_POST['insurance']);
    
    $update_query = "UPDATE cars SET insurance = '$insurance' WHERE id = $car_id";
   
  $userId = $_SESSION['id'];
  if ($conn->query($update_query) === TRUE) {
            
            log_action($conn, $userId, "insurance updated", "car id: " . $car_id, $insurance);
        echo json_encode(['status' => 'success', 'message' => 'Ubezpieczenie zostało zaktualizowane']);
    } else {
    log_action($conn, $userId, "insurance updated", "failure", 0);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji ubezpieczenia']);
    }
    exit();
}

// aktualizacja przegladu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['inspection'])) {
    $car_id = intval($_POST['car_id']);
    $inspection = $conn->real_escape_string($_POST['inspection']);
    
    $update_query = "UPDATE cars SET technical_inspection = '$inspection' WHERE id = $car_id";
    
  $userId = $_SESSION['id'];
  if ($conn->query($update_query) === TRUE) {
   
            log_action($conn, $userId, "inspection updated", "car id: " . $car_id, $inspection);
        echo json_encode(['status' => 'success', 'message' => 'Przegląd został zaktualizowany']);
    } else {
    log_action($conn, $userId, "inspection updated", "failure", 0);
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przeglądu']);
    }
    exit();
}


// ustawienie service_flag na 1 jezeli czesc jest do wymiany
function set_service(){
    global $conn;

    $car_id = intval($_POST['car_id']);
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
            if ($requires_service) {
                $updateQuery = "UPDATE cars_info SET service_flag = 1 WHERE car_id = $car_id";
                $conn->query($updateQuery);
            }
      
      
      
        //echo json_encode(['status' => 'success', 'data' => $data]);
      // } else {
        //echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();

}

?>