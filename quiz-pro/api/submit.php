<?php
include "../config/db.php";
$data=json_decode(file_get_contents("php://input"),true);
$score=0;
foreach($data as $id=>$ans){
$c=$pdo->query("SELECT correct_option FROM questions WHERE id=$id")->fetchColumn();
if($c==$ans)$score++;
}
echo json_encode(["score"=>$score]);
?>