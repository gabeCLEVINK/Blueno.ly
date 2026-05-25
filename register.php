<?php

header("Content-Type: application/json");

ini_set('display_errors',1);
error_reporting(E_ALL);

require "db.php";

/*
    data
*/

$username =
isset($_POST['username'])
? trim($_POST['username'])
: '';

$email =
isset($_POST['email'])
? trim($_POST['email'])
: '';

$password =
isset($_POST['password'])
? trim($_POST['password'])
: '';

$bio =
isset($_POST['bio'])
? trim($_POST['bio'])
: '';

/*
    validate
*/

if($username == '' ||
   $email == '' ||
   $password == '')
{
    echo json_encode(array(
        "success" => false,
        "message" => "Missing Fields"
    ));

    exit;
}

/*
    user exists
*/

$check =
$db->prepare("
SELECT id
FROM users
WHERE username = ?
OR email = ?
LIMIT 1
");

$check->execute(array(
    $username,
    $email
));

if($check->fetch())
{
    echo json_encode(array(
        "success" => false,
        "message" => "User Exists"
    ));

    exit;
}

/*
    avatar
*/

$avatarPath = '';

if(isset($_FILES['avatar']))
{
    if(!is_dir("avatars"))
    {
        mkdir("avatars");
    }

    $extension =
    pathinfo(
    $_FILES['avatar']['name'],
    PATHINFO_EXTENSION);

    if($extension == '')
    {
        $extension = 'jpg';
    }

    $filename =
    time() .
    "_" .
    rand(1000,9999) .
    "." .
    $extension;

    $target =
    "avatars/" .
    $filename;

    move_uploaded_file(
    $_FILES['avatar']['tmp_name'],
    $target);

    $avatarPath =
    $target;
}

/*
    create user
*/

$password =
md5($password);

$token =
md5(time() . rand());

$insert =
$db->prepare("
INSERT INTO users
(
username,
email,
password,
token,
avatar,
bio
)

VALUES
(
?,
?,
?,
?,
?,
?
)
");

$insert->execute(array(
    $username,
    $email,
    $password,
    $token,
    $avatarPath,
    $bio
));

echo json_encode(array(
    "success" => true,
    "token" => $token
));
?>