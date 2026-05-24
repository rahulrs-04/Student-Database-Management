<?php

function showdetails($standard, $rollno) {
    include('dbcon.php');

    // SQLite uses escapeString to clean inputs
    $standard_esc = $con->escapeString($standard);
    $rollno_esc = $con->escapeString($rollno);

    $qry = "SELECT * FROM `student` WHERE `rollno`='$rollno_esc' AND `standard`='$standard_esc'";
    $run = $con->query($qry);
    
    if ($run && ($data = $run->fetchArray(SQLITE3_ASSOC))) {
        ?>
        <div class="student-card">
            <div>
                <?php if (!empty($data['image'])): ?>
                    <img src="dataimg/<?php echo htmlspecialchars($data['image']); ?>" class="student-card-img" alt="Student Photo"/>
                <?php else: ?>
                    <div class="student-card-img-placeholder">👤</div>
                <?php endif; ?>
            </div>
            <div class="student-card-info">
                <h3 style="margin-bottom: 0.5rem; color: var(--primary); font-size: 1.25rem;">Student Details</h3>
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value"><?php echo htmlspecialchars($data['name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Roll No</span>
                    <span class="info-value"><?php echo htmlspecialchars($data['rollno']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Standard</span>
                    <span class="info-value"><?php echo htmlspecialchars($data['standard']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">City</span>
                    <span class="info-value"><?php echo htmlspecialchars($data['city']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Contact No</span>
                    <span class="info-value"><?php echo htmlspecialchars($data['pcont']); ?></span>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<div style='margin-top:2rem; padding:1rem; border-radius:10px; background:rgba(255, 75, 92, 0.1); border:1px solid rgba(255, 75, 92, 0.2); color:var(--danger); text-align:center; font-weight:600;'>No Student Found matching these criteria.</div>";
    }
}

?>
