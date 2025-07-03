<?php
require 'helpers/logger.php';

session_start();



// Parametry połączenia
$config = include('config/db_config.php');
// Tworzymy połączenie
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);


// Sprawdzamy, czy połączenie się powiodło
if (!$conn) {
    // Jeśli połączenie nie uda się, wyświetli się błąd
    //die("Połączenie nie powiodło się: " . mysqli_connect_error());
} else {
    // Jeśli połączenie jest udane, wyświetlamy komunikat
    //echo "Połączenie z bazą danych powiodło się!";
}


// Jeśli formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $user_username = $_POST["username"];
    $user_password = $_POST["password"];
    
  // Przygotowanie zapytania
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);

    // Sprawdzamy, czy zapytanie zostało przygotowane poprawnie
    if ($stmt === false) {
        die("Błąd w przygotowaniu zapytania: " . $conn->error);
    }

    // Zwiąż parametr z zapytaniem (parametr typu string)
    $stmt->bind_param("s", $user_username);  // "s" oznacza typ string
    $stmt->execute();

    // Pobierz wynik
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Sprawdź, czy użytkownik istnieje i czy hasło jest poprawne
    if ($user && password_verify($user_password, $user['password'])) {
        // Hasło jest poprawne, ustaw zmienne sesji
        $_SESSION["username"] = $user['username'];
        $_SESSION["role"] = $user['role'];
        $_SESSION["id"] = $user['id'];
      
      // log
       log_action($conn, $user['id'], "login", "success", "", "");

        // Przekierowanie na stronę powitalną
        header("Location: menu.php");
        exit();
    } else {
      log_action($conn, $user['id'], "login", "failure", "", "");
        // Jeśli dane logowania są niepoprawne
        $error = "Błędna nazwa użytkownika lub hasło.";
    }
}


// Zamykamy połączenie
mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Auto Serwis Online</title>
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
      width: 100%;
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
      
     @media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 15px;
    }

    input[type="text"], input[type="password"] {
        font-size: 1em;
        padding: 12px;
    }

    button {
        width: 100%;
        font-size: 1em;
    }
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