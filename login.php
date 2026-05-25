<?php
header("Content-Type: application/json");
require "db.php";
$username=trim($_POST["username"] ?? "");
$password=trim($_POST["password"] ?? "");
$stmt=$db->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
$stmt->execute([$username]);
$user=$stmt->fetch(PDO::FETCH_ASSOC);
if(!$user){echo json_encode(["success"=>false,"message"=>"User Not Found"]);exit;}
if(md5($password)!=$user["password"]){echo json_encode(["success"=>false,"message"=>"Wrong Password"]);exit;}
$token=md5(time().rand());
$db->prepare("UPDATE users SET token=? WHERE id=?")->execute([$token,$user["id"]]);
echo json_encode(["success"=>true,"token"=>$token]);