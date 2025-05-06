<?php
include('../include/config.php');

// delete trainee

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['type']) && $_POST['type'] == 'delete_person') {

    $personId = $_POST['person_id'];

    $stmt = $conn->prepare("UPDATE person SET status = 'inactive' WHERE id = ?");
    $stmt->bind_param("i", $personId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    exit;
}
// AJAX Request: Get Fee & Discount
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type'])) {

        if ($_POST['type'] == 'get_fee_discount') {
            header('Content-Type: application/json');

        $course_id = $_POST['course_id'];

        $query = "SELECT fee  FROM course_fee WHERE course_id = '$course_id' ";
        $result = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($result);

        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['fee' => '', 'discount' => '']);
        }
        exit; 
    }

    // AJAX Request: Get Duration
    if ($_POST['type'] == 'get_durations') {
        $course_id = $_POST['course_id'];
        $query = "SELECT DISTINCT duration FROM course WHERE id = '$course_id'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['duration']}'>{$row['duration']}</option>";
            }
        } else {
            echo "<option value=''>No durations found</option>";
        }
        exit;
    }
}


// Main Trainee Add Logic

function flush_multi_results($conn) {
    while ($conn->more_results() && $conn->next_result()) {
    }
}
// 1) ADD TRAINEE
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['hdnAction'])
    && $_POST['hdnAction'] === 'addTrainee') 
{
    $name        = $_POST['name'];
    $phone       = $_POST['phone'];
    $email       = $_POST['pemail'];
    $regno       = $_POST['regno'];
    $dob         = $_POST['dob'];
    $doj         = $_POST['doj'];
    $gender      = $_POST['gender'];
    $bloodgroup  = $_POST['blood_group'];
    $address     = $_POST['address'];
    $profilePath = '';

    if (!empty($_FILES['profile']['name'])) {
        $targetDir     = '../assets/images/profile';
        $saveDir       = 'assets/images/profile';
        $filename      = basename($_FILES['profile']['name']);
        $ext           = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedTypes  = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowedTypes)) {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fullTarget    = "$targetDir/$filename";
            $webPath       = "$saveDir/$filename";

            if (move_uploaded_file($_FILES['profile']['tmp_name'], $fullTarget)) {
                $profilePath = $webPath;
            } else { 
                exit('Failed to upload profile image.');
            }
        } else {
            exit('Invalid image format. Allowed: JPG, JPEG, PNG, GIF.');
        }
    }

    $call = "CALL add_trainee(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($call);
    $stmt->bind_param(
        "ssssssssss",
        $name, $phone, $email, $regno,
        $dob, $doj, $gender, $bloodgroup,
        $address, $profilePath
    );

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    flush_multi_results($conn);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['hdnAction'])
    && $_POST['hdnAction'] === 'editTrainee') 
{
    $personId    = $_POST['edit_person_id'];
    $name        = $_POST['uname'];
    $email       = $_POST['uemail'];
    $regno       = $_POST['uregno'];
    $phone       = $_POST['uphone'];
    $address     = $_POST['uaddress'];
    $dob         = $_POST['udob'];
    $doj         = $_POST['udoj'];
    $gender      = $_POST['ugender'];
    $bloodgroup  = $_POST['ublood_group'];
    $profilePath = ''; 

    if (!empty($_FILES['uprofile']['name'])) {
        $targetDir    = '../assets/images/profile';
        $saveDir      = 'assets/images/profile';
        $filename     = basename($_FILES['uprofile']['name']);
        $ext          = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowedTypes)) {
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fullTarget = "$targetDir/$filename";
            $webPath    = "$saveDir/$filename";
            if (move_uploaded_file($_FILES['uprofile']['tmp_name'], $fullTarget)) {
                $profilePath = $webPath;
            } else {
                exit('Failed to upload profile image.');
            }
        } else {
            exit('Invalid image format. Allowed: JPG, JPEG, PNG, GIF.');
        }
    }

    $call = "CALL edit_trainee(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($call);
    $stmt->bind_param(
        "issssssssss",
        $personId, $name, $email, $regno,
        $phone, $address, $dob, $doj,
        $gender, $bloodgroup, $profilePath
    );

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    flush_multi_results($conn);
    exit;
}


if (isset($_GET['id'])) {
    header('Content-Type: application/json');
    $call = "CALL get_trainee_by_id(?)";
    $stmt = $conn->prepare($call);
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();

    $result = $stmt->get_result();
    $data   = $result->fetch_assoc();

    $stmt->close();
    flush_multi_results($conn);

    echo json_encode($data);
    exit;
}

// filter
$course_id = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;
$join_date = isset($_POST['doj']) ? $_POST['doj'] : '';

$query = "
    SELECT DISTINCT
        ft.person_id,
        ft.person_name,
        ft.phone_number,
        ft.email,
        ft.course_id,
        ft.doj
    FROM filtertrainee AS ft
    WHERE ft.course_id = $course_id
";

if (!empty($join_date)) {
    $join_date = mysqli_real_escape_string($conn, $join_date);
    $query .= " AND DATE(ft.doj) = '$join_date'";
}

$query .= " ORDER BY ft.doj DESC";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "
        <tr>
          <td>{$i}</td>
          <td>{$row['person_name']}</td>
          <td>{$row['phone_number']}</td>
          <td>{$row['email']}</td>
          <td>
            <button class='coustome_btn text-primary' 
                    onclick=\"window.location.href='viewTrainee.php?id={$row['person_id']}';\">
              <i class='lni lni-eye'></i>
            </button>
            <button class='coustome_btn text-success' 
                    onclick='editTrainee({$row['person_id']});'
                    data-bs-toggle='modal' data-bs-target='#editTraineeModal'>
              <i class='lni lni-pencil'></i>
            </button>
            <button class='coustome_btn text-danger delete-person' 
                    data-id='{$row['person_id']}'>
              <i class='lni lni-trash'></i>
            </button>
          </td>
        </tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='5'>No trainee found</td></tr>";
}

?>


