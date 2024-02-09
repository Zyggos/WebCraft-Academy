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

$homeworkId = $_GET['id'] ?? null;
$homework = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $db->real_escape_string($_POST['title']);
    $goals = $db->real_escape_string($_POST['goals']);
    $deliverables = $db->real_escape_string($_POST['deliverables']);
    $due_date = $db->real_escape_string($_POST['due_date']);
    $homeworkId = $db->real_escape_string($_POST['homework_id']);


    $stmt = $db->prepare("UPDATE homework SET title = ?, goals = ?, deliverables = ?, due_date = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $goals, $deliverables, $due_date, $homeworkId);
    if ($stmt->execute()) {
        echo "Homework updated successfully.";
        header("Location: homework.php");
    } else {
        echo "Error updating homework.";
    }
    $stmt->close();
} else if ($homeworkId) {

    $stmt = $db->prepare("SELECT title, goals, deliverables, due_date FROM homework WHERE id = ?");
    $stmt->bind_param("i", $homeworkId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $homework = $result->fetch_assoc();
    } else {
        echo "Homework not found.";
        exit;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Homework</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php if ($homework): ?>
        <div id="title-container">
            <h1>Edit Homework</h1>
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
                    // Check if the user is logged in and is a tutor
                    if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'Tutor') {
                        echo '<a href="user_management.php" class="button">Διαχείριση Χρηστών</a>';
                    }
                    ?>
                </div>
            </div>
            <div id="main-content">

                <div class="form-container">
                    <form action="edit_homework.php" method="post" class="login-form">
                        <fieldset>
                            <legend>Homework Details</legend>
                            <input type="hidden" name="homework_id" value="<?= htmlspecialchars($homeworkId); ?>">
                            <div class="input-group">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title"
                                    value="<?= htmlspecialchars($homework['title']); ?>" required>
                            </div>
                            <div class="input-group">
                                <label for="goals">Goals:</label>
                                <textarea id="goals" name="goals"><?= htmlspecialchars($homework['goals']); ?></textarea>
                            </div>
                            <div class="input-group">
                                <label for="deliverables">Deliverables:</label>
                                <textarea id="deliverables"
                                    name="deliverables"><?= htmlspecialchars($homework['deliverables']); ?></textarea>
                            </div>
                            <div class="input-group">
                                <label for="due_date">Due Date:</label>
                                <input type="date" id="due_date" name="due_date"
                                    value="<?= htmlspecialchars($homework['due_date']); ?>" required>
                            </div>
                            <input type="submit" value="Update Homework" class="button">
                        </fieldset>
                    </form>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>

</html>