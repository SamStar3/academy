<?php
    include("config.php");
    
    $queryTrainee = "SELECT * FROM listtrainees WHERE status = 'active'";    
    $resTrainee = mysqli_query($conn, $queryTrainee);
?>