<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Tutor') {
    header('Location: login.php');
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["document"])) {
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $filename = basename($_FILES['document']['name']);
    $filepath = 'documents/' . $filename;


    if ($_FILES['document']['error'] !== UPLOAD_ERR_OK) {
        echo "File upload error code: " . $_FILES['document']['error'];
        exit;
    }


    $db = new mysqli('localhost', 'root', '', 'my webpage');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }


    if (file_exists($filepath)) {
        echo "Sorry, file already exists.";
    } else {

        if (move_uploaded_file($_FILES['document']['tmp_name'], $filepath)) {

            $stmt = $db->prepare("INSERT INTO documents (title, description, filename) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $filename);

            if ($stmt->execute()) {
                echo "The file has been uploaded.";
                header("Location: documents.php");
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }


    $db->close();
}
?>
<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Document</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="title-container">
        <h1>Add Document</h1>
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
                <form action="add_document.php" method="post" enctype="multipart/form-data" class="contact-form">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="document">Document:</label>
                        <input type="file" id="document" name="document" required>
                    </div>
                    <input type="submit" value="Upload Document" class="submit-button">
                </form>
            </div>
        </div>

    </div>

</body>

</html>