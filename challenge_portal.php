<?php
session_start();
if (!isset($_SESSION['team_id'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "oasis");

$team_id = $_SESSION['team_id'];

// Get the next available challenge not yet completed
$nextChallengeQuery = "
SELECT c.* FROM challenges c
WHERE c.status='active' AND c.id NOT IN (
  SELECT challenge_id FROM submissions WHERE team_id = $team_id
)
ORDER BY c.id ASC LIMIT 1";

$challenge = $conn->query($nextChallengeQuery)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Challenge Portal | OASIS</title>
  <style>
    body { background: #0a0a23; color: white; font-family: Arial; padding: 30px; }
    textarea, input, select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
    button { background: #00bfff; color: white; padding: 10px 20px; border: none; border-radius: 5px; }
    .section { background: #1e1e3f; padding: 20px; margin-top: 20px; border-radius: 10px; }
  </style>
</head>
<body>
  <h1>Welcome, <?= $_SESSION['team_name'] ?> 👾</h1>

  <?php if (!$challenge): ?>
    <p>🎉 You've completed all available challenges!</p>
  <?php else: ?>
    <div class="section">
      <h2>🚀 Algorithmic Challenge: <?= $challenge['title'] ?></h2>
      <p><?= nl2br($challenge['algorithmic_problem']) ?></p>

      <!-- Flag Submission -->
      <form method="POST" action="submit_flag.php">
        <input type="hidden" name="challenge_id" value="<?= $challenge['id'] ?>">
        <input type="text" name="flag" placeholder="Enter your flag..." required>
        <button type="submit">Submit Flag</button>
      </form>
    </div>

    <?php if (isset($_SESSION['flag_passed']) && $_SESSION['flag_passed'] == $challenge['id']): ?>
      <div class="section">
        <h2>🔓 Buildathon Unlocked!</h2>
        <p><?= nl2br($challenge['buildathon_problem']) ?></p>

        <!-- Buildathon GitHub submission -->
        <form method="POST" action="submit_buildathon.php">
          <input type="hidden" name="challenge_id" value="<?= $challenge['id'] ?>">
          <input type="url" name="github_url" placeholder="Paste your GitHub repo URL..." required>
          <button type="submit">Submit Buildathon</button>
        </form>
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <br><a href="logout.php" style="color: orange;">Logout</a>
</body>
</html>
