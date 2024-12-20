<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';  
?>