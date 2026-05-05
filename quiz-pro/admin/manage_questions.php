<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .question-row { margin-bottom: 15px; padding: 15px; background: rgba(255,255,255,0.5); border-radius: 10px; }
        .options { font-size: 0.9rem; color: #666; margin-top: 5px; }
        .delete-btn { background: #e53e3e; padding: 5px 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <?php
        session_start();
        include "../config/db.php";
        if (!isset($_SESSION['user']) || $pdo->query("SELECT role FROM users WHERE id = " . (int)$_SESSION['user'])->fetchColumn() != 'admin') {
            header("Location: ../user/dashboard.php");
            exit;
        }

        $questions = $pdo->query("SELECT * FROM questions ORDER BY id DESC")->fetchAll();
        ?>
        <h2>📝 Manage Questions (<?php echo count($questions); ?> total)</h2>
        <a href="index.php">← Back to Admin</a>
        <a href="add_question.php" style="margin-left: 10px;">➕ Add New</a>
        
        <?php if (empty($questions)): ?>
            <p>No questions yet. <a href="add_question.php">Add the first one</a>!</p>
        <?php else: ?>
            <div style="margin-top: 30px;">
                <?php foreach ($questions as $q): ?>
                    <div class="question-row">
                        <strong>ID: <?php echo $q['id']; ?> | Correct: Option <?php echo $q['correct_option']; ?></strong>
                        <p style="margin: 10px 0;"><?php echo htmlspecialchars($q['question']); ?></p>
                        <div class="options">
                            1. <?php echo htmlspecialchars($q['option1']); ?><br>
                            2. <?php echo htmlspecialchars($q['option2']); ?><br>
                            3. <?php echo htmlspecialchars($q['option3']); ?><br>
                            4. <?php echo htmlspecialchars($q['option4']); ?>
                        </div>
                        <form method="POST" action="delete_question.php" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $q['id']; ?>">
                            <button type="submit" name="delete" class="delete-btn" onclick="return confirm('Delete this question?')">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
