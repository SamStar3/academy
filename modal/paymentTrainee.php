
<div class="modal fade" id="PaymenttraineeModal" tabindex="-1" aria-labelledby="PaymenttraineeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="PaymenttraineeModalLabel">Add Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Payment form starts here -->
        <form id="paymentForm"  method="POST">
          <input type="hidden" name="hdnAction" value="addPaymentTrainee">
          <div class="mb-3">
            <label for="paymentDate" class="form-label">Payment Date</label>
            <input type="date" class="form-control" id="paymentDate" name="paymentDate" required>
          </div>
          <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
          </div>
          
          <div class="mb-3">
            <input type="hidden" name="paymentperson_id" id="paymentperson_id" value="">
            </div>

          <div class="mb-3">
            <label for="paymentMode" class="form-label">Payment Mode</label>
            <select class="form-select" id="paymentMode" name="paymentMode" required>
              <option value="Cash">Cash</option>
              <option value="Online Payment">Online Payment</option>
              <option value="Cheque">Cheque</option>
            </select>
          </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit Payment</button>
          </div>
        </form>
        <!-- Payment form ends here -->
      </div>
    </div>
  </div>
</div>

<!-- Add course trainee -->

<div class="modal fade" id="CourseTraineeModal" tabindex="-1" aria-labelledby="CourseTraineeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CourseTraineeModalLabel">Add Course</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="courseForm"  method="POST">
          <input type="hidden" name="hdnAction" value="addCourseTrainee">
          <input type="hidden" name="courseperson_id" id="courseperson_id" value="<?= $personId; ?>">
          <div class="row">
              <!-- Course -->
            <div class="col-md-6">
                <label><b>Course</b> <span class="text-danger">*</span></label>
                <select id="tcourse" name="tcourse" class="form-select">
                    <option value="">-- Select Course --</option>
                    <?php
                    $query = "SELECT id, name FROM course WHERE status='Active'";
                    $res = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <!-- Duration Dropdown -->
            <div class="col-md-6">
                <label><b>Duration</b> <span class="text-danger">*</span></label>
                <select id="tduration" name="tduration" class="form-select">
                    <option value="">-- Select Duration --</option>
                </select>
            </div>

            <!-- Fee -->
            <div class="col-md-6 mt-3">
                <label><b>Fee</b></label>
                <input type="number" id="tfee" name="tfee" class="form-control" value="" readonly>
            </div>

            <!-- Discount -->
            <div class="col-md-6 mt-3">
                <label><b>Discount</b></label>
                <input type="text" id="tdiscount" name="tdiscount" value="" class="form-control" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Course</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>




