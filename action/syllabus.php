<?php
include("../include/config.php");



//✅ ADD TOPIC USING PROCEDURE
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['hdnAction']) && $_POST['hdnAction'] == 'addTopic') {
    $topic_name = mysqli_real_escape_string($conn, $_POST['topic_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $subject_id = intval($_POST['subject_id']);

    $stmt = $conn->prepare("CALL insert_syllabus(?, ?, ?)");
    $stmt->bind_param("iss", $subject_id, $topic_name, $description);
    echo ($stmt->execute()) ? "Topic added successfully!" : "Error: " . $stmt->error;
    $stmt->close();
    exit;
}

//✅ FETCH SINGLE TOPIC (AJAX for Edit Modal) - NO NEED PROCEDURE
$response = ['status' => 'error', 'data' => null];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fetch_id'])) {
    $id = intval($_POST['fetch_id']);
    $query = "SELECT id, name, description FROM syllabus WHERE id = $id LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $response['status'] = 'success';
        $response['data'] = $row;
    }
    echo json_encode($response);
    exit;
}

//Fetch syllabus data
if (isset($_POST['syllabus_id']) && isset($_POST['action']) && $_POST['action'] == 'fetchSyllabus') {
    $syllabus_id = intval($_POST['syllabus_id']); // safer

    $sql = "SELECT name, description FROM syllabus WHERE id = $syllabus_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'syllabus_name' => $row['name'],
            'description' => $row['description']
        ]);
    } else {
        echo json_encode(["error" => "Syllabus not found"]);
    }
}
//Update syllabus
if (isset($_POST['syllabus_id']) && isset($_POST['hdnAction']) && $_POST['hdnAction'] == 'updateSyllabus') {
    $syllabus_id = intval($_POST['syllabus_id']);
    $syllabus_name = mysqli_real_escape_string($conn, $_POST['syllabus_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $sql = "UPDATE syllabus SET name = '$syllabus_name', description = '$description' WHERE id = $syllabus_id";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}

//Delete syllabus
if (isset($_POST['syllabus_id']) && isset($_POST['action']) && $_POST['action'] == 'deleteSyllabus') {
    $syllabus_id = intval($_POST['syllabus_id']);

    $sql = "DELETE FROM syllabus WHERE id = $syllabus_id";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => "Syllabus deleted successfully"]);
    } else {
        echo json_encode(["error" => "Failed to delete syllabus"]);
    }
}
//=== Upload Study Material ===
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file']) && isset($_POST['syllabus_id'])) {
    $syllabus_id = intval($_POST['syllabus_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $upload_dir = '../uploads/study_materials/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $check = mysqli_query($conn, "SELECT id FROM syllabus WHERE id = $syllabus_id");
    if (mysqli_num_rows($check) == 0) {
        echo json_encode(["error" => "Invalid syllabus ID."]);
        exit;
    }

    $original_file_name = basename($_FILES['file']['name']);
    $file_name = time() . '_' . $original_file_name;
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_path = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $file_path)) {
        $query = "INSERT INTO study_materials (syllabus_id, file_name, description, created_at, status)
                  VALUES ('$syllabus_id', '$file_name', '$description', NOW(), 'Active')";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["success" => "File uploaded and saved successfully."]);
        } else {
            echo json_encode(["error" => "Database error: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["error" => "Failed to move uploaded file."]);
    }
    exit;
}

//=== Fetch Existing Materials ===
if (isset($_POST['syllabus_id']) && isset($_POST['action']) && $_POST['action'] == 'fetchExistingMaterials') {
    $syllabusId = intval($_POST['syllabus_id']);
    $query = "SELECT file_name, description FROM study_materials WHERE syllabus_id = $syllabusId AND status = 'Active' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $materials = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode(["success" => true, "materials" => $materials]);
    } else {
        echo json_encode(["success" => false, "error" => "Failed to fetch materials"]);
    }
    exit;
}

if (isset($_POST['action']) && $_POST['action'] == 'fetchExistingMaterials') {
    $syllabus_id = mysqli_real_escape_string($conn, $_POST['syllabus_id']);

    $query = "SELECT * FROM study_materials WHERE syllabus_id = '$syllabus_id'";
    $result = mysqli_query($conn, $query);

    $materials = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $materials[] = $row;
    }

    echo json_encode(['success' => true, 'materials' => $materials]);
    exit;
}


?>









