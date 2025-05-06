<?php
    session_start();
    include("include/viewTrainee.php"); 
?>
<!doctype html>
<html lang="en">
  <?php include("include/head.php"); ?>
  <body>
    <div class="wrapper">
      <?php include("include/top.php");?>
      <?php include("include/left.php");?>
      <?php $row = mysqli_fetch_assoc($viewTrainee) ?>
      <div class="page-wrapper">
        <div class="page-content">
          <div class="page-title-box">
              <div class="page-title-right">
                  <div class="modal-footer p-2">
                      <button type="button" class="btn btn-primary me-auto" onclick="javascript:location.href='trainee.php'"><i class='bx bx-arrow-back'></i></button>
                     <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-id="<?php echo $row['person_id']; ?>"  data-bs-target="#CourseTraineeModal">Add Course</button>                          
                  </div>
              </div>            
          </div>
          <div class="row">
            <div class="col-lg-4">
              <div class="card h-100" style="height: 450px;">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <?php if($row['profile']) { ?>
                    <img src="<?php echo $row['profile'] ?>" alt="logo" class="rounded-circle p-1 bg-secondary img-fluid" style="width: 120px; height: 120px; object-fit: cover; object-position: top; max-width: 120px; max-height: 120px;">
                  <?php } else { ?>
                    <img src="assets/images/logo/default_logo.jpg" alt="logo" class="rounded-circle p-1 bg-secondary img-fluid" style="width: 120px; height: 120px; object-fit: cover; object-position: top; max-width: 120px; max-height: 120px;">
                    <?php } ?>
                    <div class="mt-3">
                      <h4> <?php echo $row['person_name']; ?>  </h4>
                      <p class="text-secondary mb-1"> <?php echo $row['register_no']; ?> </p>
                      <p class="text-secondary mb-1"> <?php echo $row['gender']; ?> </p>
                      <p class="text-secondary mb-1"> <?php echo $row['blood_group']; ?> </p>
                      <p class="text-secondary mb-1"> Trainee  </p>
                      <p class="text-secondary mb-1"> <?php echo $row['email']; ?> </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <div class="card h-100" style="height: 450px;">
                <div class="card-body">
                  <div class="row h-100">
                    <div class="col-md-8">
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Mobile</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1">  <?= !empty($row['phone_number']) ? $row['phone_number'] : '-' ?></p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Address</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1"> <?= !empty($row['address']) ? $row['address'] : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Username</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1" id="usernameField"> <?= !empty($row['username']) ? $row['username'] : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Password</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1" id="passwordField"> <?= !empty($row['password']) ? '********' : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Date Of Joining</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1"> <?= !empty($row['doj']) ? $row['doj'] : '-' ?> <span style="color: red;"> (-) </span> </p>
                        </div>
                      </div>                      
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Date Of Birth</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1"> <?= !empty($row['dob']) ? $row['dob'] : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Course Name</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1"> <?= !empty($row['course_name']) ? $row['course_name'] : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Course Duration</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1">  <?= !empty($row['duration']) ? $row['duration'] : '-' ?> </p>
                        </div>
                      </div>                         
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Incharge Name</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1"> <?= !empty($row['incharger']) ? $row['incharger'] : '-' ?> </p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Total Fees</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1">₹  <?= !empty($row['course_amount']) ? $row['course_amount'] : '-' ?> </p>
                        </div>
                      </div>
                          <?php 
                            if(mysqli_num_rows($viewTrainee2) > 0) {
                                $total_paid = 0;
                                while($row2 = mysqli_fetch_assoc($viewTrainee2)) {
                                    $total_paid += $row2['paid_amount'];
                                }
                                
                          $total_fee =$row['course_amount'];
                          $balance_amount = $total_fee - $total_paid;
                            } else {
                                echo "No records found in viewTrainee.";
                            }
                            ?>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Total Paid Amount</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-secondary mb-1">₹  <?= number_format($total_paid, 2) ?></p>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <div class="col-sm-4">
                          <h6 class="mb-0">Balance Amount</h6>
                        </div>
                        <div class="col-sm-8 text-secondary">
                          <p class="text-danger mb-1">₹ <?= number_format($balance_amount, 2) ?></p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="true">Payment</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" tabindex="-1">Document</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="false" tabindex="-1">Login</button>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                  <div class="d-flex justify-content-end mb-3">
                    <button type="button"  class="btn btn-primary" data-bs-toggle="modal" data-id="<?php echo $row['person_id']; ?>"  data-bs-target="#PaymenttraineeModal">Add Payment</button>
                  </div>
                  <div class="table-responsive">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                      <div class="row">
                        <div class="col-sm-12 col-md-6">
                          <div class="dt-buttons btn-group">  
                              <button class="btn btn-outline-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Copy</span></button> 
                              <button class="btn btn-outline-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>Excel</span></button> 
                              <button class="btn btn-outline-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="example2" type="button"><span>PDF</span></button>
                              <button class="btn btn-outline-secondary buttons-print" tabindex="0" aria-controls="example2" type="button"><span>Print</span></button> 
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                          <div id="example2_filter" class="dataTables_filter">
                            <label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example2"></label>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                          <table id="example2" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="example2_info">
                            <thead>
                              <tr role="row">
                                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="S. No: activate to sort column descending" style="width: 80.7875px;">S. No</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 145.1px;">Date</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Received Amount: activate to sort column ascending" style="width: 232.225px;">Received Amount</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Received By: activate to sort column ascending" style="width: 166.025px;">Received By</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Payment Mode: activate to sort column ascending" style="width: 200.712px;">Payment Mode</th>
                                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 117.15px;">Action</th>
                              </tr>
                            </thead>
                            </thead>
                                <tbody>
                                <?php 
                                  $i = 1; 
                                  while($row1 = mysqli_fetch_assoc($viewTrainee1)): 
                                ?>
                                  <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row1['payment_date'])); ?></td>
                                    <td>₹<?php echo $row1['paid_amount']; ?></td>
                                    <td><?php echo $row1['received_by']; ?></td>
                                    <td><?php echo $row1['payment_mode']; ?></td>
                                    <td>
                                      <a target="_blank" href="traineeReceipt.php?id=<?php echo $row1['payment_id']; ?>">
                                        <button class="btn btn-primary btn-sm m-1">
                                          <i class="ph ph-download"></i> Bill PDF
                                        </button>
                                      </a>
                                    </td>
                                  </tr>
                                <?php endwhile; ?>
                                </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                  <div class="d-flex justify-content-end mb-5">
                    <button type="button" id="addDocumentBtn" class="btn btn-primary" onclick="goDocuments(<?php echo $row['person_id']; ?>);" data-bs-toggle="modal" data-bs-target="#addDocumentModal">Add Documents</button>
                  </div>
                  <div class="container">
                    <div class="row">                      
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                  <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <h6 class="mt-3 text-center">Change Username Password</h6>
                    <form id="loginForm" action="action/trainee_login.php">
                      <div class="row mt-4 ml-2">
                        <input type="hidden" name="person_id" id="person_id" value="<?php echo $row['person_id']; ?>">
                        <div class="col-sm-12 col-md-4">
                          <input type="text" class="form-control" name="pusername" id="pusername" placeholder="Enter Username">
                        </div>
                        <div class="col-sm-12 col-md-4">
                          <input type="text" class="form-control" name="ppassword" id="ppassword" placeholder="Enter Password">
                        </div>
                        <div class="col-sm-12 col-md-6 mt-4">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <?php include("modal/paymentTrainee.php");?>
      </div>
      <?php include("include/footer.php"); ?>
    </div>
    <script>

      
