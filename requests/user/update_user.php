<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    // Sprawdzenie, czy nowe hasło jest ustawione
    if (empty($_POST['new_password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Proszę wprowadzić nowe hasło']);
        exit();
    }

    // Hasło wprowadzone przez użytkownika
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Zapytanie SQL do aktualizacji hasła
    $update_query = "UPDATE users SET password = '$hashed_password' WHERE username = '$username'";
    
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Hasło zostało zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji hasła: ' . $conn->error]);
    }
    exit();
}



// dodawanie nowego pojazdu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['carModel'])) {
  
    $user_id = intval($_POST['user_id']);
    $car_model = $conn->real_escape_string($_POST['carModel']);
    $car_year = intval($_POST['carYear']);
    $car_engine = $conn->real_escape_string($_POST['carEngine']);
    $car_kw = $conn->real_escape_string($_POST['carKw']);
    $car_oil = $conn->real_escape_string($_POST['carOil']);
    $car_oil_filter = $conn->real_escape_string($_POST['carOilFilter']);
    $car_air_filter = $conn->real_escape_string($_POST['carAirFilter']);
    $car_tires = $conn->real_escape_string($_POST['carTires']);
    $car_other_info = $conn->real_escape_string($_POST['carOtherInfo']);
    
  
  $update_query = "INSERT INTO cars (id, model, year, mileage, insurance, technical_inspection, user_id) VALUES (NULL, '$car_model', '$car_year', 0, 0, 0, '$user_id');
  
 
 INSERT INTO cars_info (id, engine_number, km_kw, oil_number, oil_filter_number, air_filter_number, tires, other_info, service_flag, car_id) VALUES (NULL, '$car_engine', '$car_kw', '$car_oil', '$car_oil_filter', '$car_air_filter', '$car_tires', '$car_other_info', 0, LAST_INSERT_ID());

 ";
  
    if ($conn->multi_query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Pojazd został dodany']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas dodawania pojazdu: ' . $conn->error]);
    }
    
   
    exit();
}


// usuwanie pojazdu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    $car_id = intval($_POST['car_id']); // Zabezpieczenie przed SQL Injection

    // Zapytanie SQL do usunięcia notatki
    $delete_query = "DELETE FROM cars_info WHERE car_id = $car_id;
    
    DELETE FROM cars WHERE id = $car_id";

    // Wykonanie zapytania
    if ($conn->multi_query($delete_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Pojazd został usunięty']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Błąd podczas usuwania pojazdu: ' . $conn->error]);
    }
    exit();
}

?>