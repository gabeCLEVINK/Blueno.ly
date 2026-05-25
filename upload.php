<?php

header("Content-Type: application/json");

require "db.php";

$uploadDir =
__DIR__ . "/uploads/";

if(!file_exists($uploadDir))
{
    mkdir($uploadDir,0777,true);
}

$username =
isset($_POST["username"])
? trim($_POST["username"])
: "";

$title =
isset($_POST["title"])
? trim($_POST["title"])
: "";

$caption =
isset($_POST["caption"])
? trim($_POST["caption"])
: "";

if($username == "")
{
    echo json_encode([
        "success" => false,
        "error" => "Missing Username"
    ]);

    exit;
}

if(!isset($_FILES["video"]))
{
    echo json_encode([
        "success" => false,
        "error" => "No Video"
    ]);

    exit;
}

$extension =
strtolower(
pathinfo(
$_FILES["video"]["name"],
PATHINFO_EXTENSION
));

if($extension == "")
{
    $extension = "mov";
}

/*
    unique filename
*/

$filename =
time() .
rand(1000,9999) .
"." .
$extension;

$target =
$uploadDir . $filename;

if(!move_uploaded_file(
    $_FILES["video"]["tmp_name"],
    $target))
{
    echo json_encode([
        "success" => false,
        "error" => "Upload Failed"
    ]);

    exit;
}

$stmt =
$db->prepare("
INSERT INTO videos
(
username,
title,
caption,
video
)
VALUES
(
?,
?,
?,
?
)
");

$ok =
$stmt->execute([
$username,
$title,
$caption,
$filename
]);

if(!$ok)
{
    echo json_encode([
        "success" => false,
        "error" => "Database Error"
    ]);

    exit;
}

echo json_encode([
    "success" => true,
    "video" => $filename,
    "url" =>
    "http://blueno.ly.gabriknet.online/uploads/" .
    $filename
]);

?>