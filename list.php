<?php
require 'config/session.php';
require 'config/db_connection.php';
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



 //Jeśli użytkownik nie jest administratorem, dodajemy warunek, by pokazać tylko samochody przypisane do tego użytkownika
//if ($user_role !== 'admin') {
//    $sql .= " HAVING user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";

//}

 

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
                        
                      
                      
                      
                      
                      
      //$insuranceClass = getInsuranceClass($row['insurance']);
      //$inspectionClass = getInsuranceClass($row['technical_inspection']);
 
      
      
      
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['model']) . '</td>';
        echo '<td>' . htmlspecialchars($row['year']) . '</td>';
        echo '<td>' . htmlspecialchars($row['user_id']) . '</td>';
        echo '<td class="' . $insuranceClass . '">' . htmlspecialchars($row['insurance']) . '</td>';
        echo '<td class="' . $inspectionClass . '">' . htmlspecialchars($row['technical_inspection']) . '</td>';
        echo '<td>' . htmlspecialchars($row['mileage']) . '</td>';
       echo '<td>' . (isset($row['average_fuel_consumption']) ? htmlspecialchars($row['average_fuel_consumption']) : 'Brak danych') . '</td>';
      
      
        
        
        echo '<td>';
        echo '<button onclick="openMenuAdd(' . htmlspecialchars($row['id']) . ')">Dodaj</button>';
        echo '<button onclick="openInfo(' . htmlspecialchars($row['id']) . ')">Info</button>';
        echo '<button onclick="openHistory(' . htmlspecialchars($row['id']) . ')">Historia</button>';
        echo '<button onclick="openService(' . htmlspecialchars($row['id']) . ')">Serwis</button>';
        echo '</td>';
        
        echo '</tr>';
                      
                      
                      
                      
                      
                      
                      
                    }
                } else {
                    echo "<tr><td colspan='2'>Brak danych w tabeli 'cars'.</td></tr>";
                }
                ?>
            </tbody>
        </table>
  
  
  
  
  
  
  
  
  
</head>
<body>
  
  
  
  

</body>
</html>
