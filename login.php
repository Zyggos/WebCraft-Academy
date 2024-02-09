<?php
session_start();

error_reporting(E_ALL); 
ini_set('display_errors', 1);

$db = new mysqli('localhost', 'root', '', 'my webpage');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $db->real_escape_string($_POST['email']);
    $password = $db->real_escape_string($_POST['password']);

    
    $stmt = $db->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        
        if ($user['role'] == 'Tutor') {
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    } else {
        echo "Invalid email or password.";
    }
    $stmt->close();
}
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="form-container">
    <form class="login-form" method="post">
        <fieldset>
            <legend>Login</legend>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Login">
        </fieldset>
    </form>
</div>
</body>
</html>


