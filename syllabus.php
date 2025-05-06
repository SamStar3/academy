<?php
session_start();
include("include/syllabus.php"); 
?>
<!doctype html>
<html lang="en">
<?php include("include/head.php"); ?>
<body>
<div class="wrapper">
    <?php include("include/left.php"); ?>
    <?php include("include/top.php"); ?>
    <div class="page-wrapper">
        <div class="page-content" id="syllabus-content">
        <?php
                $subject_name = '';
                if ($viewSubject && mysqli_num_rows($viewSubject) > 0) {
                    $firstRow = mysqli_fetch_assoc($viewSubject);
                    $subject_name = $firstRow['subject_name'];
                    mysqli_data_seek($viewSubject, 0); 
                }
                ?>
                <h2 id="h4subId">Syllabus: <?php echo htmlspecialchars($subject_name); ?></h2>

            <br>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" class="btn btn-primary" onclick="location.href='subject.php'"><i class='bx bx-arrow-back'></i></button>
                <button type="button" id="addTopicBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTopicModal">Add Topic</button>
                </div>
            <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Topic</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="syllabusTableBody">
                        <?php
                        if ($viewSubject && mysqli_num_rows($viewSubject) > 0) {
                            $sno = 1;
                            while ($row = mysqli_fetch_assoc($viewSubject)) {
                        ?>
                        <tr>
                            <td><?php echo $sno; ?></td>
                            <td><?php echo htmlspecialchars($row['syllabus_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['syllabus_description']); ?></td>
                            <td>
                                <button class="coustome_btn text-warning" title="Edit" onclick="goEditSyllabus(<?php echo $row['syllabus_id']; ?>)">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="coustome_btn text-danger" title="Delete" onclick="deleteSyllabus(<?php echo $row['syllabus_id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <button class="btn btn-link text-primary p-0" onclick="openMaterialModal(<?= $row['syllabus_id'] ?>)" title="Add Study Material">
                                    <i class="bi bi-camera-video"></i>
                                </button>
                                <button class="coustome_btn text-success" onclick="openViewModal(<?= $row['syllabus_id'] ?>)" data-bs-toggle="tooltip" data-bs-placement="top" title="Topic Material">
                                    <i class="bi bi-folder custom-icon"></i>
                                </button>
                            </td>
                        </tr>
                        <?php
                                $sno++;
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="4" class="text-center">No syllabus entries found</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
    <?php include("include/footer.php"); ?>
</div>
<script src="js_functions/syllabus.js" type="text/javascript"></script>
<?php include("modal/syllabus.php");?>

</body>
</html>
