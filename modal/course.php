<!-- Edit Course Modal -->
<div class="modal fade" id="EditBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editCourseForm">
        <input type="hidden" name="course_id" id="edit_course_id">
        <input type="hidden" name="hdnAction" value="edit_Course">

        <div class="modal-header">
          <h4 class="modal-title" id="editCourseModalLabel">Edit Course</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_course_name" class="form-label fw-bold">Course Name</label>
            <input type="text" class="form-control" id="edit_course_name" name="course_name" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Subjects</label>
            <div class="row" id="edit_subject_list"></div>
          </div>

          <div class="mb-3">
            <label for="edit_duration" class="form-label fw-bold">Duration</label>
            <select id="edit_duration" name="duration" class="form-select" required>
              <option value="">-- Select Duration --</option>
              <!-- Auto-fill via JS or same loop as add -->
              <?php
              for ($i = 1; $i <= 12; $i++) {
                $label = "$i month" . ($i > 1 ? "s" : "");
                echo "<option value='$label'>$label</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_fee" class="form-label fw-bold">Fee</label>
            <input type="number" id="edit_fee" name="fee" class="form-control" placeholder="Enter Course Fee">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addCourseForm" name="addCourseForm">
      <input type="hidden" name="hdnAction" value="add_Course">
      <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Add Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-3">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="course_name" class="form-label fw-bold">Course Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="course_name" id="course_name" placeholder="Enter Course Name" required>
            </div>

            <div class="col-md-6 mb-3">
              <label for="duration" class="form-label fw-bold">Duration <span class="text-danger">*</span></label>
              <select id="duration" name="duration" class="form-select" required>
                <option value="">-- Select Duration --</option>
                <?php
                for ($i = 1; $i <= 12; $i++) {
                  $label = "$i month" . ($i > 1 ? "s" : "");
                  echo "<option value='$label'>$label</option>";
                }
                ?>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label for="fee" class="form-label fw-bold">Fee</label>
              <input type="number" id="fee" name="fee" class="form-control" placeholder="Enter Course Fee">
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label fw-bold">Select Subjects</label>
              <div class="row">
                <?php
                $subjects = mysqli_query($conn, "SELECT id, name FROM subject");
                while ($row = mysqli_fetch_assoc($subjects)) {
                  $inputId = 'subject_' . $row['id'];
                  echo "
                    <div class='col-6'>
                      <div class='form-check'>
                        <input class='form-check-input' type='checkbox' id='$inputId' name='subjects[]' value='{$row['id']}'>
                        <label class='form-check-label' for='$inputId'>{$row['name']}</label>
                      </div>
                    </div>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS (make sure it's only loaded once in your layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
