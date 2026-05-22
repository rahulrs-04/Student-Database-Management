<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <title>Student Management System</title>
</head>
<body>
    
    <div class="admin-link-top">
        <a href="login.php" class="btn-nav">Admin Login</a>
    </div>

    <div class="glass-container">
        <h1 class="hero-title">Student Management System</h1>
        <p class="hero-subtitle">Enter your standard and roll number to retrieve student records</p>
        
        <form method="post" action="index.php">
            <div class="form-group">
                <label for="std">Choose Standard</label>
                <select name="std" id="std">
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                    <option value="3">3rd</option>
                    <option value="4">4th</option>
                    <option value="5">5th</option>
                    <option value="6">6th</option>
                    <option value="7">7th</option>
                    <option value="8">8th</option>
                    <option value="9">9th</option>
                    <option value="10">10th</option>
                    <option value="11">11th</option>
                    <option value="12">12th</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="rollno">Enter Roll No</label>
                <input type="text" name="rollno" id="rollno" placeholder="Enter your roll number" required>
            </div>
            
            <div style="margin-top: 2rem;">
                <input type="submit" name="submit" class="btn-submit" value="Show Info">
            </div>
        </form>

        <?php
        if(isset($_POST['submit'])){
            $standard = $_POST['std'];
            $rollno = $_POST['rollno'];
            
            include('dbcon.php');
            include('function.php');
            
            showdetails($standard, $rollno);
        }
        ?>
    </div>
        
</body>
</html>