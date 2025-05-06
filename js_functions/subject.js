console.log("subject.js script is loaded");

// Add Subject AJAX
$(document).ready(function () {
  $('#addSubject').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
      url: 'action/subject.php',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          alert(response.message); 
          $('#addSubjectModal').modal('hide');
          $('#addSubject')[0].reset();
          location.reload(); 
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert('Something went wrong. Please try again.');
      }
    });
  });
});

// Load subjects into view
function loadSubjects() {
  $.ajax({
    url: 'action/subject.php',
    type: 'POST',
    data: { action: 'getSubjects' },
    dataType: 'json',
    success: function (subjects) {
      let html = '';
      subjects.forEach(sub => {
        html += `
          <div class="col-md-6 mb-4">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">${sub.name}</h5>
                <button type="button" class="btn btn-success text-white mb-2"
                  onclick="window.location.href='syllabus.php?id=${sub.id}'">
                  <i class="bx bx-book-open"></i> View Syllabus
                </button>
              </div>
            </div>
          </div>`;
      });
      $('#subject-content .row').html(html);
    }
  });
}

// Call loadSubjects when the page is ready
$(document).ready(function () {
  loadSubjects();  // This ensures subjects load when the page is loaded
});

