<?php 
    include("config.php");

    $selQuery = "SELECT * FROM games WHERE status = 1";
    $resGames = mysqli_query($conn, $selQuery);

    $games = [];

    if ($resGames && mysqli_num_rows($resGames) > 0) {
        while ($row = mysqli_fetch_assoc($resGames)) {
            // Get package data
            $gameId = $row['id'];
            $pkgQuery = "SELECT total_amount FROM game_package WHERE games_id = $gameId AND status = 1 LIMIT 1";
            $pkgRes = mysqli_query($conn, $pkgQuery);
            $pkgData = mysqli_fetch_assoc($pkgRes);
            $row['total_amount'] = $pkgData ? $pkgData['total_amount'] : 'N/A';
            $games[] = $row;
        }
    }
?>
