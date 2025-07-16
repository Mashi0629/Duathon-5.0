<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.html");
    exit;
}
$conn = new mysqli("localhost", "root", "", "oasis_db");

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM challenges WHERE id=$id");
    header("Location: admin_challenges.php");
}

// Handle Add/Edit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $algo = $_POST['algorithmic_problem'];
    $build = $_POST['buildathon_problem'];
    $flag = $_POST['flag'];
    $status = $_POST['status'];
    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Update
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE challenges SET title=?, algorithmic_problem=?, buildathon_problem=?, flag=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $title, $algo, $build, $flag, $status, $id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO challenges (title, algorithmic_problem, buildathon_problem, flag, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $algo, $build, $flag, $status);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: admin_challenges.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Challenge Manager | OASIS</title>
    <style>
        body { font-family: sans-serif; background: #0b0b23; color: white; padding: 20px; }
        form, table { background: #1e1e3f; padding: 15px; border-radius: 10px; width: 90%; margin: 20px auto; }
        input, textarea, select { width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: none; }
        button { padding: 10px 20px; background: #00bfff; border: none; color: white; border-radius: 5px; }
        table { border-collapse: collapse; margin-top: 40px; }
        th, td { padding: 10px; border: 1px solid #555; text-align: left; }
        a { color: orange; text-decoration: none; }
    </style>
</head>
<body>
    <h2>🧩 Admin Challenge Management</h2>

    <!-- Challenge Form -->
    <form method="POST">
        <input type="hidden" name="id" value="<?= $_GET['edit'] ?? '' ?>">
        <input type="text" name="title" placeholder="Challenge Title" required><br>
        <textarea name="algorithmic_problem" placeholder="Algorithmic Problem Description" rows="4"></textarea><br>
        <textarea name="buildathon_problem" placeholder="Buildathon Problem Description" rows="4"></textarea><br>
        <input type="text" name="flag" placeholder="Correct Flag (for unlock)" required><br>
        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select><br>
        <button type="submit">Save Challenge</button>
    </form>

    <!-- Challenges Table -->
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Flag</th>
                <th>Status</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM challenges");
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['title']}</td>
                    <td>{$row['flag']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='admin_challenges.php?edit={$row['id']}'>Edit</a> | 
                        <a href='admin_challenges.php?delete={$row['id']}' onclick='return confirm(\"Delete this challenge?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
