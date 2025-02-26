<?php
session_start();
require "db.php.inc";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!preg_match('/^[a-zA-Z0-9]{6,13}$/', $username)) {
        $errors['username'] = "Username must be 6–13 alphanumeric characters.";
    }

    try {
        $pdo = getDatabaseConnection("root", ""); 
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $usernameCount = $stmt->fetchColumn();

        if ($usernameCount > 0) {
            $errors['username'] = "This username is already taken. Please choose another.";
        }
    } catch (PDOException $e) {
        $errors['database'] = "Database error occurred: " . $e->getMessage();
    }

    if (strlen($password) < 8 || strlen($password) > 12 || !preg_match('/^(?=.*[a-zA-Z])(?=.*\d).+$/', $password)) {
        $errors['password'] = "Password must be 8–12 characters long and include both letters and numbers.";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;

        header("Location: reg3.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Step 2</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php";?>
        <main>
            <h2>User Registration - Step 2: E-Account Creation</h2>

            <form method="post" action="reg2.php">
                <fieldset>
                    <legend>E-Account Creation</legend>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" 
                           class="<?php echo isset($errors['username']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['username'] ?? ''); ?>" 
                           placeholder="e.g. william123" required>
                    <?php if (isset($errors['username'])): ?>
                        <p class="error"><?php echo ($errors['username']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" 
                           class="<?php echo isset($errors['password']) ? 'invalid' : ''; ?>" 
                           minlength="8" maxlength="12" required>
                    <?php if (isset($errors['password'])): ?>
                        <p class="error"><?php echo ($errors['password']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="confirm_password">Password Confirmation:</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="<?php echo isset($errors['confirm_password']) ? 'invalid' : ''; ?>" 
                           minlength="8" maxlength="12" required>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="error"><?php echo ($errors['confirm_password']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit">Proceed</button>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>