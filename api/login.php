<?php
require_once __DIR__ . '/session_helper.php';
session_start();
if(isset($_SESSION['uid'])) {
    header('location:Admin/admindash.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <title>Admin Login - Student Management System</title>
</head>
<body>
    
    <div class="admin-link-top">
        <a href="index.php" class="btn-nav">Back to Home</a>
    </div>

    <div class="glass-container login-card">
        <h1 class="hero-title" style="font-size: 1.75rem; margin-bottom: 0.5rem;">Admin Login</h1>
        <p class="hero-subtitle" style="margin-bottom: 1.5rem;">Enter credentials to access the admin panel</p>
        
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="uname">Username</label>
                <input type="text" name="uname" id="uname" placeholder="Enter Username" required>
            </div>
            
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" name="pass" id="pass" placeholder="Enter Password" required>
            </div>
            
            <div style="margin-top: 1.5rem;">
                <input type="submit" name="login" class="btn-submit" value="Login">
            </div>
        </form>

        <?php
        if(isset($_POST['login'])){
            include('dbcon.php');
            
            $username = $con->escapeString($_POST['uname']);
            $password = $con->escapeString($_POST['pass']);
            
            $qry = "SELECT * FROM `admin` WHERE `username` = '$username' AND `password` = '$password'";
            $run = $con->query($qry);
            $data = $run ? $run->fetchArray(SQLITE3_ASSOC) : null;
            
            if($data) {
                $id = $data['id'];
                
                $_SESSION['uid'] = $id;
                header('location:Admin/admindash.php');
                exit();
            } else {
                echo "<div style='margin-top:1.5rem; padding:0.75rem; border-radius:8px; background:rgba(255, 75, 92, 0.1); border:1px solid rgba(255, 75, 92, 0.2); color:var(--danger); text-align:center; font-size:0.875rem; font-weight:600;'>Invalid Username or Password</div>";
            }
        }
        ?>
    </div>

</body>
</html>