<?php
include("../include/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && isset($_POST['hdnAction'])
    && $_POST['hdnAction'] == 'addPaymentTrainee') {

    $paymentDate   = $_POST['paymentDate'];       
    $amount        = floatval($_POST['amount']); 
    $person_id     = intval($_POST['paymentperson_id']);
    $paymentMode   = $_POST['paymentMode'];       
    $receivedBy    = 1;                           

    $sqlCheck = "SELECT id FROM person_course WHERE person_id = ?";
    $stmt1    = $conn->prepare($sqlCheck);
    $stmt1->bind_param("i", $person_id);
    $stmt1->execute();
    $res = $stmt1->get_result();
    if ($res->num_rows === 0) {
        echo "Error: No course found for this person.";
        exit;
    }
    $row = $res->fetch_assoc();
    $personCourseId = $row['id'];
    $stmt1->close();

    $spCall = "CALL AddPaymentTrainees(?, ?, ?, ?, ?)";
    $stmt2  = $conn->prepare($spCall);
    $stmt2->bind_param(
        "idssi",
        $personCourseId,    
        $amount,            
        $paymentMode,       
        $paymentDate,      
        $receivedBy        
    );

    if ($stmt2->execute()) {
        echo "Payment added successfully.";
    } else {
        echo "Error: " . $stmt2->error;
    }
    $stmt2->close();
}


// add course


error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hdnAction']) && $_POST['hdnAction'] == 'addCourseTrainee') {

    $tcourse = $_POST['tcourse'];
    $tduration = $_POST['tduration'];
    $tfee = $_POST['tfee'];
    $tdiscount = $_POST['tdiscount'];
    $personCourseId = $_POST['courseperson_id'];

    // Step 1: Get course_fee.id based on selected course and fee
    $sql = "SELECT id FROM course_fee WHERE course_id = ? AND fee = ?";
    $stmtCheck = $conn->prepare($sql);
    $stmtCheck->bind_param("id", $tcourse, $tfee);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $CoursefeeId = $row['id'];
    } else {
        echo "Error: Course fee not found.";
        exit;
    }
    $stmtCheck->close();

    // Step 2: Check if same course is already added for this person
    $sqlCheck = "SELECT id FROM person_course WHERE person_id = ? AND course_fee_id IN (SELECT id FROM course_fee WHERE course_id = ?)";
    $stmtExist = $conn->prepare($sqlCheck);
    $stmtExist->bind_param("ii", $personCourseId, $tcourse);
    $stmtExist->execute();
    $resultExist = $stmtExist->get_result();

    if ($resultExist->num_rows > 0) {
        // Course exists — update
        $rowExist = $resultExist->fetch_assoc();
        $existingId = $rowExist['id'];

        echo "Updating record ID: $existingId <br>";
        echo "New course_fee_id: $CoursefeeId, Discount: $tdiscount <br>";

        $sqlUpdate = "UPDATE person_course SET course_fee_id = ?, fee_amount = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("idi", $CoursefeeId, $tdiscount, $existingId);

        if ($stmtUpdate->execute()) {
            if ($stmtUpdate->affected_rows > 0) {
                echo "Course updated successfully.";
            } else {
                echo "No changes made. (Same values as before)";
            }
        } else {
            echo "Update error: " . $stmtUpdate->error;
        }
        $stmtUpdate->close();

    } else {
        // New course — insert
        $query = "INSERT INTO person_course (person_id, course_fee_id, fee_amount) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iid", $personCourseId, $CoursefeeId, $tdiscount);

        if ($stmt->execute()) {
            echo "Course added successfully.";
        } else {
            echo "Insert error: " . $stmt->error;
        }
        $stmt->close();
    }

    $stmtExist->close();
    $conn->close();
}








// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hdnAction']) && $_POST['hdnAction'] == 'addCourseTrainee') {
//     $tcourse = $_POST['tcourse'];
//     $tduration = $_POST['tduration'];
//     $tfee = $_POST['tfee'];
//     $tdiscount = $_POST['tdiscount'];
//     $personCourseId = $_POST['courseperson_id'];

//     $sql = "SELECT id FROM course_fee WHERE course_id = ?  and fee = ?";
//     $stmtCheck = $conn->prepare($sql);
//     $stmtCheck->bind_param("id", $tcourse,$tfee);
//     $stmtCheck->execute();
//     $result = $stmtCheck->get_result();

//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $CoursefeeId = $row['id'];
//     } else {
//         echo "Error: Course not found for this person.";
//         exit;
//     }
//     $stmtCheck->close();

//     $query = "INSERT INTO person_course (person_id, course_fee_id, fee_amount) 
//     VALUES (?, ?, ?)";

//     $stmt = $conn->prepare($query);
//     $stmt->bind_param("iid", $personCourseId, $CoursefeeId, $tdiscount);

//     if ($stmt->execute()) {
//     echo "Course Added successfully";
//     } else {
//     echo "error: " . $stmt->error;
//     }

//     $stmt->close();
//     $conn->close();
// }
?>
