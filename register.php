<?php
$conn = new mysqli("localhost", "root", "", "oasis_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$team_name = $_POST['team_name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO teams (team_name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $team_name, $email, $password);

if ($stmt->execute()) {
    echo "<script>alert('Team registered successfully!'); window.location='login.html';</script>";
} else {
    echo "<script>alert('Registration failed or team already exists.'); window.location='register.html';</script>";
}

$stmt->close();
$conn->close();
?>
