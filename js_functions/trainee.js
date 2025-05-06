
console.log("trainee js is loaded");
    
// pagination for trainee

        

let dataTable;
$(document).ready(function () {
    // Initialize DataTable and store the instance
    dataTable = $('#addTraineeTable').DataTable({
        "pageLength": 8,
        "lengthChange": false,
        "ordering": true,
        "searching": true,
        "paging": true
    });
});

// filter for trainee

        document.addEventListener("DOMContentLoaded", function () {
            const filterBtn = document.getElementById("filterBtn");
            const clearBtn = document.getElementById("clearBtn");

            filterBtn.addEventListener("click", function () {
                const course_id = document.getElementById("course_id").value;
                const doj = document.getElementById("doj").value;

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "action/trainee.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.querySelector("#addTraineeTable tbody").innerHTML = xhr.responseText;
                      
                    }
                };

                const params = `course_id=${course_id}&doj=${doj}`;
                xhr.send(params);
            });

            clearBtn.addEventListener("click", function () {
                document.getElementById("course_id").value = "";
                document.getElementById("doj").value = "";
                filterBtn.click();
                location.reload(); 
            });
        });

  //edit for trainee
  function editTrainee(id) {
    $('#editTraineeModal').modal('show');
    $('#edit_person_id').val(id);

    $.ajax({
        url: "action/trainee.php",
        type: "GET",
        data: { id: id },
        dataType: "json",
        success: function (data) {
            console.log(data);

            $('#uname').val(data.name);
            $('#uphone').val(data.phone_number);
            $('#uemail').val(data.email);
            $('#uregno').val(data.register_no);
            $('#udob').val(data.dob);
            $('#udoj').val(data.doj);
            $('#ugender').val(data.gender);
            $('#ublood_group').val(data.blood_group);
            $('#uaddress').val(data.address);

            if (data.profile) {
                $('#currentProfileImg').attr('src', data.profile);
            }
        },
        error: function (xhr) {
            console.error("Error fetching trainee:", xhr.responseText);
            alert("Failed to fetch data");
        }
    });
}

$(document).ready(function () {
    // Form submission
    $("#editTrainee").on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "action/trainee.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.trim() === "success") {
                    alert("Trainee updated successfully!");
                    $('#editTraineeModal').modal('hide');
                    location.reload();
                } else {
                    alert("Update failed: " + response);
                }
            }
        });
    });
});

    // On Form Submit
    $("#addTrainee").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'action/trainee.php', 
            type: 'POST',
            data: formData,
            contentType: false,  
            processData: false,  
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Trainee added successfully.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        $('#addTraineeModal').modal('hide');
                        $('#addTrainee')[0].reset();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response,
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'There was an issue with the request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });


    // delete trainee

    $(document).on('click', '.delete-person', function () {
        if (confirm("Are you sure you want to delete this person?")) {
            let personId = $(this).data('id');
    
            $.ajax({
                url: 'action/trainee.php',
                type: 'POST',
                data: {
                    type: 'delete_person',
                    person_id: personId
                },
                success: function (response) {
                    if (response.trim() === "success") {
                        alert("Trainee deleted!");
                        $('#editTraineeModal').modal('hide');
                        location.reload(); // Or reload only table if using DataTables
                    } else {
                        alert("Deletion failed: " + response);
                    }
                }
            });
        }
    });

  

