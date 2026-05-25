<?php
header("Content-Type: application/json");
require "db.php";
$token=trim($_POST["token"] ?? "");
$db->prepare("UPDATE users SET token='' WHERE token=?")->execute([$token]);
echo json_encode(["success"=>true]);