<?php
    session_start();
    include("include/paymentReport.php"); 
?>
<!doctype html>
<html lang="en">
<?php include("include/head.php"); ?>

<body>
    <div class="wrapper">
        <?php include("include/top.php"); ?>
        <?php include("include/left.php"); ?>

        <div class="page-wrapper">
            <div class="page-content">

                <!-- Title Section -->
                <div class="page-title-box">
                    <h2 class="page-title">Payment Report</h2>
                </div>

                <!-- Filter Section -->
                <div class="card">
                    <div class="card-body">
                        <div class="container-fluid py-4">
                            <div class="card shadow-sm p-4">
                                <div class="row g-3">

                                    <!-- Payment Date -->
                                    <div class="col-md-3 col-12">
                                        <label for="reportPaymentDate" class="form-label">Payment Date</label>
                                        <input type="date" id="reportPaymentDate" class="form-control" />
                                        <span id="reportPaymentDateError" class="text-danger small"></span>
                                    </div>

                                    <!-- Trainee Name -->
                                    <div class="col-md-3 col-12">
                                        <label for="person_id" class="form-label">Trainee Name</label>
                                        <select id="person_id" name="person_id" class="form-select" required>
                                            <option value="">-- Select Trainee --</option>
                                            <?php
                                                include("config.php");
                                                $query = "SELECT p.name, p.id 
                                                          FROM person p 
                                                          JOIN person_roles pr ON pr.person_id = p.id 
                                                          JOIN role r ON r.id = pr.role_id 
                                                          WHERE r.name = 'Trainee'";
                                                $result = mysqli_query($conn, $query);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>

                                  
                                    <!-- Filter/Clear Buttons -->
                                    <div class="col-md-3 col-12 d-flex align-items-end ms-auto">
                                        <div class="w-100 d-flex justify-content-end gap-2">
                                            <button id="filterBtn" class="btn btn-primary">Filter</button>
                                            <button id="clearBtn" class="btn btn-secondary">Clear</button>
                                        </div>
                                    </div>

                                </div> <!-- row -->
                            </div> <!-- card -->
                        </div> <!-- container -->
                    </div> <!-- card-body -->
                </div> <!-- card -->

                <!-- Table Section -->
                <div class="table-responsive">
                    <table id="addPaymentTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Payment Mode</th>
                                <th>Received By</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($resPayment) > 0): ?>
                                <?php $i = 1; while($row = mysqli_fetch_assoc($resPayment)): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row['payment_date'])); ?></td>
                                        <td><?php echo $row['person_name']; ?></td>
                                        <td><?php echo $row['payment_mode']; ?></td>
                                        <td><?php echo $row['received_by']; ?></td>
                                        <td>₹<?php echo number_format($row['paid_amount'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6">No payment records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div> <!-- page-content -->
            <?php include("include/footer.php"); ?>
        </div> <!-- page-wrapper -->
    </div> <!-- wrapper -->

    <!-- Scripts -->
    <script src="js_functions/paymentReport.js" type="text/javascript"></script>
    
</body>
</html>
