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

if (isset($_GET['id'])) {
    $homeworkId = $_GET['id'];


    $stmt = $db->prepare("DELETE FROM homework WHERE id = ?");
    $stmt->bind_param("i", $homeworkId);


    if ($stmt->execute()) {
        echo "Homework deleted successfully.";
        header('Location: homework.php');
    } else {
        echo "Error deleting homework: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No homework ID provided.";
}

$db->close();
?>