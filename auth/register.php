<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password'])); // Direct password storage

    // Check if email already exists
    $checkQuery = "SELECT user_id FROM users WHERE email = '$email'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: ../signup.php");
        exit();
    }

    // Insert new user
    $sql = "INSERT INTO users (username, email, password) VALUES ('$name', '$email', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../index.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $conn->error . $conn->query($sql);
        echo "Error ",$conn->error,
        // header("Location: ../signup.php");
        exit();
    }

    $conn->close();
}
?>
