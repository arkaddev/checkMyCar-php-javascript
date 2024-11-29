<?php
session_start();

// Odczytaj dane z pliku JSON
$users_json = file_get_contents('data/users.json');
$users = json_decode($users_json, true);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Sprawdź, czy użytkownik istnieje w danych
    foreach ($users as $user) {
        if ($user["username"] === $username && $user["password"] === $password) {
        
          $_SESSION["username"] = $username; // Ustaw zmienną sesji
          $_SESSION["role"] = $user["role"];
          header("Location: home.php"); // Przekieruj na stronę powitalną
          exit();
        }
    }

    // Jeśli nie udało się zalogować, wyświetl komunikat
    $error = "Błędna nazwa użytkownika lub hasło.";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #0078D7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #005A9E;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Logowanie</h1>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Nazwa użytkownika" required>
            <input type="password" name="password" placeholder="Hasło" required>
            <button type="submit">Zaloguj</button>
        </form>
        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>