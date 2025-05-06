<?php

include("config.php");

// Total Trainees
$traineeCount = 0;
$traineeQuery = mysqli_query($conn, " SELECT * FROM view_role_counts WHERE role_name = 'Trainee' ");
if ($row = mysqli_fetch_assoc($traineeQuery)) {
    $traineeCount = $row['total'];
}

// Total Trainers
$trainerCount = 0;
$trainerQuery = mysqli_query($conn, " SELECT * FROM view_role_counts WHERE role_name = 'Trainer'");
if ($row = mysqli_fetch_assoc($trainerQuery)) {
    $trainerCount = $row['total'];
}


// Total Courses
$courseCount = 0;
$courseQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM course WHERE status = 1");
if ($row = mysqli_fetch_assoc($courseQuery)) {
    $courseCount = $row['total'];
}

// Total Income in This Month
$incomeMonth = 0;
$currentMonth = date('Y-m');
$incomeQuery = mysqli_query($conn, "SELECT SUM(paid_amount) AS total FROM payment WHERE DATE_FORMAT(payment_date, '%Y-%m') = '$currentMonth' AND status = 1");
if ($row = mysqli_fetch_assoc($incomeQuery)) {
    $incomeMonth = $row['total'] ?: 0;
}
?>