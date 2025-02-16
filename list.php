<?php
require 'config/session.php';
require 'config/db_connection.php';

require 'helpers/functions.php';
?>


<?php

$query = "
    SELECT 
        c.id, 
        c.model, 
        c.year, 
        c.user_id, 
        c.insurance, 
        c.technical_inspection, 
        c.mileage,
        IFNULL(ROUND(SUM(f.liters)*100 / SUM(f.distance), 2), 'Brak danych') AS average_fuel_consumption
        
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
      
        
    GROUP BY 
        c.id, c.model, c.year, c.user_id, c.insurance, c.technical_inspection, c.mileage

";




if ($user_role !== 'admin') {
    $query .= " HAVING user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
}

 

$result = mysqli_query($conn, $query);

    

// Zamykamy połączenie
mysqli_close($conn);
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
  
  
  <style>
  /* kontener glowny */
.main-container {
    max-width: 1100px; /* Ograniczenie maksymalnej szerokości */
}

     .insurance-expired {
       background-color: #f8d7da;
        
    }
    .insurance-warning {
        background-color: #fff3cd;
        
    }
    .insurance-valid {
        background-color: #d4edda;
      
    }
    
 
  </style>
  
  
  <div class="main-container">
        <div class="user-container">
          <span class="title">Lista pojazdów</span>
            <p>Zalogowany użytkownik: <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span></p>
     </div>
  
   
      <!-- Tabela z danymi z bazy -->
        <table>
            <thead>
                <tr>
                  
                   <th>ID</th>
                   <th>Marka i model</th>
                   <th>Rok</th>
                   <th>Użytkownik</th>
                   <th>Ubezpieczenie</th>
                   <th>Przeglad</th>
                   <th>Przebieg</th>
                   <th>Spalanie</th>
                   <th>Opcje</th>
        
                </tr>
            </thead>
            <tbody>
                <?php
                // Wyświetlanie danych z tabeli 'cars'
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        
                      
                      
                      
                      
                      
      $insuranceClass = getInsuranceClass($row['insurance']);
      $inspectionClass = getInsuranceClass($row['technical_inspection']);
 
      
      
      
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) .' 
        
        <button class="list-info-button" onclick="openInfo(' . htmlspecialchars($row['id']) . ')" title="Informacje"><i class="fas fa-info"></i></button>
          
          </td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td class="' . $insuranceClass . '">' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td class="' . $inspectionClass . '">' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
       echo '<td>' . (isset($row['average_fuel_consumption']) ? htmlspecialchars($row['average_fuel_consumption']) : 'Brak danych') . '</td>';
      
      
        
        echo '<td>
      
      <button class="list-menu-button" onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')" title="Dodaj"><i class="fas fa-plus"></i></button>
          
        
      <button class="list-menu-button" onclick="openHistory(' . htmlspecialchars($row['id']) . ')"><i class="fas fa-history"></i></button>
      
      <button class="list-menu-button" onclick="openService(' . htmlspecialchars($row['id']) . ')"><i class="fas fa-tools"></i></button>
      
      </td>';
        
        echo '</tr>';
                      
                      
                      
                      
                      
                      
                      
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
  
  
  
  
  
  
  </div>
  
  
</head>
<body>
  
  
  
  

</body>
</html>
