<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form name="frmAddSubject" id="addSubject" enctype="multipart/form-data">
        <input type="hidden" name="hdnAction" value="addSubject">
        <div class="modal-header">
          <h4 class="modal-title" id="staticBackdropLabel">Add Subject</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="subjectMsg" class="px-3 mt-2"></div>
        <div class="modal-body p-3">
          <div class="row g-3">
            
            <div class="col-sm-12">
              <label for="sub_name" class="form-label fw-bold">Subject Name</label>
              <input type="text" class="form-control" placeholder="Enter subject name" name="sub_name" id="sub_name" required>
            </div>
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



