<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oasis");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM teams WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['team_id'] = $user['id'];
    $_SESSION['team_name'] = $user['team_name'];
    header("Location: hacker_dashboard.php"); // challenge portal
} else {
    echo "<script>alert('Invalid credentials'); window.location='login.html';</script>";
}

$stmt->close();
$conn->close();
?>
