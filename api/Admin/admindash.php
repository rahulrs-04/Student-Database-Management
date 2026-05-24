<?php
session_start();
if(!isset($_SESSION['uid'])) {
    header('location: ../login.php');
    exit();
}

include('header.php');
?>

<div class="admintitle">
    <h1>Welcome, Admin</h1>
    <h4>
        <a href="../logout.php" class="btn-nav btn-logout">Logout</a>
    </h4>
</div>

<div class="glass-container" style="max-width: 900px; margin-top: 1rem;">
    <h2 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(135deg, #fff 30%, var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Dashboard Control Panel</h2>
    
    <div class="dashboard-grid">
        <div class="dash-card" onclick="window.location.href='addstudent.php'">
            <div class="dash-card-icon">➕</div>
            <a href="addstudent.php">Insert Student</a>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem; text-align: center;">Register new student profiles and details</p>
        </div>
        
        <div class="dash-card" onclick="window.location.href='updatestudent.php'">
            <div class="dash-card-icon">📝</div>
            <a href="updatestudent.php">Update Student</a>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem; text-align: center;">Modify existing student records</p>
        </div>
        
        <div class="dash-card" onclick="window.location.href='deletestudent.php'">
            <div class="dash-card-icon">🗑️</div>
            <a href="deletestudent.php">Delete Student</a>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-top: 0.5rem; text-align: center;">Remove students from the directory</p>
        </div>
    </div>
</div>

</body>
</html>
