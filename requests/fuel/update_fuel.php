<?php
// dodanie tankowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['fuelLiters'])) {
    $car_id = intval($_POST['car_id']);
    $fuel_liters = $conn->real_escape_string($_POST['fuelLiters']);
    $fuel_price = $conn->real_escape_string($_POST['fuelPrice']);
    $fuel_type = $conn->real_escape_string($_POST['fuelType']);
    $fuel_date = $conn->real_escape_string($_POST['fuelDate']);
    $fuel_distance = $conn->real_escape_string($_POST['fuelDistance']);
    $fuel_details = $conn->real_escape_string($_POST['fuelDetails']);

    $fuel_consumption = ($fuel_liters / $fuel_distance) * 100;
    
    $update_query = "INSERT INTO fuel (id, liters, price, fuel_type, refueling_date, distance, details, consumption_100_km, car_id) 
                     VALUES (NULL, '$fuel_liters', '$fuel_price', '$fuel_type', '$fuel_date', '$fuel_distance', '$fuel_details', $fuel_consumption, '$car_id')";
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Tankowanie zostało dodane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania tankowania']);
    }
    exit();
}


// usuwanie tankowania
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fuel_id_history']))  {
    $fuel_id = intval($_POST['fuel_id_history']);
    
    $query = "DELETE FROM fuel WHERE id = $fuel_id";
  
   if ($conn->query($query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Dane zostały zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji danych']);
    }
    exit();
}


// wyswietlenie historii tankowan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_history']))  {
    $car_id = intval($_POST['car_id_history']);
    
    $query = "SELECT
    id,
    car_id,
    liters, 
    price, 
    fuel_type, 
    refueling_date, 
    distance,
    details,
    ROUND((liters/distance)*100,2) AS average_fuel_consumption
    FROM fuel WHERE car_id = $car_id 
    ORDER BY refueling_date DESC
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

// wyswietlenie wykresu tankowan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id_history_chart']))  {
    $car_id = intval($_POST['car_id_history_chart']);
    
    $query = "SELECT
    id,
    car_id,
    liters, 
    price, 
    fuel_type, 
    refueling_date, 
    distance,
    details,
    consumption_100_km
    FROM fuel 
    WHERE car_id = $car_id AND consumption_100_km > 0
    ORDER BY refueling_date ASC
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