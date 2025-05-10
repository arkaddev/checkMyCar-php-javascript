<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_info']))  {
    $car_id = intval($_POST['car_id_info']);
    
    $query = "SELECT * FROM cars_info WHERE car_id = $car_id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}

// historia czesci
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_history']))  {
    $car_id = intval($_POST['car_id_history']);
  
  
  $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
  $limit = 10;
  $offset = ($page - 1) * $limit;

  $query = "SELECT * FROM parts WHERE car_id = $car_id ORDER BY kilometers_status ASC LIMIT $limit OFFSET $offset";

  
    //$query = "SELECT * FROM parts WHERE car_id = $car_id ORDER BY kilometers_status ASC";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}
  
// serwis czesci
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_service']))  {
    $car_id = intval($_POST['car_id_service']);
    
    //$query = "SELECT * FROM parts WHERE car_id = $car_id";
    
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
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych o samochodzie']);
    }
    exit();
}
?>
