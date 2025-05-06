<?php
include("config.php");

//    CREATE VIEW person_payment AS
//      SELECT  
//         p.id AS person_id,
//         p.name AS person_name,
//         f.paid_amount,
//         f.payment_mode,
//         f.payment_date,
//         p2.name as received_by
//     FROM  payment f
//     JOIN  person_course pc ON f.person_course_id = pc.id
//     JOIN  person p ON pc.person_id = p.id
//     left join person p2 on f.received_by = p2.id
//     ORDER BY  f.payment_date DESC 
  

$queryPayment = "SELECT * from payments";    
$resPayment = mysqli_query($conn, $queryPayment);
?>

