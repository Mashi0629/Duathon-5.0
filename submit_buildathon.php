<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oasis_db");

$team_id = $_SESSION['team_id'];
$challenge_id = $_POST['challenge_id'];
$github_url = trim($_POST['github_url']);
$flag = $conn->query("SELECT flag FROM challenges WHERE id=$challenge_id")->fetch_assoc()['flag'];

$conn->query("
    INSERT INTO submissions (team_id, challenge_id, flag, buildathon_url)
    VALUES ($team_id, $challenge_id, '$flag', '$github_url')
");

unset($_SESSION['flag_passed']); // Lock again for next challenge
echo "<script>alert('✅ Buildathon submitted successfully!'); window.location='challenge_portal.php';</script>";
?>
