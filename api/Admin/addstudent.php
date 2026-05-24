<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

$success_msg = null;
$error_msg = null;

if(isset($_POST['submit'])){
    include('../dbcon.php');
    
    $rollno = $con->escapeString($_POST['rollno']);
    $name = $con->escapeString($_POST['name']);
    $city = $con->escapeString($_POST['city']);
    $pcon = $con->escapeString($_POST['pcon']);
    $std = $con->escapeString($_POST['standard']);
    
    $imagename = "";
    $upload_ok = true;
    
    if (!empty($_FILES['simg']['name'])) {
        $imagename = $_FILES['simg']['name'];
        $tempname = $_FILES['simg']['tmp_name'];
        if (!move_uploaded_file($tempname, "../dataimg/$imagename")) {
            $upload_ok = false;
            $error_msg = "Failed to upload image. Please ensure the target folder is writeable.";
        }
    }
        
    if ($upload_ok) {
        $qry = "INSERT INTO `student`(`name`, `city`, `pcont`, `standard`, `rollno`,`image`) VALUES ('$name','$city','$pcon','$std','$rollno','$imagename')";
        $run = $con->exec($qry);
        
        if($run){
            $success_msg = "Student records inserted successfully!";
        } else {
            $error_msg = "Failed to insert record: " . $con->lastErrorMsg();
        }
    }
}

include('header.php');
include('titleheader.php');
?>

<div class="glass-container" style="max-width: 600px; margin-top: 1rem;">
    <h2 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #fff 30%, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Add Student Records</h2>
    
    <?php if ($success_msg): ?>
        <div style="margin-bottom: 1.5rem; padding: 0.75rem; border-radius: 8px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); text-align: center; font-weight: 600; font-size: 0.9rem;">
            <?php echo $success_msg; ?>
        </div>
    <?php elseif ($error_msg): ?>
        <div style="margin-bottom: 1.5rem; padding: 0.75rem; border-radius: 8px; background: rgba(255, 75, 92, 0.1); border: 1px solid rgba(255, 75, 92, 0.2); color: var(--danger); text-align: center; font-weight: 600; font-size: 0.9rem;">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="addstudent.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="rollno">Roll No</label>
            <input type="text" name="rollno" id="rollno" placeholder="Enter Rollno" required>
        </div>
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" placeholder="Enter Full Name" required>
        </div>
        
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" placeholder="Enter City" required>
        </div>
        
        <div class="form-group">
            <label for="pcon">Parents Contact No.</label>
            <input type="text" name="pcon" id="pcon" placeholder="Enter Parents Contact Number" required>
        </div>
        
        <div class="form-group">
            <label for="standard">Standard</label>
            <input type="number" name="standard" id="standard" placeholder="Enter Standard" required>
        </div>
        
        <div class="form-group">
            <label for="simg">Student Image (Optional)</label>
            <input type="file" name="simg" id="simg">
        </div>
        
        <div style="margin-top: 2rem;">
            <input type="submit" name="submit" class="btn-submit" value="Submit">
        </div>
    </form>
</div>

</body>
</html>
