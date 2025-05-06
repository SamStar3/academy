<?php
    session_start();
    include("include/course.php"); 
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
                    <!-- Add Course Button (Right Aligned) -->
                <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary" id="btnAddSubject" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    Add Games
                    </button>

                </div>
                <div class="row">
                    <?php foreach ($subjects as $subjectId => $subject): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($subject['name']) ?></h5>
                                    <button type="button" class="btn btn-success text-white mb-2" onclick="window.location.href='syllabus.php?id=<?php echo $subject['id']; ?>';">                                            
                                    <i class="bx bx-book-open"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php include("modal/subject.php");?>
        </div>
        <?php include("include/footer.php");?>
        <script src="js_functions/subject.js" type="text/javascript"></script>

    </div>
    
</body>
</html>
