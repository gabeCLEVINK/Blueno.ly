<?php

/*
    connect sqlite
*/

$db = new PDO(
"sqlite:bluenoly.db"
);

$db->setAttribute(
PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION
);

/*
    execute sql automatically
*/

$sql = "

CREATE TABLE IF NOT EXISTS users
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    username TEXT UNIQUE,

    email TEXT UNIQUE,

    password TEXT,

    token TEXT,

    avatar TEXT,

    bio TEXT,

    followers INTEGER DEFAULT 0,

    likes INTEGER DEFAULT 0,

    created_at DATETIME
    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS videos
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    user_id INTEGER,

    username TEXT,

    title TEXT,

    caption TEXT,

    video TEXT,

    thumbnail TEXT,

    likes INTEGER DEFAULT 0,

    views INTEGER DEFAULT 0,

    created_at DATETIME
    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS notifications
(
    id INTEGER PRIMARY KEY AUTOINCREMENT,

    from_user TEXT,

    to_user TEXT,

    type TEXT,

    video_id INTEGER,

    created_at DATETIME
    DEFAULT CURRENT_TIMESTAMP
);

";

/*
    run sql
*/

$db->exec($sql);

/*
    create folders
*/

if(!is_dir("uploads"))
{
    mkdir("uploads");
}

if(!is_dir("avatars"))
{
    mkdir("avatars");
}
?>