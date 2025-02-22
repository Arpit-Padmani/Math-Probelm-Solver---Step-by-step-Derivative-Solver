<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, trim($_POST['email'])); // Sanitize email input
    $password = mysqli_real_escape_string($conn, trim($_POST['password'])); // Sanitize password input

    // SQL query to fetch user data based on email
    $sql = "SELECT user_id , username, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Check if entered password matches stored password (no hashing)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['username'];
            // echo "Correct password!" . $user['password'] . $password;
            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password! ".$user['password'];
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with this email!";
        // echo "No account found with this email!!";
        header("Location: ../index.php");
        exit();
    }

    $conn->close();
}
?>
