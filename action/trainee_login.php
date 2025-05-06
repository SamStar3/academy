<?php
include("../include/config.php");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $person_id = $_POST['person_id'];
    $username = $_POST['pusername'];
    $password = $_POST['ppassword'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM person_login WHERE person_id = '$person_id'");

    if (mysqli_num_rows($check) > 0) {
        $update = mysqli_query($conn, "UPDATE person_login SET username='$username', password='$hashedPassword' WHERE person_id='$person_id'");
        if ($update) {
            echo json_encode(['success' => true, 'message' => 'Login updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update login']);
        }
    } else {
        $insert = mysqli_query($conn, "INSERT INTO person_login (person_id, username, password) VALUES ('$person_id', '$username', '$hashedPassword')");
        if ($insert) {
            echo json_encode(['success' => true, 'message' => 'Login inserted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert login']);
        }
    }
}
?>
