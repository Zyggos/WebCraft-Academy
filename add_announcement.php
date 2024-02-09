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
    
    $subject = $db->real_escape_string($_POST['subject']);
    $message = $db->real_escape_string($_POST['message']);
    $date = date('Y-m-d'); 

   
    $stmt = $db->prepare("INSERT INTO announcements (date, subject, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $date, $subject, $message);
    if ($stmt->execute()) {
        echo "New announcement added successfully.";
        header("Location: announcement.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="title-container">
        <h1>Add New Announcement</h1>
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
                <form action="add_announcement.php" method="post" class="login-form">
                    <fieldset>
                        <legend>Announcement Details</legend>
                        <div class="input-group">
                            <label for="subject">Subject:</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="input-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <input type="submit" value="Submit" class="button">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
 
</body>
</html>
