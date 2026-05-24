<?php
require_once __DIR__ . '/../session_helper.php';
session_start();

if (!isset($_SESSION['student_uid'])) {
    header('location: ../index.php');
    exit();
}

include __DIR__ . '/../dbcon.php';

$student_id = $_SESSION['student_uid'];
$qry = "SELECT * FROM `student` WHERE `id` = '$student_id'";
$run = $con->query($qry);
$student = $run ? $run->fetchArray(SQLITE3_ASSOC) : null;

if (!$student) {
    // If student record was deleted, clear session and redirect
    unset($_SESSION['student_uid']);
    header('location: ../index.php');
    exit();
}

$standard = $student['standard'];

// Fetch classmates
$classmates_qry = "SELECT * FROM `student` WHERE `standard` = '$standard' AND `id` != '$student_id' ORDER BY `rollno` ASC";
$classmates_run = $con->query($classmates_qry);
$classmates = [];
if ($classmates_run) {
    while ($row = $classmates_run->fetchArray(SQLITE3_ASSOC)) {
        $classmates[] = $row;
    }
}

// Stats
$total_classmates = count($classmates) + 1; // plus self
$cities_qry = "SELECT COUNT(DISTINCT(`city`)) FROM `student` WHERE `standard` = '$standard'";
$total_cities = $con->querySingle($cities_qry);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Dashboard</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
    <style>
        .portal-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        @media (max-width: 900px) {
            .portal-grid {
                grid-template-columns: 1fr;
            }
        }
        .search-box {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .search-box input {
            padding-left: 2.5rem;
        }
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            pointer-events: none;
        }
        .stat-badge {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }
        .stat-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>

<div class="admintitle">
    <h1>Student Portal</h1>
    <h4>
        <span style="color: var(--text-secondary); font-size: 0.9rem;">Standard: <strong style="color: var(--primary);"><?php echo htmlspecialchars($student['standard']); ?></strong></span>
        <a href="logout.php" class="btn-nav btn-logout">Logout</a>
    </h4>
</div>

<div class="portal-grid">
    <!-- Sidebar profile info -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="glass-container" style="margin: 0; padding: 2rem; width: 100%;">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <?php if (!empty($student['image'])): ?>
                    <img src="../dataimg/<?php echo htmlspecialchars($student['image']); ?>" class="student-card-img" style="width: 120px; height: 120px; border-radius: 50%;" alt="My Photo"/>
                <?php else: ?>
                    <div class="student-card-img-placeholder" style="width: 120px; height: 120px; border-radius: 50%; font-size: 3rem; margin: 0 auto;">👤</div>
                <?php endif; ?>
                <h3 style="margin-top: 1rem; font-size: 1.25rem; color: var(--text-primary);"><?php echo htmlspecialchars($student['name']); ?></h3>
                <p style="color: var(--text-secondary); font-size: 0.85rem;">Roll Number: <?php echo htmlspecialchars($student['rollno']); ?></p>
            </div>
            
            <div class="student-card-info" style="gap: 0.5rem; border-top: 1px solid var(--card-border); padding-top: 1.5rem;">
                <div class="info-row" style="grid-template-columns: 80px 1fr;">
                    <span class="info-label">City</span>
                    <span class="info-value" style="font-size: 0.9rem;"><?php echo htmlspecialchars($student['city']); ?></span>
                </div>
                <div class="info-row" style="grid-template-columns: 80px 1fr;">
                    <span class="info-label">Contact</span>
                    <span class="info-value" style="font-size: 0.9rem;"><?php echo htmlspecialchars($student['pcont']); ?></span>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="stat-badge">
                <div class="stat-value"><?php echo $total_classmates; ?></div>
                <div class="stat-label">Class Size</div>
            </div>
            <div class="stat-badge">
                <div class="stat-value"><?php echo $total_cities; ?></div>
                <div class="stat-label">Cities</div>
            </div>
        </div>
    </div>

    <!-- Classmates Directory -->
    <div class="glass-container" style="margin: 0; width: 100%;">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem; background: linear-gradient(135deg, #fff 40%, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">My Classmates</h2>
        
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" id="classmateSearch" placeholder="Search classmates by name or roll number..." onkeyup="filterClassmates()">
        </div>

        <div class="table-container" style="margin-top: 0;">
            <table class="modern-table" id="classmatesTable">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>City</th>
                        <th>Contact No</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($classmates) > 0): ?>
                        <?php foreach($classmates as $row): ?>
                            <tr class="classmate-row">
                                <td class="c-roll"><?php echo htmlspecialchars($row['rollno']); ?></td>
                                <td>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="../dataimg/<?php echo htmlspecialchars($row['image']); ?>" class="img-thumbnail" style="width: 40px; height: 40px;" alt="Photo"/>
                                    <?php else: ?>
                                        <div class="img-thumbnail-placeholder" style="width: 40px; height: 40px; font-size: 1.1rem;">👤</div>
                                    <?php endif; ?>
                                </td>
                                <td class="c-name" style="font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['city']); ?></td>
                                <td><?php echo htmlspecialchars($row['pcont']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 2rem;">No other classmates in this standard yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterClassmates() {
    var input = document.getElementById("classmateSearch");
    var filter = input.value.toLowerCase();
    var rows = document.getElementsByClassName("classmate-row");
    
    for (var i = 0; i < rows.length; i++) {
        var nameCell = rows[i].getElementsByClassName("c-name")[0];
        var rollCell = rows[i].getElementsByClassName("c-roll")[0];
        if (nameCell || rollCell) {
            var nameText = nameCell.textContent || nameCell.innerText;
            var rollText = rollCell.textContent || rollCell.innerText;
            if (nameText.toLowerCase().indexOf(filter) > -1 || rollText.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}
</script>

</body>
</html>
