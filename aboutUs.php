<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="login-styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="login-container">
        <?php require "header.php"?>
        <main>
            <div class="about-container">
                <h2>About Us</h2>
                <p>Welcome to <span class="highlight">Task Allocator Pro</span>, your ultimate solution for efficient task management and project coordination.</p>
                
                <h3>Our Mission</h3>
                <p>We strive to empower teams and individuals by providing tools that streamline task assignments, monitor progress, and ensure project success.</p>
                
                <h3>What We Do</h3>
                <ul>
                    <li>Efficient task allocation for teams.</li>
                    <li>Real-time progress tracking.</li>
                    <li>Seamless integration with your project workflows.</li>
                </ul>
                
                <h3>Our Values</h3>
                <ul>
                    <li><strong>Efficiency:</strong> Streamline your workflow and save time.</li>
                    <li><strong>Collaboration:</strong> Foster teamwork and transparency.</li>
                    <li><strong>Success:</strong> Help you achieve project goals effectively.</li>
                </ul>
                
                <p>Need more information? Feel free to <a href="contactUs.php">Contact Us</a>.</p>
                <?php if(empty($_SESSION['userId'])){?>
                    <a href="login.php" class="btn">Return to login</a>
                <?php }else{?>
                    <a href="dashboard.php" class="btn">Return to Dashboard</a>
                <?php }?>
            </div>
        </main>
        <?php require "footer.php"?>
    </div>
</body>
</html>
