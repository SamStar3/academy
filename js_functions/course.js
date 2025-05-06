console.log("course.js script is loaded");

$(document).ready(function () {

  // ADD Course
  $('#addCourseForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: 'action/course.php',
      type: 'POST',
      data: $(this).serialize(),
      success: function (response) {
        console.log("Server Response:", response);
        if (response.trim() === 'success') {
          Swal.fire({
            icon: 'success',
            title: 'Course Added',
            text: 'Course has been added successfully!',
            timer: 1500,
            showConfirmButton: false
          });

          document.activeElement?.blur();
          $('#staticBackdrop').modal('hide');
          $('#addCourseForm')[0].reset();
          location.reload();
        } else {
          Swal.fire('Error', response, 'error');
        }
      }
    });
  });

  // UPDATE Course
  $('#editCourseForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: "action/course.php",
      type: "POST",
      data: $(this).serialize(),
      success: function (response) {
        console.log("Update Response:", response);
        if (response.trim() === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: 'Course updated successfully!',
            showConfirmButton: false,
            timer: 2000
          });
          location.reload();
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: response
          });
        }
      },
      error: function () {
        Swal.fire("Error!", "Something went wrong while updating.", "error");
      }
    });
  });

});

// EDIT Course
function goEditcourse(id) {
  document.activeElement?.blur();
  $('#staticBackdrop').modal('hide');

  $.ajax({
    url: "action/course.php",
    type: "POST",
    data: { course_id: id, action: 'fetchCourse' },
    dataType: "json",
    success: function (response) {
      $('#edit_course_id').val(response.course.id);
      $('#edit_course_name').val(response.course.name);
      $('#edit_fee').val(response.coursefee?.fee ?? '');
      $('#edit_duration').val(response.course.duration);
      let html = '';
      response.subjects.forEach(sub => {
        const checked = response.selected_subjects.includes(sub.id.toString()) ? 'checked' : '';
        html += `
          <div class="col-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="subject[]" value="${sub.id}" id="sub_${sub.id}" ${checked}>
              <label class="form-check-label" for="sub_${sub.id}">${sub.name}</label>
            </div>
          </div>
        `;
      });

      $('#edit_subject_list').html(html);
      $('#EditBackdrop').modal('show');
    },
    error: function () {
      Swal.fire("Error!", "Failed to fetch course details.", "error");
    }
  });
}

// DELETE Course
function goDeleteCourse(course_id) {
  Swal.fire({
    title: "Are you sure?",
    text: "This will permanently delete the course.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "action/course.php",
        type: "POST",
        data: { course_id: course_id, action: 'DeleteCourse' },
        success: function (response) {
          if (response.trim() === "success") {
            Swal.fire("Deleted!", "The course has been deleted.", "success");
            setTimeout(() => location.reload(), 1000);
          } else {
            Swal.fire("Error!", response, "error");
          }
        },
        error: function () {
          Swal.fire("Error!", "Failed to delete course.", "error");
        }
      });
    }
  });
}
function loadCourseSubjects(courseId) {
  $.ajax({
    url: 'course.php',
    type: 'POST',
    data: { course_id: courseId },
    dataType: 'json',
    success: function(response) {
      const allSubjects = response.subjects;
      const selectedSubjects = response.selected_subjects;

      let html = '';
      allSubjects.forEach(subject => {
        const isChecked = selectedSubjects.includes(subject.id.toString()) ? 'checked' : '';
        html += `
          <div class="col-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="subjects[]" id="edit_subject_${subject.id}" value="${subject.id}" ${isChecked}>
              <label class="form-check-label" for="edit_subject_${subject.id}">${subject.name}</label>
            </div>
          </div>
        `;
      });

      $('#edit_subject_list').html(html);
    }
  });
}
