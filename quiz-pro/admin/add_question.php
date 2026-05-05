<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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

        $success = '';
        if (isset($_POST['add'])) {
            $question = trim($_POST['question']);
            $opt1 = trim($_POST['option1']);
            $opt2 = trim($_POST['option2']);
            $opt3 = trim($_POST['option3']);
            $opt4 = trim($_POST['option4']);
            $correct = (int)$_POST['correct_option'];
            
            if (!empty($question) && !empty($opt1) && !empty($opt2) && !empty($opt3) && !empty($opt4) && in_array($correct, [1,2,3,4])) {
                $stmt = $pdo->prepare("INSERT INTO questions (question, option1, option2, option3, option4, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$question, $opt1, $opt2, $opt3, $opt4, $correct])) {
                    $success = 'Question added successfully!';
                } else {
                    $error = 'Failed to add question.';
                }
            } else {
                $error = 'All fields required. Correct option 1-4.';
            }
        }
        ?>
        <h2>➕ Add New Question</h2>
        <?php if (isset($success)) echo "<p style='color: #48bb78;'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p style='color: #e53e3e;'>$error</p>"; ?>
        <form method="POST">
            <div class="form-group">
                <textarea name="question" placeholder="Question text..." rows="3" style="width:100%; padding:15px; border:2px solid #e2e8f0; border-radius:10px; resize:vertical; font-size:1rem;" required></textarea>
            </div>
            <div class="form-group">
                <input type="text" name="option1" placeholder="Option 1" required>
            </div>
            <div class="form-group">
                <input type="text" name="option2" placeholder="Option 2" required>
            </div>
            <div class="form-group">
                <input type="text" name="option3" placeholder="Option 3" required>
            </div>
            <div class="form-group">
                <input type="text" name="option4" placeholder="Option 4" required>
            </div>
            <div class="form-group">
                <label>Correct Option:</label><br>
                <select name="correct_option" style="padding:10px; border:2px solid #e2e8f0; border-radius:5px;">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>
            <button type="submit" name="add">Add Question</button>
            <a href="index.php" style="margin-left:20px;">← Back</a>
        </form>
    </div>
</body>
</html>
