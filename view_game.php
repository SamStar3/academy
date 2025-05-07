<?php
include("include/config.php");

//print_r($_REQUEST); exit;

if (!isset($_GET['id'])) {
    echo "No game ID provided.";
    exit;
}

$game_id = intval($_GET['id']);

// Fetch game info
$game_query = "SELECT * FROM games WHERE id = $game_id";
$game_result = mysqli_query($conn, $game_query);
$game = mysqli_fetch_assoc($game_result);

if (!$game) {
    echo "Game not found.";
    exit;
}

// Fetch related packages
$package_query = "SELECT * FROM game_package WHERE games_id = $game_id";
$package_result = mysqli_query($conn, $package_query);
?>

<h2><?= htmlspecialchars($game['name']) ?></h2>
<p><strong>Description:</strong> <?= nl2br(htmlspecialchars($game['description'])) ?></p>
<p><strong>Status:</strong> <?= htmlspecialchars($game['status']) ?></p>
<p><strong>Created At:</strong> <?= htmlspecialchars($game['created_at']) ?></p>

<h4 class="mt-4">Packages for <?= htmlspecialchars($game['name']) ?>:</h4>
<?php if (mysqli_num_rows($package_result) > 0): ?>
    <table class="table table-bordered mt-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pkg = mysqli_fetch_assoc($package_result)): ?>
                <tr>
                    <td><?= $pkg['id'] ?></td>
                    <td><?= $pkg['total_amount'] ?></td>
                    <td><?= $pkg['status'] ?></td>
                    <td><?= $pkg['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No packages found for this game.</p>
<?php endif; ?>
