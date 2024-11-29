<?php
session_start();
// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj</title>
</head>
<body>
    <h1>Witaj, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Jesteś zalogowany.</p>
    <a href="logout.php">Wyloguj się</a>
</body>
</html>