$(document).ready(function () {
    // When course is selected
    $('#tcourse').on('change', function () {
        let courseId = $(this).val();
        // Clear previous options
        $('#tduration').html('<option value="">-- Select Duration --</option>');
        $('#tfee').val('');
        $('#tdiscount').val('');

        if (courseId) {
            $.ajax({
                url: 'action/trainee.php',
                type: 'POST',
                data: {
                    type: 'get_durations',
                    course_id: courseId
                },
                success: function (response) {
                    $('#tduration').append(response);
                    console.log("Durations loaded:", response);
                },
                error: function (xhr, status, error) {
                    console.log("Error loading durations:", error);
                }
            });
        }
    });

    // When duration is selected
    $('#tduration').on('change', function () {
        let courseId = $('#tcourse').val();
        let duration = $(this).val();

        if (courseId && duration) {
            $.ajax({
                url: 'action/trainee.php',
                type: 'POST',
                data: {
                    type: 'get_fee_discount',
                    course_id: courseId,
                    duration: duration
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    $('#tfee').val(response.fee);
                },
                error: function (xhr) {
                    console.log("AJAX Error:", xhr.responseText);
                    alert("Something went wrong. Check console for details.");
                }                
            });
        }
    });
});


      document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("action/trainee_login.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: 'Username and password updated successfully.',
              confirmButtonText: 'OK'
            }).then(() => {
              location.reload(); // Reload the page after OK is clicked
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: data.message || 'Login update failed.',
              confirmButtonText: 'OK'
            });
          }
        })
        .catch(error => {
          console.error("Error:", error);
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
            confirmButtonText: 'OK'
          });
        });
      });
      document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById('PaymenttraineeModal');

        modal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          var personId = button.getAttribute('data-id');
          
          // Set the value in hidden input
          document.getElementById('paymentperson_id').value = personId;
        });
      });
      $(document).ready(function() {
        $('#paymentForm').on('submit', function(e) {
          e.preventDefault(); // Stop normal form submission
          $.ajax({
            url: 'action/paymentTrainee.php', // Your PHP file path
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
              alert(response); // Show success message
              location.reload();
              $('#PaymenttraineeModal').modal('hide'); // Close modal
              $('#paymentForm')[0].reset(); // Reset form
            },
            error: function(xhr, status, error) {
              alert("Something went wrong: " + error);
            }
          });
        });
      });
      document.addEventListener("DOMContentLoaded", function () {
        var modal1 = document.getElementById('CourseTraineeModal');

        modal1.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget;
          var personId = button.getAttribute('data-id');
          
          // Set the value in hidden input
          document.getElementById('courseperson_id').value = personId;
        });
      });
      $(document).ready(function() {
        $('#courseForm').on('submit', function(e) {
          e.preventDefault(); // Stop normal form submission
          $.ajax({
            url: 'action/paymentTrainee.php', // Your PHP file path
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
              alert(response); // Show success message
              location.reload();
              $('#CourseTraineeModal').modal('hide'); // Close modal
              $('#courseForm')[0].reset(); // Reset form
            },
            error: function(xhr, status, error) {
              alert("Something went wrong: " + error);
            }
          });
        });
      });
    </script>
  </body>
</html>