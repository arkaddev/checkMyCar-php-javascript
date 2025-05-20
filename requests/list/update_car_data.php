<?php

// aktualizacja przebiegu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['mileage'])) {
    $car_id = intval($_POST['car_id']);
    $mileage = intval($_POST['mileage']);

    $update_query = "UPDATE cars SET mileage = $mileage WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        $current_date = date('Y-m-d H:i:s');
        $insert_query = "INSERT INTO mileages (car_id, mileage, date) VALUES ($car_id, $mileage, '$current_date')";

        if ($conn->query($insert_query) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Przebieg został zaktualizowany']);
        } else {
            echo json_encode(['status' => 'warning', 'message' => 'Przebieg zaktualizowany, ale nie zapisano historii']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przebiegu']);
    }
    exit();
}

// aktualizacja ubezpieczenia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['insurance'])) {
    $car_id = intval($_POST['car_id']);
    $insurance = $conn->real_escape_string($_POST['insurance']);
    
    $update_query = "UPDATE cars SET insurance = '$insurance' WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Ubezpieczenie zostało zaktualizowane']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji ubezpieczenia']);
    }
    exit();
}

// aktualizacja przegladu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['inspection'])) {
    $car_id = intval($_POST['car_id']);
    $inspection = $conn->real_escape_string($_POST['inspection']);
    
    $update_query = "UPDATE cars SET technical_inspection = '$inspection' WHERE id = $car_id";
    if ($conn->query($update_query) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Przegląd został zaktualizowany']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Błąd podczas aktualizacji przeglądu']);
    }
    exit();
}
?>
