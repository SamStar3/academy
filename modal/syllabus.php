<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addTopicModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
    <form name="frmAddTopic" id="addTopic" enctype="multipart/form-data" method="POST" action="action/syllabus.php">
    <input type="hidden" name="hdnAction" value="addTopic">
    <input type="hidden" name="subject_id" id="subject_id" value="<?php echo $id; ?>">
    <div class="modal-header">
        <h4 class="modal-title">Add Topic</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="topic_name">Topic Name</label>
            <input type="text" class="form-control" name="topic_name" id="topic_name" required>
        </div>
        <div class="mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" id="description" required></textarea>
        </div>
    </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
 </div>
</div>


<!-- Edit Syllabus Modal -->
<div class="modal fade" id="editSyllabusModal" tabindex="-1" aria-labelledby="editSyllabusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form id="updateSyllabusForm" method="POST" action="action/syllabus.php">
  <input type="hidden" name="hdnAction" value="updateSyllabus">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editSyllabusModalLabel">Edit Syllabus</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="edit_syllabus_id" name="syllabus_id">
            <div class="mb-3">
                <label for="edit_syllabus_name" class="form-label">Syllabus Name</label>
                <input type="text" class="form-control" id="edit_syllabus_name" name="syllabus_name" required>
            </div>
            <div class="mb-3">
                <label for="edit_syllabus_description" class="form-label">Description</label>
                <textarea class="form-control" id="edit_syllabus_description" name="description" rows="3" required></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Add Study Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addMaterialForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="addMaterialLabel">Add Study Material</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="syllabus_id" id="material_syllabus_id" />
          <div class="mb-3">
            <label for="materialFile" class="form-label">Select File</label>
            <input type="file" class="form-control" id="materialFile" name="file" required>
          </div>
          <div class="mb-3">
            <label for="materialDescription" class="form-label">Description</label>
            <textarea class="form-control" id="materialDescription" name="description" required></textarea>
          </div>         
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Material</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Edit Material Modal -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editMaterialForm" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editMaterialLabel">Edit Study Material</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="material_id" id="editMaterialId">
          <input type="hidden" name="syllabus_id" id="editSyllabusId">

          <div class="mb-3">
            <label for="editFile" class="form-label">Change File (Optional)</label>
            <input type="file" class="form-control" name="file">
          </div>

          <div class="mb-3">
            <label for="editDescription" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="editDescription" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- View Study Materials Modal -->
<div class="modal fade" id="viewMaterialsModal" tabindex="-1" aria-labelledby="viewMaterialsLabel" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewMaterialsLabel">Study Materials</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="viewExistingMaterials">
          <p>Loading...</p>
        </div>
      </div>
    </div>
  </div>
</div>
