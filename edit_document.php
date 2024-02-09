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

$documentId = $_GET['id'] ?? null;
$document = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $db->real_escape_string($_POST['title']);
    $description = $db->real_escape_string($_POST['description']);
    $documentId = $db->real_escape_string($_POST['document_id']);


    $stmt = $db->prepare("UPDATE documents SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $documentId);
    if ($stmt->execute()) {
        echo "Document updated successfully.";
        header("Location: documents.php");

    } else {
        echo "Error updating document.";
    }
    $stmt->close();
} else if ($documentId) {

    $stmt = $db->prepare("SELECT title, description FROM documents WHERE id = ?");
    $stmt->bind_param("i", $documentId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $document = $result->fetch_assoc();
    } else {
        echo "Document not found.";
        exit;
    }
    $stmt->close();
}


if ($document):
    ?>
    <!DOCTYPE html>
    <html lang="el">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Edit Document</title>
        <link rel="stylesheet" href="style.css" />
    </head>

    <body>
        <div id="title-container">
            <h1>Edit Document</h1>
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
                <?php if ($document): ?>
                    <div class="form-container">
                        <form action="edit_document.php" method="post" class="contact-form">
                            <input type="hidden" name="document_id" value="<?= htmlspecialchars($documentId); ?>">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title" value="<?= htmlspecialchars($document['title']); ?>"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea id="description"
                                    name="description"><?= htmlspecialchars($document['description']); ?></textarea>
                            </div>
                            <input type="submit" value="Update Document" class="submit-button">
                        </form>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </body>

    </html>

<?php endif; ?>