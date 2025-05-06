<?php
include("../include/config.php");

if (isset($_POST['hdnAction']) && $_POST['hdnAction'] == 'addSubject') {
    $subject_name = trim($_POST['sub_name']);

    // Check if subject already exists
    $check = mysqli_prepare($conn, "SELECT id FROM subject WHERE name = ?");
    mysqli_stmt_bind_param($check, 's', $subject_name);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Subject already exists.']);
        exit;
    }

    // Insert new subject
    $insert = mysqli_prepare($conn, "INSERT INTO subject (name, created_at, status) VALUES (?, NOW(), 'Active')");
    mysqli_stmt_bind_param($insert, 's', $subject_name);

    if (mysqli_stmt_execute($insert)) {
        echo json_encode(['status' => 'success', 'message' => 'Subject added successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add subject.']);
    }

    exit;
}

// Load Subjects
if (isset($_POST['action']) && $_POST['action'] == 'getSubjects') {
    $subjects = [];
    $res = mysqli_query($conn, "SELECT s.id, s.name, c.name, s.created_at, s.status FROM subject s LEFT JOIN course c ON s.course_id = c.course_id ORDER BY s.id ASC");

    while ($row = mysqli_fetch_assoc($res)) {
        $subjects[] = $row;
    }
    echo json_encode($subjects);
    exit;
}
