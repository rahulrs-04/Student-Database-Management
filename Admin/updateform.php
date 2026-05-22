<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

include('header.php');
include('titleheader.php');
include('../dbcon.php');

$sid = $con->escapeString($_GET['sid']);

$sql = "SELECT * FROM `student` WHERE `id` = '$sid'";
$run = $con->query($sql);
$data = $run ? $run->fetchArray(SQLITE3_ASSOC) : null;

if(!$data) {
    echo "<div class='glass-container' style='text-align:center;'><h2 style='color:var(--danger);'>Record Not Found</h2><p><a href='updatestudent.php'>Back to search</a></p></div>";
    exit();
}
?>

<div class="glass-container" style="max-width: 600px; margin-top: 1rem;">
    <h2 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #fff 30%, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Edit Student Record</h2>
    
    <form method="post" action="updatedata.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="rollno">Roll No</label>
            <input type="text" name="rollno" id="rollno" value="<?php echo htmlspecialchars($data['rollno']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($data['city']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="pcon">Parents Contact No.</label>
            <input type="text" name="pcon" id="pcon" value="<?php echo htmlspecialchars($data['pcont']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="standard">Standard</label>
            <input type="number" name="standard" id="standard" value="<?php echo htmlspecialchars($data['standard']); ?>" required>
        </div>
        
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label>Current Photo</label>
            <div style="display: flex; align-items: center; gap: 1.5rem; margin-top: 0.25rem; margin-bottom: 0.75rem;">
                <?php if (!empty($data['image'])): ?>
                    <img src="../dataimg/<?php echo htmlspecialchars($data['image']); ?>" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid var(--card-border);" alt="Current Photo" />
                <?php else: ?>
                    <div style="width: 80px; height: 80px; border-radius: 12px; border: 2px solid var(--card-border); background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: var(--text-secondary); user-select: none;">👤</div>
                <?php endif; ?>
                <span style="color: var(--text-secondary); font-size: 0.85rem;">If you wish to update the photo, select a new file below. Otherwise, leave it empty.</span>
            </div>
            <label for="simg">Select New Photo (Optional)</label>
            <input type="file" name="simg" id="simg">
        </div>
        
        <div style="margin-top: 2rem;">
            <input type="hidden" name="sid" value="<?php echo $data['id']; ?>" />
            <input type="submit" name="submit" class="btn-submit" value="Save Changes">
        </div>
    </form>
</div>

</body>
</html>
