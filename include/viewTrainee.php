    <?php
    include("config.php");

    if (isset($_GET['id'])) {
        $viewedPersonId = intval($_GET['id']);
    }else if(isset($_GET['person_id'])){
        $viewedPersonId = intval($_GET['person_id']);
    }

    $queryView = "SELECT * FROM viewtrainee WHERE person_id = $viewedPersonId ORDER BY person_course_id DESC";
    $viewTrainee = mysqli_query($conn, $queryView);
    $queryView1 = "SELECT * FROM payments WHERE person_id = $viewedPersonId";
    $viewTrainee1 = mysqli_query($conn, $queryView1);
    $queryView2 = "SELECT * FROM viewtrainee WHERE person_id = $viewedPersonId";
    $viewTrainee2 = mysqli_query($conn, $queryView2);
?>
