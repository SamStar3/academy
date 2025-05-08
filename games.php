<?php
    session_start();
    include("include/games.php"); 
?>
<!doctype html>
<html lang="en">

<?php include("include/head.php");?>

<body>

	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
			<?php include("include/left.php");?>
		<!--end sidebar wrapper -->
		<!--start header -->
			<?php include("include/top.php");?>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
            <div class="page-content" id="subject-content">
            <div class="page-title-box">
    <div class="page-title-right">
        <h2 class="page-title">Games</h2>
    </div>
</div>

<div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-success" id="btnAddSubject" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
        Add Games
    </button>
</div>

<div class="row">
    <?php if (!empty($games)): ?>
        <?php foreach ($games as $game): ?>
        <div class="game-card"  style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:10px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h3>
                    <?= htmlspecialchars($game['name']) ?>
                    <a href="edit_game.php?id=<?= $game['id'] ?>" title="Edit">
                        <i class="fas fa-edit" style="color:#007bff; margin-left:10px;"></i>
                    </a>
                    <a href="delete_game.php?id=<?= $game['id'] ?>" title="Delete" onclick="return confirm('Are you sure to delete?')">
                        <i class="fas fa-trash-alt" style="color:red; margin-left:5px;"></i>
                    </a>
                </h3>
            </div>

            <button onclick="toggleDetails(<?= $game['id'] ?>)" class="btn btn-success">View</button>

            <div id="details-<?= $game['id'] ?>" style="display:none; margin-top:10px;">
                <p><strong>Description:</strong> <?= htmlspecialchars($game['description']) ?></p>
                <p><strong>Total Amount:</strong> <?= htmlspecialchars($game['total_amount']) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <p>No games found.</p>
    <?php endif; ?>
</div>


            <?php include("modal/subject.php");?>
        </div>
        <?php include("include/footer.php");?>
        <script src="js_functions/subject.js" type="text/javascript"></script>
        <script>
        function toggleDetails(id) {
            const el = document.getElementById("details-" + id);
            el.style.display = (el.style.display === "none") ? "block" : "none";
        }
        </script>

    </div>
    
</body>
</html>
