<?php
function log_action($conn, $user_id, $action, $status, $details = null, $new_value = null) {
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, status, details, new_value) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $action, $status, $details, $new_value);
    $stmt->execute();
    $stmt->close();
}
?>