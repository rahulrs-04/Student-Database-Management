<?php

$db_path = __DIR__ . '/sms.db';
$con = new SQLite3($db_path);

if(!$con) {
    echo "Connection not successful";
    exit();
}

// Automatically initialize SQLite tables if they do not exist
$con->exec("CREATE TABLE IF NOT EXISTS `admin` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `username` TEXT NOT NULL,
    `password` TEXT NOT NULL
)");

$con->exec("CREATE TABLE IF NOT EXISTS `student` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` TEXT NOT NULL,
    `city` TEXT NOT NULL,
    `pcont` TEXT NOT NULL,
    `standard` INTEGER NOT NULL,
    `rollno` INTEGER NOT NULL,
    `image` TEXT NOT NULL
)");

// Insert default Admin credentials if empty
$adminCount = $con->querySingle("SELECT COUNT(*) FROM `admin`");
if ($adminCount == 0) {
    $con->exec("INSERT INTO `admin` (`username`, `password`) VALUES ('Admin', 'Admin')");
}

?>
