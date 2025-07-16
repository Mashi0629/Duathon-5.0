<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "oasis_db");

// Get team count
$teamResult = $conn->query("SELECT COUNT(*) AS total_teams FROM teams");
$teamCount = $teamResult->fetch_assoc()['total_teams'];

// Get active challenges
$challengeResult = $conn->query("SELECT COUNT(*) AS active_challenges FROM challenges WHERE status='active'");
$challengeCount = $challengeResult->fetch_assoc()['active_challenges'];

// Get total submissions
$submissionResult = $conn->query("SELECT COUNT(*) AS total_submissions FROM submissions");
$submissionCount = $submissionResult->fetch_assoc()['total_submissions'];

// Get leaderboard (top 5 teams by number of submissions)
$leaderboard = $conn->query("
  SELECT teams.team_name, COUNT(submissions.id) AS submissions_count
  FROM submissions
  JOIN teams ON submissions.team_id = teams.id
  GROUP BY teams.id
  ORDER BY submissions_count DESC
  LIMIT 5
");

?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard | OASIS</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background: #0a0a23; color: white; font-family: Arial, sans-serif; text-align: center; padding: 20px; }
    .card { background: #1e1e3f; display: inline-block; padding: 20px; margin: 20px; border-radius: 10px; width: 200px; }
  </style>
</head>
<body>
  <h1>Welcome Admin: <?= $_SESSION['admin_username'] ?></h1>

  <div class="card">
    <h3>👥 Teams</h3>
    <p><?= $teamCount ?></p>
  </div>

  <div class="card">
    <h3>📚 Challenges</h3>
    <p><?= $challengeCount ?></p>
  </div>

  <div class="card">
    <h3>📩 Submissions</h3>
    <p><?= $submissionCount ?></p>
  </div>

  <h2>🏆 Leaderboard Snapshot</h2>
  <canvas id="leaderboardChart" width="400" height="200"></canvas>

  <script>
    const ctx = document.getElementById('leaderboardChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php
              $names = [];
              $counts = [];
              while ($row = $leaderboard->fetch_assoc()) {
                  $names[] = "'" . $row['team_name'] . "'";
                  $counts[] = $row['submissions_count'];
              }
              echo implode(",", $names);
            ?>],
            datasets: [{
                label: 'Submissions',
                data: [<?= implode(",", $counts) ?>],
                backgroundColor: '#00bfff'
            }]
        },
        options: {
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { display: false } }
        }
    });
  </script>

  <br><a href="admin_logout.php" style="color: orange;">Logout</a>
</body>
</html>

