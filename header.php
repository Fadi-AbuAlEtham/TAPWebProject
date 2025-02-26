<header>
    <h1>Task Allocator Pro</h1>
    <div class="header-right">
        <div >
            <?php if (!empty($_SESSION["username"])){ ?>
                <p><strong>Welcome, <?php echo $_SESSION["username"]; ?>!</strong></p>
                <a href="profile.php">Profile</a>  | <a href="logout.php">Logout</a>
            <?php } else {?>
                <a href="login.php">Login</a>
            <?php } ?>
        </div>
        <div>
            <?php if (!empty($_SESSION["username"])){ ?>
                <a href="profile.php"><img src="icons/user.png" alt="Profile Pic"></a>
                <p><a href="profile.php"><?php echo $_SESSION["username"];?></a></p>
            <?php } ?>
        </div>
    </div>
</header>