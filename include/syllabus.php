<?php
include("config.php");
    if(isset($_GET['id'])){
        $id = intval($_GET['id']);
        $queryView = " SELECT * FROM subject_with_syllabus WHERE subject_id = $id";
       
        $viewSubject = mysqli_query($conn, $queryView); 
    } 
?>
