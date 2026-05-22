<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

include('../dbcon.php');

$id = $con->escapeString($_POST['sid']);
$rollno = $con->escapeString($_POST['rollno']);
$name = $con->escapeString($_POST['name']);
$city = $con->escapeString($_POST['city']);
$pcon = $con->escapeString($_POST['pcon']);
$std = $con->escapeString($_POST['standard']);

$imagename = $_FILES['simg']['name'];
$tempname = $_FILES['simg']['tmp_name'];

if(!empty($imagename)) {
    move_uploaded_file($tempname, "../dataimg/$imagename");
    $qry = "UPDATE `student` SET `name` = '$name', `city` = '$city', `pcont` = '$pcon', `standard` = '$std', `rollno` = '$rollno', `image` = '$imagename' WHERE `id` = '$id'";
} else {
    $qry = "UPDATE `student` SET `name` = '$name', `city` = '$city', `pcont` = '$pcon', `standard` = '$std', `rollno` = '$rollno' WHERE `id` = '$id'";
}

$run = $con->exec($qry);

include('header.php');
include('titleheader.php');
?>

<div class="glass-container" style="max-width: 500px; text-align: center; margin-top: 5rem;">
    <?php if($run == true): ?>
        <div style="font-size: 4rem; color: var(--success); margin-bottom: 1rem; line-height: 1;">✓</div>
        <h2 style="margin-bottom: 1rem; color: var(--success); font-size: 1.75rem;">Record Updated</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">The student details have been saved successfully.</p>
        <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--success); animation: pulse 1.5s infinite;"></div>
            Redirecting you back to the edit form...
        </div>
        
        <style>
            @keyframes pulse {
                0% { transform: scale(0.9); opacity: 0.5; }
                50% { transform: scale(1.1); opacity: 1; }
                100% { transform: scale(0.9); opacity: 0.5; }
            }
        </style>
        
        <script>
            setTimeout(function() {
                window.location.href = 'updateform.php?sid=<?php echo $id; ?>';
            }, 2000);
        </script>
        <noscript>
            <meta http-equiv="refresh" content="2;url=updateform.php?sid=<?php echo $id; ?>">
        </noscript>
    <?php else: ?>
        <div style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem; line-height: 1;">✗</div>
        <h2 style="margin-bottom: 1rem; color: var(--danger); font-size: 1.75rem;">Update Failed</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;"><?php echo htmlspecialchars($con->lastErrorMsg()); ?></p>
        <a href="updateform.php?sid=<?php echo $id; ?>" class="btn-submit" style="display: inline-block; width: auto; padding: 0.75rem 2rem; text-decoration: none;">Go Back</a>
    <?php endif; ?>
</div>

</body>
</html>
