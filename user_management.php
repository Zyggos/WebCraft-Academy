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


$result = $db->query("SELECT * FROM users");



?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="title-container">
        <h1>User Management</h1>
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
            <a href="add_user.php" class="button">Προσθήκη Χρήστη</a>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php echo htmlspecialchars($user['id']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($user['firstname']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($user['lastname']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($user['role']); ?>
                                </td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="button">Επεξεργασία</a>
                                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="button"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Διαγραφή</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>