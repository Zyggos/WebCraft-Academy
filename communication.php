<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Επικοινωνία</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="title-container">
        <h1>Επικοινωνία</h1>
    </div>
    <div id="content-container">
        <div id="sidebar">
            <div id="nav">
                <?php session_start(); ?>
                <a href="index.php" class="button">Αρχική σελίδα</a>
                <a href="announcement.php" class="button">Ανακοινώσεις</a>
                <a href="communication.php" class="button active">Επικοινωνία</a>
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
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Database connection
                $db = new mysqli('localhost', 'root', '', 'my webpage');
                if ($db->connect_error) {
                    die("Connection failed: " . $db->connect_error);
                }

                // Prepared statement to avoid SQL injection
                $stmt = $db->prepare("SELECT email FROM users WHERE role = ?");
                $role = 'Tutor';
                $stmt->bind_param("s", $role);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $from_email = filter_input(INPUT_POST, 'sender-email', FILTER_VALIDATE_EMAIL);
                    $subject = trim($_POST['subject']);
                    $message = trim($_POST['message']);

                    if (!$from_email) {
                        echo "<p>Invalid email address. Please go back and try again.</p>";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            $to = $row['email'];
                            $headers = "From: " . $from_email . "\r\n" .
                                "Reply-To: " . $from_email . "\r\n" .
                                "X-Mailer: PHP/" . phpversion();
                            if (mail($to, $subject, $message, $headers)) {
                                echo "<p>Email sent successfully to: " . htmlspecialchars($to) . "</p>";
                            } else {
                                echo "<p>Failed to send email to: " . htmlspecialchars($to) . "</p>";
                            }
                        }
                    }
                } else {
                    echo "<p>No tutor emails found.</p>";
                }
                $db->close();
            }


            ?>
            <div class="communication-method">
                <h2>Αποστολή e-mail μέσω web φόρμας</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="contact-form">
                    <div class="form-group">
                        <label for="sender-email">Αποστολέας Email:</label>
                        <input type="email" id="sender-email" name="sender-email" placeholder="Το email σας" required />
                    </div>
                    <div class="form-group">
                        <label for="subject">Θέμα:</label>
                        <input type="text" id="subject" name="subject" placeholder="Θέμα για το μήνυμα" required />
                    </div>
                    <div class="form-group">
                        <label for="message">Μήνυμα:</label>
                        <textarea id="message" name="message" placeholder="Μήνυμα" required></textarea>
                    </div>
                    <input type="submit" value="Αποστολή" class="submit-button" />
                </form>
            </div>
            <div class="communication-method">
                <h2>Αποστολή e-mail και χρήση e-mail διεύθυνσης</h2>
                <p>Εναλλακτικά μπορείτε να αποστείλετε e-mail στην παρακάτω διεύθυνση ηλεκτρονικού ταχυδρομείου</p>
                <a href="mailto:tutor@csd.auth.test.gr">tutor@csd.auth.test.gr</a>
            </div>
        </div>
    </div>
</body>

</html>