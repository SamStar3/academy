<?php

include("../include/config.php");

$person_id = isset($_POST['person_id']) && $_POST['person_id'] > 0 ? (int) $_POST['person_id'] : null;
$payment_date = !empty($_POST['payment_date']) ? $_POST['payment_date'] : null;

$person_id_param = is_null($person_id) ? "NULL" : $person_id;
$payment_date_param = is_null($payment_date) ? "NULL" : "'" . mysqli_real_escape_string($conn, $payment_date) . "'";

$query = "CALL GetPaymentDetails($person_id_param, $payment_date_param)";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$i}</td>
                <td>" . date('d-m-Y', strtotime($row['payment_date'])) . "</td>
                <td>{$row['person_name']}</td>
                <td>{$row['payment_mode']}</td>
                <td>{$row['received_by']}</td>
                <td>₹" . number_format($row['paid_amount'], 2) . "</td>
              </tr>";
        $i++;
    }
    mysqli_next_result($conn); 
} else {
    echo "<tr><td colspan='6'>No payment records found.</td></tr>";
}

?>
