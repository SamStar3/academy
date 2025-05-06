<?php
include("config.php");

// Validate and get payment_id
$payment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($payment_id > 0) {
    $query = "SELECT * FROM view_payment_receipt WHERE payment_id = $payment_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Extract values safely
        $student_name = htmlspecialchars($row['person_name']);
        $course_name = htmlspecialchars($row['course_name']);
        $date = date("d-M-Y", strtotime($row['payment_date']));
        $method = htmlspecialchars($row['payment_mode']);
        $amount = number_format($row['paid_amount'], 2);
       

    } else {
        echo "<h2 style='color:red; text-align:center;'>Receipt not found.</h2>";
        exit;
    }
} else {
    echo "<h2 style='color:red; text-align:center;'>Invalid payment ID.</h2>";
    exit;
}
?>
























<!-- 
// CREATE VIEW view_payment_receipt AS
// SELECT  
//     pay.id AS payment_id,
//     per.id AS person_id,
//    per.name AS person_name,
//     c.name AS course_name,
//     pay.paid_amount,
//     pay.payment_mode,
//     pay.payment_date,
//     recv.name AS received_by
// FROM payment pay
// JOIN person_course pc ON pay.person_course_id = pc.id
// JOIN person per ON pc.person_id = per.id
// JOIN course_fee cf ON pc.course_fee_id = cf.id
// JOIN course c ON cf.course_id = c.id
// LEFT JOIN person recv ON pay.received_by = recv.id; -->
