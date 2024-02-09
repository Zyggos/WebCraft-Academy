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
    $announcementId = $_GET['id'];

    
    $stmt = $db->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $announcementId);

    
    if ($stmt->execute()) {
        echo "Announcement deleted successfully.";
        header('Location: announcement.php');
    } else {
        echo "Error deleting announcement: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No announcement ID provided.";
}


$db->close();
?>
