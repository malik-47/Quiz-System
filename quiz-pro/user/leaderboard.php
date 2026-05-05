<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Quiz Pro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>🏆 Leaderboard</h2>
        <a href="dashboard.php" style="margin-bottom: 30px; display: inline-block;">← Back to Dashboard</a>
        <?php
        include "../config/db.php";
        $res = $pdo->query("SELECT username, MAX(score) as s FROM users JOIN results ON users.id=results.user_id GROUP BY user_id ORDER BY s DESC LIMIT 10");
        if ($res->rowCount() > 0) {
            echo '<table class="leaderboard-table">';
            echo '<thead><tr><th>Rank</th><th>User</th><th>Best Score</th></tr></thead>';
            echo '<tbody>';
            $rank = 1;
            foreach ($res as $r) {
                echo '<tr>';
                echo '<td>#' . $rank . '</td>';
                echo '<td>' . htmlspecialchars($r['username']) . '</td>';
                echo '<td>' . $r['s'] . '/20</td>';
                echo '</tr>';
                $rank++;
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No scores yet. Be the first to take the quiz!</p>';
        }
        ?>
    </div>
</body>
</html>
