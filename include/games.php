<?php 
    include("config.php");

    $selQuery = "SELECT * FROM games";
    $resGames = mysqli_query($conn, $selQuery);

    $games = []; // initialize as empty array

    if ($resGames && mysqli_num_rows($resGames) > 0) {
        while ($row = mysqli_fetch_assoc($resGames)) {
            $games[] = $row;
        }
    }
?>
