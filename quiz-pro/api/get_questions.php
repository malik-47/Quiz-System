<?php
include "../config/db.php";
echo json_encode($pdo->query("SELECT * FROM questions")->fetchAll(PDO::FETCH_ASSOC));
?>