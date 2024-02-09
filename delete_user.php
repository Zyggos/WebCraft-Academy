<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Tutor') {
    header('Location: login.php');
    exit();
}


$db = new mysqli('localhost', 'root', '', 'my webpage');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);


    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);


    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting user: " . $stmt->error;
    }


    $stmt->close();
} else {
    $_SESSION['message'] = "No user ID provided.";
}


$db->close();


header('Location: user_management.php');
exit();
?>