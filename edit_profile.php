<?php

header("Content-Type: application/json");

require "db.php";

/*
    username
*/

if(!isset($_POST["username"]))
{
    die(json_encode([
        "success"=>false,
        "message"=>"No Username"
    ]));
}

$username =
trim($_POST["username"]);

$bio =
$_POST["bio"] ?? "";

/*
    avatar
*/

$avatarName = null;

if(isset($_FILES["avatar"]))
{
    if($_FILES["avatar"]["error"] == 0)
    {
        /*
            uploads folder
        */

        if(!file_exists("uploads"))
        {
            mkdir("uploads");
        }

        $ext =
        pathinfo(
        $_FILES["avatar"]["name"],
        PATHINFO_EXTENSION);

        if(!$ext)
        {
            $ext = "png";
        }

        $avatarName =
        "pfp_" .
        time() .
        "." .
        $ext;

        $target =
        "uploads/" .
        $avatarName;

        /*
            move
        */

        if(!move_uploaded_file(
            $_FILES["avatar"]["tmp_name"],
            $target))
        {
            die(json_encode([
                "success"=>false,
                "message"=>"Move Failed"
            ]));
        }
    }
}

/*
    update
*/

if($avatarName)
{
    $stmt =
    $db->prepare("
    UPDATE users
    SET bio=?,
        avatar=?
    WHERE username=?
    ");

    $ok =
    $stmt->execute([
        $bio,
        $avatarName,
        $username
    ]);
}
else
{
    $stmt =
    $db->prepare("
    UPDATE users
    SET bio=?
    WHERE username=?
    ");

    $ok =
    $stmt->execute([
        $bio,
        $username
    ]);
}

if(!$ok)
{
    die(json_encode([
        "success"=>false,
        "message"=>"Database Error"
    ]));
}

echo json_encode([
    "success"=>true
]);
?>