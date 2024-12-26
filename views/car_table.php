<?php


// Przygotowanie zapytania SQL, aby pobrać dane samochodów

$sql = "SELECT id, model, year, user_id, insurance, technical_inspection, mileage FROM cars";
/*$sql = "
    SELECT 
        c.id, 
        c.model, 
        c.year, 
        c.user_id, 
        c.insurance, 
        c.technical_inspection, 
        c.mileage,
        (f.distance / f.liters) AS fuel_consumption
    FROM 
        cars c
    LEFT JOIN 
        fuel f 
    ON 
        c.id = f.car_id
";
*/
//bez zaokraglania do 2 miejsc po przecinku
//IFNULL(SUM(f.distance) / SUM(f.liters), 'Brak danych') AS average_fuel_consumption

$sql = "
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
if ($user_role !== 'admin') {
    $sql .= " WHERE user_id = '" . mysqli_real_escape_string($conn, $_SESSION['id']) . "'";
  


}

 


$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Marka i model</th>';
    echo '<th>Rok</th>';
    echo '<th>Użytkownik</th>';
    echo '<th>Ubezpieczenie</th>';
    echo '<th>Przeglad</th>';
    echo '<th>Przebieg</th>';
    echo '<th>Spalanie</th>';
    echo '<th>Opcje</th>';
    echo '</tr>';
   require 'helpers/functions.php';
    while ($row = mysqli_fetch_assoc($result)) {
    
      
      
      $insuranceClass = getInsuranceClass($row['insurance']);
      $inspectionClass = getInsuranceClass($row['technical_inspection']);
 /*
      $insuranceDate = $row['insurance'];
    $currentDate = date('Y-m-d'); // Pobieramy aktualną datę w formacie Y-m-d
    
   // Obliczenie różnicy w dniach między datą ubezpieczenia a bieżącą datą
    $dateDiff = (strtotime($insuranceDate) - strtotime($currentDate)) / (60 * 60 * 24);
    
    // Określenie klasy CSS na podstawie różnicy w dniach
    if ($dateDiff < 0) {
        $insuranceClass = 'insurance-expired'; // Czerwony dla przeterminowanego ubezpieczenia
    } elseif ($dateDiff <= 30) {
        $insuranceClass = 'insurance-warning'; // Pomarańczowy dla ubezpieczenia bliskiego terminu
    } else {
        $insuranceClass = 'insurance-valid'; // Zielony dla ważnego ubezpieczenia
    }
    
      */
     
   
      
      
      
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
    echo '</table>';
} else {
    echo "Brak danych.";
}

// Zamykamy połączenie
mysqli_close($conn);
?> 
