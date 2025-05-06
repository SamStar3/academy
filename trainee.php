<?php
    include("include/trainee.php"); 
?>
<!doctype html>
<html lang="en">
<?php include("include/head.php"); ?>
<body>
    <div class="wrapper">
        <?php include("include/top.php");?>
        <?php include("include/left.php");?>
        <div class="page-wrapper">
            <div class="page-content">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <h2 class="page-title">Trainee</h2>
                        <div class="position-relative" style="height: 80px;">                          
                            <button type="button" id="addTraineeBtn" class="btn btn-primary position-absolute top-0 end-0" data-bs-toggle="modal" data-bs-target="#addTraineeModal">Add Trainee</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="container-fluid py-4">
                            <div class="card shadow-sm p-4">
                            <?php
                                $doj = $_GET['doj'] ?? '';
                                $selectedCourseId = $_GET['course_id'] ?? '';
                                ?>
                                <div class="row g-3">
                                    <!-- DOJ Input -->
                                    <div class="col-md-3 col-12">
                                        <label>Join Date</label>
                                        <input type="date" id="doj" name="doj" class="form-control" value="<?= $doj ?>">
                                    </div>

                                    <!-- Course Dropdown -->
                                    <div class="col-md-3 col-12">
                                        <label for="course_id" class="form-label">Course Name</label>
                                        <select id="course_id" name="course_id" class="form-select">
                                            <option value="">-- Select Course --</option>
                                            <?php
                                            $queryCourse = "SELECT id, name FROM course WHERE status='Active'";
                                            $resCourse = mysqli_query($conn, $queryCourse);
                                            while ($rowCourse = mysqli_fetch_assoc($resCourse)) {
                                                $selected = $selectedCourseId == $rowCourse['id'] ? 'selected' : '';
                                                echo "<option value='{$rowCourse['id']}' $selected>{$rowCourse['name']}</option>";
                                            }
                                            mysqli_free_result($resCourse);
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Filter + Clear Buttons -->
                                    <div class="col-md-3 col-12 d-flex align-items-end">
                                        <div class="w-100 d-flex justify-content-between">
                                            <button id="filterBtn" class="btn btn-primary w-48">Filter</button>
                                            <button id="clearBtn" class="btn btn-secondary w-48">Clear</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="addTraineeTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($resTrainee) > 0) {
                                        $sno = 1; 
                                        while ($row = mysqli_fetch_assoc($resTrainee)) {
                                    ?>
                                    
                                    <tr>
                                        <td><?php echo $sno; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['phone_number']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td>
                                            <button class="coustome_btn text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View" onclick="window.location.href='viewTrainee.php?id=<?php echo $row['id']; ?>';">                                            
                                                <i class="lni lni-eye"></i>
                                            </button>
                                            <button type="button" class="coustome_btn text-success"  onclick="editTrainee(<?php echo $row['id']; ?>);" data-bs-toggle="modal" data-bs-target="#editTraineeModal">
                                                <i class="lni lni-pencil"></i>
                                            </button>
                                            <button class="coustome_btn text-danger btn-sm delete-person" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" data-id="<?php echo $row['id'] ?>">
                                                <i class="lni lni-trash"></i>
                                            </button>
                                          
                                        </td>
                                    </tr>
                                    <?php
                                            $sno++;
                                            }
                                        } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center"> trainees Not found</td>
                                    </tr>
                                    <?php
                                        }
                                        mysqli_free_result($resTrainee);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php include("modal/trainee.php");?>
            </div>
            <?php include("include/footer.php"); ?>
        </div>
    </div>
    <script src="js_functions/trainee.js" type="text/javascript"></script>
    </body>
</html>