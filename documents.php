<?php
session_start();


$isTutor = isset($_SESSION['user_id']) && $_SESSION['role'] === 'Tutor';


$db = new mysqli('localhost', 'root', '', 'my webpage');
if ($db->connect_error) {
  die("Connection failed: " . $db->connect_error);
}


$query = "SELECT id, title, description, filename FROM documents";
$result = $db->query($query);
?>
<!DOCTYPE html>
<html lang="el">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Έγγραφα μαθήματος</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <div id="title-container">
    <h1>Έγγραφα μαθήματος</h1>
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
        echo "<a href='add_document.php' class='button'>Προσθήκη νέου εγγράφου</a>";
      }


      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<div class='document'>";


          echo "<div class='document-header'>";
          echo "<div class='document-title'><h2>" . htmlspecialchars($row['title']) . "</h2></div>";


          if ($isTutor) {
            echo "<div class='document-actions'>";
            echo "<a href='edit_document.php?id=" . $row['id'] . "' class='button'>Επεξεργασία</a>";
            echo "<a href='delete_document.php?id=" . $row['id'] . "' class='button' onclick='return confirm(\"Are you sure?\");'>Διαγραφή</a>";
            echo "</div>";
          }
          echo "</div>";

          echo "<p>" . htmlspecialchars($row['description']) . "</p>";
          echo "<a href='documents/" . htmlspecialchars($row['filename']) . "' download>Κατέβασε το αρχείο</a>";
          echo "</div>";
        }
      } else {
        echo "<p>No documents found.</p>";
      }


      $db->close();
      ?>
    </div>
    <a href="#top" class="top-link">Επιστροφή στην κορυφή</a>
  </div>

</body>

</html>