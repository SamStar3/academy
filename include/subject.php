
<!-- CREATE VIEW subject_with_syllabus AS
SELECT 
    subject.id AS subject_id,
    subject.name AS subject_name,
    syllabus.id AS syllabus_id,
    syllabus.status AS syllabus_status,
    syllabus.description AS syllabus_description,
    syllabus.created_at AS syllabus_created_at
FROM subject
LEFT JOIN syllabus ON subject.id = syllabus.subject_id; -->
<?php 
include("config.php");
$selQuery = "SELECT * FROM `subject_with_syllabus`";
$res_course = mysqli_query($conn , $selQuery);

// Group syllabus by subject
$subjects = [];
while ($row = mysqli_fetch_assoc($res_course)) {
    $subjectId = $row['subject_id'];
    if (!isset($subjects[$subjectId])) {
        $subjects[$subjectId] = [
            'name' => $row['subject_name'],
            'id' => $subjectId,
            'syllabus' => [],
        ];
    }

    if (!empty($row['syllabus_id'])) {
        $subjects[$subjectId]['syllabus'][] = [
            'id' => $row['syllabus_id'],
            'description' => $row['syllabus_description'],
        ];
    }
}
?>
