<?php


session_start();


// Sprawdź, czy użytkownik jest zalogowany


if (!isset($_SESSION["username"])) {


    header("Location: login.php");


    exit();


}





$userId = $_SESSION['id'];





$config = include('config/db_config.php');


// Tworzymy połączenie


$conn = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);





?>





<!DOCTYPE html>


<html lang="pl">


<head>


    <meta charset="UTF-8">


    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Witaj</title>


  


   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">


  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">





   <link rel="stylesheet" href="css/style.css">


 


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>


    


    


    </style>


  


</head>


<body>


  <div id="overlay"></div>


  


   <div class="main-container">


        <div class="user-container">


          


          


          <span class="title">


            <a href="menu.php" class=""><i class="fas fa-home"></i></a>


             &nbsp; Spalanie</span>


            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>


          &nbsp;


          <a href="logout.php" title="Wyloguj">


          <i class="fas fa-sign-out-alt"></i></a>


          


          </p>


           


      </div>


     


    


</div>





 


</body>


</html>
