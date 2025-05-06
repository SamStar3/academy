
console.log("paymentTrainee.js script is loaded");

$(document).ready(function() {
  $('#paymentForm').on('submit', function(e) {
    e.preventDefault(); // Stop normal form submission

    $.ajax({
      url: 'action/paymentTrainee.php', // Your PHP file path
      type: 'POST',
      data: $(this).serialize(),
      success: function(response) {
        alert(response); // Show success message
        $('#PaymenttraineeModal').modal('hide'); // Close modal
        $('#paymentForm')[0].reset(); // Reset form
      },
      error: function(xhr, status, error) {
        alert("Something went wrong: " + error);
      }
    });
  });
});

