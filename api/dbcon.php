<?php

$db_path = __DIR__ . '/sms.db';
$con = null;
$is_readonly = false;

try {
    // Try opening in read-write mode (needed for local development and writes)
    if (!file_exists($db_path)) {
        // Create the database file if it doesn't exist locally
        $con = new SQLite3($db_path, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
    } else {
        $con = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
    }
} catch (Exception $e) {
    // Fall back to read-only mode (needed for Vercel's read-only filesystem)
    try {
        $con = new SQLite3($db_path, SQLITE3_OPEN_READONLY);
        $is_readonly = true;
    } catch (Exception $e2) {
        die("Connection not successful: " . $e2->getMessage());
    }
}

if(!$con) {
    echo "Connection not successful";
    exit();
}

// Automatically initialize SQLite tables and defaults only if NOT in read-only mode
if (!$is_readonly) {
    try {
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
    } catch (Exception $e) {
        // Silently catch write errors during schema setup (just in case)
    }
}

?>
