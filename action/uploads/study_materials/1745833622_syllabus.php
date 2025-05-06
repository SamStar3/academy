<?php
include("config.php");  
$id = intval($_GET['id']); // Get syllabus ID from the URL

// SQL query to get the syllabus and subject details along with associated study materials
$queryView = "
    SELECT sy.id as syllabus_id, sy.name as syllabus_name, s.name as subject_name, sy.description,
           sm.id as material_id, sm.file_name, sm.description as material_description, sm.created_at, sm.status
    FROM syllabus sy
    JOIN subject s ON sy.subject_id = s.id
    LEFT JOIN study_materials sm ON sm.syllabus_id = sy.id
    WHERE sy.subject_id = $id
";
$viewSubject = mysqli_query($conn, $queryView);
?>
