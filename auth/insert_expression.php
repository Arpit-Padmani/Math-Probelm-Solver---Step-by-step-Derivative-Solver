<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $expression = mysqli_real_escape_string($conn, $_POST['expression']);
    $derivative = mysqli_real_escape_string($conn, $_POST['derivative']);

    $sql = "INSERT INTO user_wise_history (expression,answer , user_id ) VALUES ('$expression', '$derivative','$user_id')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "expression" => $expression, "derivative" => $derivative]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error"]);
    }

    $conn->close();
}
?>
