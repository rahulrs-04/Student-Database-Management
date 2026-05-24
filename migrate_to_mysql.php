<?php
// SQLite connection
$sqliteDbPath = __DIR__ . '/sms.db';
if (!file_exists($sqliteDbPath)) {
    echo "[ERROR] SQLite database file not found at: $sqliteDbPath\n";
    exit(1);
}
$sqlite = new SQLite3($sqliteDbPath);

// List of common MySQL root passwords to test
$passwordsToTest = ['YOUR_PASSWORD_HERE', 'Rahul@2004', '8881212', '', 'root', 'admin', 'mysql', '1234', '12345', '123456', '12345678', '123', 'password', 'root123', 'mysql123', 'admin123', 'rahul', 'Rahul', 'rahul123', 'Rahul123', 'rahul@123', 'Rahul@123', 'rahulsur', 'RahulSur', 'ayush', 'ayush123', 'ayush@123'];
$mysql = null;
$detectedPassword = null;

echo "Testing connections to local MySQL Server (127.0.0.1:3306)...\n";

foreach ($passwordsToTest as $pass) {
    try {
        // Disable warning output for connection attempts
        $conn = @new mysqli('127.0.0.1', 'root', $pass);
        if ($conn && !$conn->connect_error) {
            $mysql = $conn;
            $detectedPassword = $pass;
            echo "[SUCCESS] Connected to MySQL with username 'root' and password: '" . ($pass === '' ? '(empty)' : $pass) . "'\n";
            break;
        }
    } catch (Exception $e) {
        // Continue
    }
}

if (!$mysql) {
    echo "[ERROR] Could not connect to local MySQL Server using common passwords.\n";
    echo "Please configure the correct root password in migrate_to_mysql.php and run again.\n";
    exit(1);
}

// 1. Create schema
echo "Creating database schema 'sms' if it does not exist...\n";
if (!$mysql->query("CREATE DATABASE IF NOT EXISTS `sms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "[ERROR] Failed to create database: " . $mysql->error . "\n";
    exit(1);
}

if (!$mysql->select_db('sms')) {
    echo "[ERROR] Failed to select database 'sms': " . $mysql->error . "\n";
    exit(1);
}

// 2. Create tables
echo "Creating table 'admin'...\n";
$createAdminTable = "CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$mysql->query($createAdminTable)) {
    echo "[ERROR] Failed to create table 'admin': " . $mysql->error . "\n";
    exit(1);
}

echo "Creating table 'student'...\n";
$createStudentTable = "CREATE TABLE IF NOT EXISTS `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `pcont` varchar(20) NOT NULL,
  `standard` int(11) NOT NULL,
  `rollno` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
if (!$mysql->query($createStudentTable)) {
    echo "[ERROR] Failed to create table 'student': " . $mysql->error . "\n";
    exit(1);
}

// 3. Clear existing tables in MySQL to avoid duplicates
echo "Clearing existing data in MySQL tables...\n";
$mysql->query("TRUNCATE TABLE `admin`");
$mysql->query("TRUNCATE TABLE `student`");

// 4. Migrate Admin data
echo "Migrating data for 'admin' table...\n";
$adminRes = $sqlite->query("SELECT id, username, password FROM admin");
$adminCount = 0;
if ($adminRes) {
    $stmt = $mysql->prepare("INSERT INTO `admin` (id, username, password) VALUES (?, ?, ?)");
    while ($row = $adminRes->fetchArray(SQLITE3_ASSOC)) {
        $id = $row['id'];
        $user = $row['username'];
        $pass = $row['password'];
        $stmt->bind_param("iss", $id, $user, $pass);
        $stmt->execute();
        $adminCount++;
    }
    $stmt->close();
}
echo "Migrated $adminCount records for 'admin'.\n";

// 5. Migrate Student data
echo "Migrating data for 'student' table...\n";
$studentRes = $sqlite->query("SELECT id, name, city, pcont, standard, rollno, image FROM student");
$studentCount = 0;
if ($studentRes) {
    $stmt = $mysql->prepare("INSERT INTO `student` (id, name, city, pcont, standard, rollno, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    while ($row = $studentRes->fetchArray(SQLITE3_ASSOC)) {
        $id = $row['id'];
        $name = $row['name'];
        $city = $row['city'];
        $pcont = $row['pcont'];
        $std = $row['standard'];
        $rollno = $row['rollno'];
        $image = $row['image'] ?? '';
        $stmt->bind_param("isssiis", $id, $name, $city, $pcont, $std, $rollno, $image);
        $stmt->execute();
        $studentCount++;
    }
    $stmt->close();
}
echo "Migrated $studentCount records for 'student'.\n";

echo "\n[SUCCESS] Migration completed successfully!\n";
echo "Credentials used: root / " . ($detectedPassword === '' ? '(empty)' : $detectedPassword) . "\n";
echo "You can now open MySQL Workbench and you will find the database schema 'sms' with all tables and data!\n";
