<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Tutor') {
    header('Location: login.php');
    exit;
}


$db = new mysqli('localhost', 'root', '', 'my webpage');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $db->real_escape_string($_POST['title']);
    $goals = $db->real_escape_string($_POST['goals']);
    $deliverables = $db->real_escape_string($_POST['deliverables']);
    $due_date = $db->real_escape_string($_POST['due_date']);
    $file_path = '';


    if (isset($_FILES['homework_file']) && $_FILES['homework_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['homework_file']['tmp_name'];
        $fileName = $_FILES['homework_file']['name'];
        $fileSize = $_FILES['homework_file']['size'];
        $fileType = $_FILES['homework_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));


        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;


        $allowedfileExtensions = array('doc', 'docx', 'pdf', 'txt');

        if (in_array($fileExtension, $allowedfileExtensions)) {

            $uploadFileDir = './homework_files/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $file_path = $dest_path;
            } else {
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $message = 'There was some error in the file upload. Please check the following error.<br>';
        $message .= 'Error:' . $_FILES['homework_file']['error'];
    }


    if ($file_path !== '') {
        $stmt = $db->prepare("INSERT INTO homework (title, goals, deliverables, due_date, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $goals, $deliverables, $due_date, $file_path);

        if ($stmt->execute()) {
            $homework_id = $stmt->insert_id;
            $announcement_date = date('Y-m-d');
            $announcement_subject = 'Υποβλήθηκε η εργασία ' . $homework_id;
            $announcement_message = 'Η ημερομηνία παράδοσης της εργασίας είναι ' . $due_date;
            $announcement_message .= ' <a href="homework.php">Δείτε την εργασία</a>';


            $announcement_stmt = $db->prepare("INSERT INTO announcements (date, subject, message) VALUES (?, ?, ?)");
            $announcement_stmt->bind_param("sss", $announcement_date, $announcement_subject, $announcement_message);
            if ($announcement_stmt->execute()) {
                $message = "New homework and announcement added successfully.";
                header("Location: homework.php");
            } else {
                $message = "Error in adding announcement: " . $announcement_stmt->error;
            }
            $announcement_stmt->close();
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $db->close();
    echo $message;

}
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Homework</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="title-container">
        <h1>Προσθήκη νέας εργασίας</h1>
    </div>
    <div id="content-container">
        <div id="sidebar">
            <div id="nav">
                <a href="index.php" class="button">Αρχική σελίδα</a>
                <a href="announcement.php" class="button">Ανακοινώσεις</a>
                <a href="communication.php" class="button">Επικοινωνία</a>
                <a href="documents.php" class="button">Έγγραφα μαθήματος</a>
                <a href="homework.php" class="button">Εργασίες</a>
                <?php

                if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'Tutor') {
                    echo '<a href="user_management.php" class="button">Διαχείριση Χρηστών</a>';
                }
                ?>
            </div>
        </div>
        <div id="main-content">
            <div class="form-container">
                <form action="add_homework.php" method="post" enctype="multipart/form-data" class="login-form">
                    <fieldset>
                        <legend>Homework Details</legend>
                        <div class="input-group">
                            <label for="title">Τίτλος:</label>
                            <input type="text" id="title" name="title" required>
                        </div>
                        <div class="input-group">
                            <label for="goals">Στόχοι:</label>
                            <textarea id="goals" name="goals" required
                                placeholder="Π.χ. Στόχος1, Στόχος2, Στόχος3"></textarea>
                        </div>
                        <div class="input-group">
                            <label for="deliverables">Παραδοτέα:</label>
                            <textarea id="deliverables" name="deliverables" required
                                placeholder="Π.χ. Παραδοτέο1, Παραδοτέο2, Παραδοτέο3"></textarea>
                        </div>
                        <div class="input-group">
                            <label for="due_date">Ημερομηνία παράδοσης:</label>
                            <input type="date" id="due_date" name="due_date" required>
                        </div>
                        <div class="input-group">
                            <label for="homework_file">Εκφώνηση:</label>
                            <input type="file" id="homework_file" name="homework_file" required>
                        </div>
                        <input type="submit" value="Προσθήκη εργασίας" class="button">
                    </fieldset>
                </form>
            </div>
        </div>

    </div>
</body>

</html>