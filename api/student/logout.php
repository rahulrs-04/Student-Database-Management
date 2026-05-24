<?php
require_once __DIR__ . '/../session_helper.php';
session_start();
unset($_SESSION['student_uid']);
session_destroy();
header('location: ../index.php');
exit();
?>
