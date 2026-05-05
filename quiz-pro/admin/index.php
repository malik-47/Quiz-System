<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quiz Pro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include "../config/db.php";
        if (!isset($_SESSION['user'])) {
            header("Location: ../auth/login.php");
            exit;
        }
        $role = $pdo->query("SELECT role FROM users WHERE id = " . (int)$_SESSION['user'])->fetchColumn();
        if ($role != 'admin') {
            header("Location: ../user/dashboard.php");
            exit;
        }
        ?>
        <h2>⚙️ Admin Panel</h2>
        <div class="dashboard-links" style="text-align: left;">
            <a href="add_question.php">➕ Add New Question</a>
            <a href="manage_questions.php">📝 Manage Questions</a>
            <a href="../user/leaderboard.php">📊 View Leaderboard</a>
            <a href="../user/dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
