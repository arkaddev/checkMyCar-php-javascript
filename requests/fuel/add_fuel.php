<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['fuelLiters'])) {
    $car_id = intval($_POST['car_id']);
    $fuel_liters = $conn->real_escape_string($_POST['fuelLiters']);
    $fuel_price = $conn->real_escape_string($_POST['fuelPrice']);
    $fuel_type = $conn->real_escape_string($_POST['fuelType']);
    $fuel_date = $conn->real_escape_string($_POST['fuelDate']);
    $fuel_distance = $conn->real_escape_string($_POST['fuelDistance']);
    $fuel_details = $conn->real_escape_string($_POST['fuelDetails']);
    
    $update_query = "INSERT INTO fuel (id, liters, price, fuel_type, refueling_date, distance, details, car_id) 
                     VALUES (NULL, '$fuel_liters', '$fuel_price', '$fuel_type', '$fuel_date', '$fuel_distance', '$fuel_details', '$car_id')";
  
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Tankowanie zostało dodane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania tankowania']);
    }
    exit();
}
?>