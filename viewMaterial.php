<?php
// Sanitize input
$file = basename($_GET['file']);
$description = htmlspecialchars($_GET['desc'] ?? '', ENT_QUOTES);

$filepath = "uploads/study_materials/" . $file;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Study Material</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        iframe {
            width: 100%;
            height: 90vh;
            border: none;
        }
    </style>
</head>
<body>
<div class="container py-4">

    <h5>Preview</h5>
    <?php if (file_exists($filepath) && strtolower(pathinfo($filepath, PATHINFO_EXTENSION)) === 'pdf'): ?>
        <iframe src="<?php echo $filepath; ?>"></iframe>
    <?php else: ?>
        <div class="alert alert-danger">This file can't be previewed. Only PDFs are supported for inline viewing.</div>
        <a href="<?php echo $filepath; ?>" class="btn btn-success" download>Download File</a>
    <?php endif; ?>
</div>
</body>
</html>
