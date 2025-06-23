<?php
function getInsuranceClass($date) {
    $currentDate = date('Y-m-d');
    $dateDiff = (strtotime($date) - strtotime($currentDate)) / (60 * 60 * 24);
    if ($dateDiff < 0) return 'insurance-expired';
    if ($dateDiff <= 30) return 'insurance-warning';
    return 'insurance-valid';
}
  
?>