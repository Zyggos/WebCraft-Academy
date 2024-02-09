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

$user = ['id' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'role' => ''];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {

    $stmt = $db->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $role, $id);


    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if ($stmt->execute()) {
        $stmt->close();
        $db->close();
        header("Location: user_management.php");
        exit();
    } else {
        echo "Error updating user record: " . $stmt->error;
    }

    $stmt->close();
} else {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $db->prepare("SELECT id, firstname, lastname, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
        $stmt->close();
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div id="title-container">
        <h1>Edit User</h1>
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
                <form action="edit_user.php" method="post" class="login-form">
                    <fieldset>
                        <legend>User Details</legend>
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <div class="input-group">
                            <label for="firstname">First Name:</label>
                            <input type="text" id="firstname" name="firstname"
                                value="<?php echo htmlspecialchars($user['firstname']); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="lastname">Last Name:</label>
                            <input type="text" id="lastname" name="lastname"
                                value="<?php echo htmlspecialchars($user['lastname']); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="input-group">
                            <label for="role">Role:</label>
                            <select id="role" name="role" required>
                                <option value="Tutor" <?php echo $user['role'] == 'Tutor' ? 'selected' : ''; ?>>Tutor
                                </option>
                                <option value="Student" <?php echo $user['role'] == 'Student' ? 'selected' : ''; ?>>
                                    Student</option>
                            </select>
                        </div>
                        <input type="submit" value="Update User" class="button">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

</body>

</html>