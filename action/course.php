<?php
include("../include/config.php");

// === 1. ADD COURSE ===
if (isset($_POST['hdnAction']) && $_POST['hdnAction'] === 'add_Course') {
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $subject_ids = $_POST['subjects'] ?? [];
    $duration = $_POST['duration'];
    $fee = $_POST['fee'];
    $effectivefrom = date("Y-m-d");
    $performed_by = "admin"; // You can use session-based user later

    // Check if course already exists
    $check = mysqli_query($conn, "SELECT * FROM course WHERE name = '$course_name'");
    if (mysqli_num_rows($check) > 0) {
        echo "Course already exists!";
        exit;
    }

    // Call stored procedure to add course
    $stmt = $conn->prepare("CALL AddCourse(?, ?, ?, ?)");
    $stmt->bind_param("ssds", $course_name, $duration, $fee, $effectivefrom);
    $stmt->execute();

    $result = $stmt->get_result();
    $courseData = $result->fetch_assoc();
    $course_id = $courseData['course_id'];

    // Free result and flush remaining result sets
    $result->free();
    while ($conn->more_results() && $conn->next_result()) {;}

    // Insert selected subjects
    $subjectStmt = $conn->prepare("INSERT INTO course_subject (course_id, subject_id, created_at, status) VALUES (?, ?, NOW(), 'Active')");
    foreach ($subject_ids as $subject_id) {
        $subjectStmt->bind_param("ii", $course_id, $subject_id);
        $subjectStmt->execute();
    }

    // Log course addition
    $action_type = "Add";
    $table_name = "course";
    $description = "Added course '{$course_name}' (ID: {$course_id})";
    $log_stmt = $conn->prepare("INSERT INTO activity_log (action_type, table_name, record_id, performed_by, description) VALUES (?, ?, ?, ?, ?)");
    $log_stmt->bind_param("ssiss", $action_type, $table_name, $course_id, $performed_by, $description);
    $log_stmt->execute();

    echo "success";
    exit;
}

// === DELETE COURSE ===
if (isset($_POST['action']) && $_POST['action'] === 'DeleteCourse') {
    $course_id = $_POST['course_id'];
    $performed_by = "admin"; // Or from session

    // Step 1: Get course name BEFORE deletion
    $result = $conn->prepare("SELECT name FROM course WHERE id = ?");
    $result->bind_param("i", $course_id);
    $result->execute();
    $res = $result->get_result();
    $course = $res->fetch_assoc();
    $course_name = $course['name'] ?? 'Unknown';

    // Step 2: Delete course
    $stmt = $conn->prepare("CALL DeleteCourse(?)");
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        while ($conn->more_results() && $conn->next_result()) {;}

        // Step 3: Log deletion
        $action_type = "Delete";
        $table_name = "course";
        $description = "Deleted course '{$course_name}' (ID: {$course_id})";
        $log_stmt = $conn->prepare("INSERT INTO activity_log (action_type, table_name, record_id, performed_by, description) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("ssiss", $action_type, $table_name, $course_id, $performed_by, $description);
        $log_stmt->execute();

        echo "success";
    } else {
        echo "Error deleting course: " . $stmt->error;
    }

    $stmt->close();
    exit;
}

// === FETCH COURSE FOR EDIT ===
if (isset($_POST['action']) && $_POST['action'] === 'fetchCourse') {
    $course_id = $_POST['course_id'];

    $conn->multi_query("CALL get_course_details($course_id)");

    $course = $conn->store_result()->fetch_assoc(); $conn->next_result();
    $coursefee = $conn->store_result()->fetch_assoc(); $conn->next_result();
    $subjects = $conn->store_result()->fetch_all(MYSQLI_ASSOC); $conn->next_result();
    $selected_subjects = [];

    $result = $conn->store_result();
    while ($row = $result->fetch_assoc()) $selected_subjects[] = $row['subject_id'];

    echo json_encode([
        'course' => $course,
        'subjects' => $subjects,
        'coursefee' => $coursefee,
        'selected_subjects' => $selected_subjects
    ]);
    exit;
}

// === UPDATE COURSE ===
if ($_POST['hdnAction'] === 'edit_Course') {
    $course_id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $duration = $_POST['duration'] ?? '';
    $new_fee = $_POST['fee'] ?? 0;
    $subjects = $_POST['subject'] ?? [];
    $subjects_str = implode(',', $subjects);
    $performed_by = "admin"; // Or from session

    $stmt = $conn->prepare("CALL update_course(?, ?, ?, ?, ?)");
    $stmt->bind_param("issds", $course_id, $course_name, $duration, $new_fee, $subjects_str);

    try {
        $stmt->execute();

        // Log edit
        $action_type = "Edit";
        $table_name = "course";
        $description = "Edited course '{$course_name}' (ID: {$course_id})";
        $log_stmt = $conn->prepare("INSERT INTO activity_log (action_type, table_name, record_id, performed_by, description) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("ssiss", $action_type, $table_name, $course_id, $performed_by, $description);
        $log_stmt->execute();

        echo "success";
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
    }

    exit;
}
?>
