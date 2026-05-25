<?php

header("Content-Type: application/json");

require "db.php";

if(!isset($_GET["username"]))
{
    die(json_encode([
        "success" => false,
        "message" => "No Username"
    ]));
}

$username =
trim($_GET["username"]);

if($username == "")
{
    die(json_encode([
        "success" => false,
        "message" => "Empty Username"
    ]));
}

$stmt =
$db->prepare("
SELECT
id,
username,
bio,
avatar
FROM users
WHERE username=?
LIMIT 1
");

$stmt->execute([
    $username
]);

$user =
$stmt->fetch(PDO::FETCH_ASSOC);

if(!$user)
{
    die(json_encode([
        "success" => false,
        "message" => "User Not Found"
    ]));
}

if(!isset($user["bio"]))
{
    $user["bio"] = "";
}

if(isset($user["avatar"]) &&
   $user["avatar"] != "")
{

    if(strpos(
       $user["avatar"],
       "http") !== 0)
    {
        $user["avatar"] =
        "http://yoursite.xyz/uploads/" .
        $user["avatar"];
    }
}
else
{
    $user["avatar"] = "";
}

$stmt =
$db->prepare("
SELECT
id,
username,
caption,
video
FROM videos
WHERE username=?
ORDER BY id DESC
");

$stmt->execute([
    $username
]);

$videos = [];

while($row =
$stmt->fetch(PDO::FETCH_ASSOC))
{

    if(!isset($row["caption"]))
    {
        $row["caption"] = "";
    }

    if(!isset($row["video"]))
    {
        $row["video"] = "";
    }

    $videos[] = $row;
}
echo json_encode([
    "success" => true,
    "user" => $user,
    "videos" => $videos
]);
?>
