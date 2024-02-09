<?php
session_start();


$isTutor = isset($_SESSION['user_id']) && $_SESSION['role'] === 'Tutor';


$db = new mysqli('localhost', 'root', '', 'my webpage');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}



$homeworkQuery = "SELECT id, title, goals, deliverables, due_date, file_path FROM homework";
$homeworkResult = $db->query($homeworkQuery);
?>
<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Εργασίες</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="title-container">
        <h1>Εργασίες</h1>
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
            <?php

            if ($isTutor) {
                echo "<div><a href='add_homework.php' class='button'>Προσθήκη νέας εργασίας</a></div>";
            }

            if ($homeworkResult && $homeworkResult->num_rows > 0) {
                while ($homework = $homeworkResult->fetch_assoc()) {
                    echo "<div class='homework'>";


                    echo "<div class='homework-header'>";
                    echo "<div class='homework-title'><h2>" . htmlspecialchars($homework['title']) . "</h2></div>";


                    if ($isTutor) {
                        echo "<div class='homework-actions'>";
                        echo "<a href='edit_homework.php?id=" . $homework['id'] . "' class='button'>Επεξεργασία</a>";
                        echo "<a href='delete_homework.php?id=" . $homework['id'] . "' class='button' onclick='return confirm(\"Are you sure?\");'>Διαγραφή</a>";
                        echo "</div>";
                    }
                    echo "</div>";


                    $goalsArray = explode(',', $homework['goals']);
                    echo "<p>Στόχοι: Οι στόχοι της εργασίας είναι</p><ol>";
                    foreach ($goalsArray as $goal) {
                        echo "<li>" . htmlspecialchars(trim($goal)) . "</li>";
                    }
                    echo "</ol>";


                    $deliverablesArray = explode(',', $homework['deliverables']);
                    echo "<p>Παραδοτέα:</p><ol>";
                    foreach ($deliverablesArray as $deliverable) {
                        echo "<li>" . htmlspecialchars(trim($deliverable)) . "</li>";
                    }
                    echo "</ol>";


                    echo "<p>Ημερομηνία παράδοσης: " . htmlspecialchars($homework['due_date']) . "</p>";


                    if (!empty($homework['file_path'])) {
                        echo "<a href='homework_files/" . htmlspecialchars($homework['file_path']) . "' download>Κατέβασε το αρχείο</a>";
                    }



                    echo "</div>";
                }
            } else {
                echo "<p>No homework assignments found.</p>";
            }

            ?>
        </div>
    </div>
    <script>
        function toggleUploadForm() {
            var uploadForm = document.getElementById('upload-form');
            uploadForm.style.display = uploadForm.style.display === 'none' ? 'block' : 'none';
        }
    </script>
    <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
</body>

</html>