<?php
session_start();
$conn = new mysqli("localhost", "root", "", "oasis");

$team_id = $_SESSION['team_id'];
$challenge_id = $_POST['challenge_id'];
$submitted_flag = trim($_POST['flag']);

// Get the correct flag from DB
$check = $conn->query("SELECT flag FROM challenges WHERE id=$challenge_id")->fetch_assoc();

if ($submitted_flag === $check['flag']) {
    // Store the flag match as session variable to unlock buildathon
    $_SESSION['flag_passed'] = $challenge_id;
    header("Location: challenge_portal.php");
} else {
    echo "<script>alert('❌ Incorrect flag. Try again!'); window.location='challenge_portal.php';</script>";
}
?>
