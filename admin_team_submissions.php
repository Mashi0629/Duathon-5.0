<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "oasis_db");

$team_id = $_GET['team_id'];
$team = $conn->query("SELECT * FROM teams WHERE id=$team_id")->fetch_assoc();
$submissions = $conn->query("
    SELECT submissions.*, challenges.title 
    FROM submissions
    JOIN challenges ON submissions.challenge_id = challenges.id
    WHERE team_id = $team_id
    ORDER BY timestamp DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submissions | <?= $team['team_name'] ?> | OASIS</title>
    <style>
        body { background: #0a0a23; color: white; font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; background: #1e1e3f; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px; border: 1px solid #444; text-align: left; }
        a { color: #00bfff; }
    </style>
</head>
<body>
    <h2>📁 Submission History: <?= $team['team_name'] ?></h2>
    <p>Email: <?= $team['email'] ?> | Registered at: <?= $team['created_at'] ?></p>

    <table>
        <tr>
            <th>Challenge</th>
            <th>Flag Submitted</th>
            <th>Buildathon Repo</th>
            <th>Submitted At</th>
        </tr>
        <?php while ($sub = $submissions->fetch_assoc()): ?>
        <tr>
            <td><?= $sub['title'] ?></td>
            <td><?= $sub['flag'] ?></td>
            <td><a href="<?= $sub['buildathon_url'] ?>" target="_blank"><?= $sub['buildathon_url'] ? 'GitHub Link' : 'N/A' ?></a></td>
            <td><?= $sub['timestamp'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br><a href="admin_teams.php">← Back to Teams</a>
</body>
</html>
