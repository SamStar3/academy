console.log("syllabus.js loaded");

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("addTopic").addEventListener("submit", function (e) {
        e.preventDefault();
  
        const subject_id = document.getElementById('subject_id').value;
        const topic_name = document.getElementById('topic_name').value;
        const description = document.getElementById('description').value;
  
        const formData = new FormData(this);
  
        fetch("action/syllabus.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.text())
        .then(response => {
            alert(response);
            location.reload(); // Refresh the page to show the updated syllabus
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Something went wrong.");
        });
    });
  });


//fetch
function goEditSyllabus(syllabusId) {
    document.activeElement?.blur();
    $('#staticBackdrop').modal('hide');

    $.ajax({
        url: "action/syllabus.php",
        type: "POST",
        data: { syllabus_id: syllabusId, action: 'fetchSyllabus' },
        dataType: "json",
        success: function (response) {
            if (response.error) {
                Swal.fire("Error!", response.error, "error");
            } else {
                $('#edit_syllabus_id').val(syllabusId);
                $('#edit_syllabus_name').val(response.syllabus_name);
                $('#edit_syllabus_description').val(response.description);
                $('#editSyllabusModal').modal('show');
            }
        },
        error: function () {
            Swal.fire("Error!", "Failed to fetch syllabus details.", "error");
        }
    });
}
//update
$(document).ready(function () {
    // Form submission
    $("#updateSyllabusForm").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "action/syllabus.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.trim() === "success") {
                    alert("Syllabus updated successfully!");
                    $('#editSyllabusModal').modal('hide');
                    location.reload();
                } else {
                    alert("Update failed: " + response);
                }
            }
        });
    });
});

//delete
function deleteSyllabus(syllabusId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "action/syllabus.php",
                type: "POST",
                data: {
                    syllabus_id: syllabusId,
                    action: 'deleteSyllabus'
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Deleted!", response.success, "success");
                        // Remove row from table
                        $(`button[onclick="deleteSyllabus(${syllabusId})"]`).closest('tr').remove();
                    } else {
                        Swal.fire("Error!", response.error, "error");
                    }
                },
                error: function () {
                    Swal.fire("Error!", "Failed to delete syllabus.", "error");
                }
            });
        }
    });
}




function openMaterialModal(syllabusId) {
    $('#material_syllabus_id').val(syllabusId);
    $('#existingMaterials').html('<p>Loading materials...</p>');
    $('#addMaterialModal').modal('show');
    
    // Load existing materials
    $.ajax({
        url: 'action/syllabus.php',
        type: 'POST',
        data: {
            syllabus_id: syllabusId,
            action: 'fetchExistingMaterials'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.materials.length > 0) {
                let html = '';
                response.materials.forEach(material => {
                    html += `
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">${material.file_name}</h6>
                                <p class="card-text">${material.description}</p>
                            </div>
                        </div>`;
                });
                $('#existingMaterials').html(html);
            } else {
                $('#existingMaterials').html('<p>No existing materials found.</p>');
            }
        },
        error: function() {
            $('#existingMaterials').html('<p>Error loading materials.</p>');
        }
    });
}

// Handle file upload
$(document).ready(function() {
    $('#addMaterialForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'action/syllabus.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        alert(res.success);
                        $('#addMaterialModal').modal('hide');
                        location.reload();
                    } else {
                        alert(res.error || 'Unknown error');
                    }
                } catch (e) {
                    alert('Invalid response: ' + response);
                }
            },
            error: function(xhr, status, error) {
                alert('Something went wrong. Please try again.');
            }
        });
    });
});


    function openViewModal(syllabusId) {
        var viewModal = new bootstrap.Modal(document.getElementById('viewMaterialsModal'));
        viewModal.show();

    $('#viewExistingMaterials').html('<p>Loading materials...</p>');

    $.ajax({
        url: 'action/syllabus.php',
        type: 'POST',
        data: {
            syllabus_id: syllabusId,
            action: 'fetchExistingMaterials'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                var html = '';
                response.materials.forEach(function(material) {
                    html += `
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">${material.file_name}</h6>
                                <p class="card-text">${material.description}</p>
                                <a href="viewMaterial.php?file=${encodeURIComponent(material.file_name)}&desc=${encodeURIComponent(material.description)}" class="btn btn-sm btn-primary" target="_blank">View File</a>
                            </div>
                        </div>
                    `;
                });
                $('#viewExistingMaterials').html(html);
            } else {
                $('#viewExistingMaterials').html('<p>No study materials found.</p>');
            }
        },
        error: function() {
            $('#viewExistingMaterials').html('<p>Error loading materials.</p>');
        }
    });
}





