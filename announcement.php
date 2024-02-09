<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ανακοινώσεις</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div id="title-container">
        <h1>Ανακοινώσεις</h1>
    </div>
    <div id="content-container">
        <div id="sidebar">
            <div id="nav">
            <?php session_start(); ?>
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
          <?php
            $db = new mysqli('localhost', 'root', '', 'my webpage');
            if ($db->connect_error) {
                die("Connection failed: " . $db->connect_error);
            }

            
            session_start();
            if (!isset($_SESSION['user_id'])) {
                
                echo "Please log in to view announcements.";
                exit;
            }

            
            $isTutor = ($_SESSION['role'] === 'Tutor');

            
            if ($isTutor) {
                echo "<div><a href='add_announcement.php' class='button'>Προσθήκη νέας ανακοίνωσης</a></div>";
            }

            
            $result = $db->query("SELECT * FROM announcements ");
            if ($result->num_rows > 0) {
                while ($announcement = $result->fetch_assoc()) {
                    echo "<div class='announcement'>";
            
                    
                    echo "<div class='announcement-header'>";
                    echo "<div class='announcement-title'><h2>" . htmlspecialchars($announcement['subject']) . "</h2></div>";
            
                    
                    if ($isTutor) {
                        echo "<div class='announcement-actions'>";
                        echo "<a href='edit_announcement.php?id=" . $announcement['id'] . "' class='button'>Επεξεργασία</a>";
                        echo "<a href='delete_announcement.php?id=" . $announcement['id'] . "' class='button' onclick='return confirm(\"Are you sure?\");'>Διαγραφή</a>";
                        echo "</div>"; 
                    }
                    echo "</div>"; 
            
                    echo "<p><strong>Ημερομηνία:</strong> " . date('d/m/Y', strtotime(htmlspecialchars($announcement['date']))) . "</p>";
                    echo $announcement['message'];
                    echo "</div>"; 
                }
            } else {
                echo "<p>No announcements found.</p>";
            }
            
            $db->close();
          ?>



        </div>
    </div>
    <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
</body>
</html>
