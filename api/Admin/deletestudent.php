<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

include('header.php');
include('titleheader.php');
?>

<div class="glass-container" style="max-width: 900px; margin-top: 1rem;">
    <h2 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #fff 30%, var(--danger)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Delete Student Records</h2>
    
    <form action="deletestudent.php" method="post" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap; margin-bottom: 2rem;">
        <div class="form-group" style="flex: 1; min-width: 150px; margin-bottom: 0;">
            <label for="standard">Enter Standard</label>
            <input type="number" name="standard" id="standard" placeholder="Standard" value="<?php echo isset($_POST['standard']) ? htmlspecialchars($_POST['standard']) : ''; ?>" required />
        </div>
        
        <div class="form-group" style="flex: 2; min-width: 250px; margin-bottom: 0;">
            <label for="stuname">Enter Student Name</label>
            <input type="text" name="stuname" id="stuname" placeholder="Student Name" value="<?php echo isset($_POST['stuname']) ? htmlspecialchars($_POST['stuname']) : ''; ?>" required />
        </div>
        
        <div style="flex: 0; min-width: 120px;">
            <input type="submit" name="submit" class="btn-submit" value="Search" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%); box-shadow: 0 4px 15px var(--danger-glow);" />
        </div>
    </form>
    
    <?php
    if(isset($_POST['submit'])){
        include('../dbcon.php');
        
        $standard = $con->escapeString($_POST['standard']);
        $name = $con->escapeString($_POST['stuname']);
        
        $sql = "SELECT * FROM `student` WHERE `standard`='$standard' AND `name` LIKE '%$name%'"; 
        $run = $con->query($sql);
        
        $records = [];
        if ($run) {
            while ($row = $run->fetchArray(SQLITE3_ASSOC)) {
                $records[] = $row;
            }
        }
        
        if(count($records) < 1){
            echo "<div style='padding: 2rem; text-align: center; color: var(--text-secondary); background: rgba(255,255,255,0.01); border-radius: 12px; border: 1px dashed rgba(255,255,255,0.1); margin-top: 1.5rem;'>No Records Found</div>";
        } else {
            ?>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach($records as $data){
                            $count++;
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td>
                                    <?php if (!empty($data['image'])): ?>
                                        <img src="../dataimg/<?php echo htmlspecialchars($data['image']); ?>" class="img-thumbnail" alt="Student Photo"/>
                                    <?php else: ?>
                                        <div class="img-thumbnail-placeholder">👤</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($data['name']); ?></td>
                                <td><?php echo htmlspecialchars($data['rollno']); ?></td>
                                <td>
                                    <a href="deleteform.php?sid=<?php echo $data['id']; ?>" class="action-link link-delete" onclick="return confirm('Are you sure you want to delete this student record? This cannot be undone.');">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
    }
    ?>
</div>

</body>
</html>
