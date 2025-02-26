<?php
session_start();
$errors = [];
$successMessage = "";
$failureMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = "Name must only contain letters and spaces.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($subject)) {
        $errors['subject'] = "Subject is required.";
    }

    if (empty($message)) {
        $errors['message'] = "Message is required.";
    }

    if (empty($errors)) {
        try {
            $pdo = getDatabaseConnection("root", "");
            $stmt = $pdo->prepare("
                INSERT INTO ContactMessages (name, email, subject, message)
                VALUES (:name, :email, :subject, :message)
            ");
            $stmt->execute([
                ':name' => $_POST['name'],
                ':email' => $_POST['email'],
                ':subject' => $_POST['subject'],
                ':message' => $_POST['message'],
            ]);

            $successMessage = "Your message has been sent successfully.";

            if (!empty($_SESSION["username"])) {
                header("Refresh: 3; url=dashboard.php");
            } else {
                header("Refresh: 3; url=login.php");
            }
            exit();
        } catch (PDOException $e) {
            $failureMessage = "There was an error storing your message. Please try again.";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <header>
            <h1>Task Allocator Pro</h1>
            <div class="header-right">
                <?php if (!empty($_SESSION["username"])): ?>
                    <p>Welcome, <?php echo ($_SESSION["username"]); ?>!</p>
                    <a href="profile.php">Profile</a> | <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </header>
        <main>
            <h2>Contact Us</h2>
            <p>If you have any questions, feedback, or need support, feel free to contact us using the form below.</p>
            
            <?php if (!empty($successMessage)): ?>
                <p class="success"><?php echo ($successMessage); ?></p>
            <?php elseif (!empty($failureMessage)): ?>
                <p class="error"><?php echo ($failureMessage); ?></p>
            <?php endif; ?>

            <form action="contactUs.php" method="post">
                <fieldset>
                    <legend>Contact Form</legend>

                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" class="<?php echo isset($errors['name']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['name'] ?? ''); ?>" placeholder="e.g. John Doe" required>
                    <?php if (isset($errors['name'])): ?>
                        <p class="error"><?php echo ($errors['name']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" class="<?php echo isset($errors['email']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['email'] ?? ''); ?>" placeholder="e.g. john.doe@example.com" required>
                    <?php if (isset($errors['email'])): ?>
                        <p class="error"><?php echo ($errors['email']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" class="<?php echo isset($errors['subject']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['subject'] ?? ''); ?>" placeholder="Subject of your message" required>
                    <?php if (isset($errors['subject'])): ?>
                        <p class="error"><?php echo ($errors['subject']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" class="<?php echo isset($errors['message']) ? 'invalid' : ''; ?>" 
                              placeholder="Write your message here..." required><?php echo ($_POST['message'] ?? ''); ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <p class="error"><?php echo ($errors['message']); ?></p>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit">Send Message</button>
                </fieldset>
            </form>
        </main>
        <footer>
            <p>
            &copy;2025 Task Allocator Pro |
            <a href="index.html">Fadi Abu Aletham Home Page</a> | <a href="contactUs.php">Contact Us</a> | Phone: <a href="tel:+972569776361">+972 56-977-6361</a> | Email: <a href="mailto:support@tap.com">support@tap.com</a> | About Us: <a href="aboutUs.html">TAP</a>
            </p>
            <address>
                Address: 7420 Maintenance Ave, Fog City<br>
            </address>
        </footer>
    </div>
</body>
</html>