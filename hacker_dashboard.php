<?php
session_start();
if (!isset($_SESSION['team_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>OASIS Challenges</title>
</head>
<body style="background-color: #000; color: #fff;">
  <h1>Welcome, <?= $_SESSION['team_name'] ?>!</h1>
  <p>Access the Algorithmic Challenges below</p>
  <a href="logout.php">Logout</a>
</body>
</html>
