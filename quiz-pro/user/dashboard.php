<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Quiz Pro</title>
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
        
        $userRole = $pdo->query("SELECT role FROM users WHERE id = " . (int)$_SESSION['user'])->fetchColumn() ?? 'user';
        ?>
        <h2>Dashboard</h2>
        <div class="dashboard-links">
            <a href="quiz.php">🚀 Start Quiz</a>
            <a href="leaderboard.php">🏆 Leaderboard</a>
            <?php if ($userRole == 'admin'): ?>
                <a href="admin/index.php">⚙️ Admin Panel</a>
            <?php endif; ?>
            <a href="../auth/logout.php" style="background: rgba(245, 101, 101, 0.1); color: #e53e3e;">🚪 Logout</a>
        </div>
    </div>
</body>
</html>
