<?php

header("Content-Type: application/json");

require "db.php";

/*
    username
*/

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

/*
    user
*/

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

/*
    bio fallback
*/

if(!isset($user["bio"]))
{
    $user["bio"] = "";
}

/*
    avatar fix
*/

if(isset($user["avatar"]) &&
   $user["avatar"] != "")
{
    /*
        if already full url
    */

    if(strpos(
       $user["avatar"],
       "http") !== 0)
    {
        $user["avatar"] =
        "http://blueno.ly.gabriknet.online/uploads/" .
        $user["avatar"];
    }
}
else
{
    $user["avatar"] = "";
}

/*
    videos
*/

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
    /*
        fallbacks
    */

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

/*
    response
*/

echo json_encode([
    "success" => true,
    "user" => $user,
    "videos" => $videos
]);
?>