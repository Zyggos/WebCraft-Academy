<!DOCTYPE html>
<html lang="el">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Αρχική σελίδα</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div id="title-container">
      <h1>Αρχική σελίδα</h1>
    </div>
    <?php session_start(); ?>
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
        <h1>Καλώς Ήρθατε στον Ιστοχώρο του Μαθήματος</h1>
        <p>
          Αυτός ο ιστοχώρος είναι αφιερωμένος στην εκμάθηση και την εφαρμογή της
          HTML. Εδώ θα βρείτε όλα τα απαραίτητα εργαλεία και πληροφορίες για να
          ξεκινήσετε το ταξίδι σας στον κόσμο της δημιουργίας ιστοσελίδων.
        </p>
        <p>
          Στην ενότητα <strong>Ανακοινώσεις</strong>, θα βρείτε τις τελευταίες
          ενημερώσεις και τα νέα του μαθήματος. Η ενότητα
          <strong>Επικοινωνία</strong> περιέχει πληροφορίες για το πώς να
          επικοινωνήσετε με τους διδάσκοντες. Στις <strong>Εργασίες</strong> θα
          βρείτε τις εκφωνήσεις και τις προθεσμίες των εργασιών του μαθήματος.
          Τέλος, στην ενότητα <strong>Έγγραφα μαθήματος</strong>, έχουμε
          συγκεντρώσει όλο το εκπαιδευτικό υλικό που θα χρειαστείτε.
        </p>
      </div>
      <img src="images/logo.png" alt="Logo" />
    </div>
  </body>
</html>
