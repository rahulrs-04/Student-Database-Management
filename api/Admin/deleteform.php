<?php
require_once __DIR__ . '/../session_helper.php';
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

include __DIR__ . '/../dbcon.php';

$id = $con->escapeString($_REQUEST['sid']);

// Retrieve the student's image and delete the physical file to save server space
$img_sql = "SELECT `image` FROM `student` WHERE `id` = '$id'";
$img_res = $con->query($img_sql);
if ($img_res && ($img_data = $img_res->fetchArray(SQLITE3_ASSOC))) {
    $img_path = "../dataimg/" . $img_data['image'];
    if (!empty($img_data['image']) && file_exists($img_path)) {
        unlink($img_path);
    }
}

$qry = "DELETE FROM `student` WHERE `id` = '$id'";
$run = $con->exec($qry);

include __DIR__ . '/header.php';
include __DIR__ . '/titleheader.php';
?>

<div class="glass-container" style="max-width: 500px; text-align: center; margin-top: 5rem;">
    <?php if($run == true): ?>
        <div style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem; line-height: 1;">🗑️</div>
        <h2 style="margin-bottom: 1rem; color: var(--danger); font-size: 1.75rem;">Record Deleted</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;">The student records and profile picture have been removed permanently.</p>
        <div style="font-size: 0.85rem; color: var(--text-secondary); display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
            <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--danger); animation: pulse 1.5s infinite;"></div>
            Returning to student directory...
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
                window.location.href = 'deletestudent.php';
            }, 2000);
        </script>
        <noscript>
            <meta http-equiv="refresh" content="2;url=deletestudent.php">
        </noscript>
    <?php else: ?>
        <div style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem; line-height: 1;">✗</div>
        <h2 style="margin-bottom: 1rem; color: var(--danger); font-size: 1.75rem;">Deletion Failed</h2>
        <p style="color: var(--text-secondary); margin-bottom: 2rem;"><?php echo htmlspecialchars($con->lastErrorMsg()); ?></p>
        <a href="deletestudent.php" class="btn-submit" style="display: inline-block; width: auto; padding: 0.75rem 2rem; text-decoration: none; background: var(--danger); box-shadow: 0 4px 15px var(--danger-glow);">Go Back</a>
    <?php endif; ?>
</div>

</body>
</html>
