<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "oasis_db");

$search = $_GET['search'] ?? '';
$searchQuery = $search ? "WHERE team_name LIKE '%$search%'" : '';
$result = $conn->query("SELECT * FROM teams $searchQuery ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Management | OASIS</title>
    <style>
        body { background-color: #0a0a23; color: white; font-family: Arial; padding: 20px; }
        input { padding: 10px; width: 250px; margin-bottom: 20px; border-radius: 5px; border: none; }
        table { width: 100%; border-collapse: collapse; background: #1e1e3f; border-radius: 10px; overflow: hidden; }
        th, td { padding: 10px; border: 1px solid #444; text-align: left; }
        a { color: #00bfff; text-decoration: none; }
    </style>
</head>
<body>
    <h2>👥 Admin Team Management</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by team name..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <tr>
            <th>Team Name</th>
            <th>Email</th>
            <th>Registered At</th>
            <th>Submissions</th>
        </tr>
        <?php while ($team = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $team['team_name'] ?></td>
            <td><?= $team['email'] ?></td>
            <td><?= $team['created_at'] ?></td>
            <td><a href="admin_team_submissions.php?team_id=<?= $team['id'] ?>">View</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
