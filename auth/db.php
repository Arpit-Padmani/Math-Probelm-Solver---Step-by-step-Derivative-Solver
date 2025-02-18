<?php
$host = "localhost";  // Change if necessary
$user = "root";       // Your MySQL username
$pass = "";           // Your MySQL password
$dbname = "math_problem_solver";  // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
