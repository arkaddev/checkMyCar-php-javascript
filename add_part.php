<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Pobranie konfiguracji bazy danych
$config = include('config/db_config.php');

// Połączenie z bazą danych
$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Połączenie nie powiodło się: " . mysqli_connect_error());
}

// Pobranie danych z formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name_form'], $_POST['number_form'], $_POST['price_form'], $_POST['exchange_date_form'], $_POST['kilometers_status_form'], $_POST['next_exchange_km_form'], $_POST['car_id'])) {
    
    $name_form = $conn->real_escape_string($_POST['name_form']);
    $number_form = $conn->real_escape_string($_POST['number_form']);
    $price_form = $conn->real_escape_string($_POST['price_form']);
    $exchange_date_form = $conn->real_escape_string($_POST['exchange_date_form']);
    $kilometers_status_form = $conn->real_escape_string($_POST['kilometers_status_form']);
    $next_exchange_km_form = $conn->real_escape_string($_POST['next_exchange_km_form']);
    $car_id = $conn->real_escape_string($_POST['car_id']);
    


 //Dodanie wpisu do tabeli parts
    $sql = "INSERT INTO parts (id, name, number, price, exchange_date, kilometers_status, next_exchange_km, car_id) 
            VALUES (NULL, '$name_form', '$number_form', '$price_form', '$exchange_date_form', '$kilometers_status_form', '$next_exchange_km_form', '$car_id')";


    if ($conn->query($sql) === TRUE) {
        echo "Nowa część została dodana!";
        echo '<a href="home.php">Powrót do listy samochodów</a>';
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Nieprawidłowe dane.";

}

// Zamknięcie połączenia
mysqli_close($conn);
?>

