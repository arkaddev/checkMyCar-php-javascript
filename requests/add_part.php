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
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Część została dodana']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania części']);
    }
    exit();
}


// ustawianie czesci jako wymieniona
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['part_id_service']))  {
    $part_id = intval($_POST['part_id_service']);
    
    $query = "UPDATE parts SET is_replaced = 1 WHERE parts.id = $part_id; ";
   if ($conn->query($query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dane zostały zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji danych']);
    }
    exit();
}

?>