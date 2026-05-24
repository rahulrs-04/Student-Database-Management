<?php
require_once __DIR__ . '/session_helper.php';
session_start();
session_destroy();
header('location:login.php');
?>
