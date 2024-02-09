<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Tutor') {
    header('Location: login.php');
    exit;
}


$db = new mysqli('localhost', 'root', '', 'my webpage');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_GET['id'])) {
    $documentId = $_GET['id'];


    $stmt = $db->prepare("SELECT filename FROM documents WHERE id = ?");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();
        $filePath = 'documents/' . $document['filename'];


        if (file_exists($filePath) && unlink($filePath)) {

            $deleteStmt = $db->prepare("DELETE FROM documents WHERE id = ?");
            $deleteStmt->bind_param("i", $documentId);
            if ($deleteStmt->execute()) {
                echo "Document and file deleted successfully.";
                header('Location: documents.php');
            } else {
                echo "Error deleting record: " . $db->error;
            }
            $deleteStmt->close();
        } else {
            echo "Error deleting file or file not found.";
        }
    } else {
        echo "Document not found.";
    }
    $stmt->close();
} else {
    echo "No document ID provided.";
}

$db->close();
?>