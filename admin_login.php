<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oasis");

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    header("Location: admin_dashboard.php");
} else {
    echo "<script>alert('Invalid credentials'); window.location='admin_login.html';</script>";
}

$stmt->close();
$conn->close();
?>
