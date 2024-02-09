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

$announcement = ['id' => '', 'subject' => '', 'message' => ''];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    
    $stmt = $db->prepare("UPDATE announcements SET subject = ?, message = ? WHERE id = ?");
    $stmt->bind_param("ssi", $subject, $message, $id);

    
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    if ($stmt->execute()) {
        $stmt->close();
        $db->close();
        header("Location: announcement.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $db->prepare("SELECT id, subject, message FROM announcements WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $announcement = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div id="title-container">
        <h1>Edit Announcement</h1>
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
                <form action="edit_announcement.php" method="post" class="login-form">
                    <fieldset>
                        <legend>Announcement Details</legend>
                        <div class="input-group">
                            <label for="subject">Subject:</label>
                            <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($announcement['subject']); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="message">Message:</label>
                            <textarea id="message" name="message" required><?php echo htmlspecialchars($announcement['message']); ?></textarea>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">
                        <input type="submit" value="Update Announcement" class="button">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
