<?php

header("Content-Type: application/json");

require "db.php";

$stmt =
$db->query("
SELECT *
FROM videos
ORDER BY id DESC
");

$videos = [];

while($row =
$stmt->fetch(PDO::FETCH_ASSOC))
{

    if(!isset($row["video"]))
    {
        $row["video"] = "";
    }

    if(!isset($row["caption"]))
    {
        $row["caption"] = "";
    }

    if(!isset($row["username"]))
    {
        $row["username"] = "user";
    }

    $videos[] = $row;
}

echo json_encode([
    "success" => true,
    "videos" => $videos
]);
?>
