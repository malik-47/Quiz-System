<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user']) || $pdo->query("SELECT role FROM users WHERE id = " . (int)$_SESSION['user'])->fetchColumn() != 'admin') {
    http_response_code(403);
    exit('Access denied');
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    if ($stmt->execute([$id])) {
        header('Location: manage_questions.php?deleted=1');
    } else {
        header('Location: manage_questions.php?error=1');
    }
} else {
    http_response_code(400);
    exit('Invalid request');
}
?>
