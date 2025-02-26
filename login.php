<?php
session_start();
session_unset();
require "db.php.inc";

$errors = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        try {
            $pdo = getDatabaseConnection("root", "");

            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            
            $user = $stmt->fetch();

            if ($user) {
                if ($password == $user["password"]) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user["role"];
                    $_SESSION['userId'] = $user["userId"];

                    header("Location: dashboard.php");
                    exit;
                } else {
                    $errors['password'] = "Incorrect password!";
                }
            } else {
                $errors['username'] = "Username not found!";
            }
        } catch (PDOException $e) {
            $errors['database'] = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Task Allocator Pro</h1>
        </header>
        <main>
            <h2>Login</h2>

            <?php if (!empty($errors['database'])): ?>
                <div class="error"><?php echo $errors['database']; ?></div>
            <?php endif; ?>

            <form method="post" action="login.php">
                <fieldset>
                    <legend>Login</legend>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username"
                           class="<?php echo isset($errors['username']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['username'] ?? ''); ?>"
                           placeholder="Enter your username" required>
                    <?php if (isset($errors['username'])): ?>
                        <p class="error"><?php echo ($errors['username']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password"
                           class="<?php echo isset($errors['password']) ? 'invalid' : ''; ?>"
                           placeholder="Enter your password" required>
                    <?php if (isset($errors['password'])): ?>
                        <p class="error"><?php echo ($errors['password']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit" id="submit" name="submit" value="login">Login</button>
                </fieldset>
            </form>

            <p>Don't have an account? <a href="reg1.php">Create New Account</a></p>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>
