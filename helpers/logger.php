<?php
function log_action($conn, $user_id, $action, $details = null, $new_value = null) {
    $stmt = $conn->prepare("INSERT INTO logs (user_id, action, details, new_value) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $action, $details, $new_value);
    $stmt->execute();
    $stmt->close();
}
?>