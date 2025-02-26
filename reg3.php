<?php
session_start();
require 'db.php.inc'; 

$errors = [];

$requiredFields = [
    'fullName', 'flatNo', 'street', 'city', 'country', 'dob', 'idNumber',
    'email', 'telephone', 'role', 'qualification', 'skills', 'username', 'password'
];

foreach ($requiredFields as $field) {
    if (empty($_SESSION[$field])) {
        $errors[$field] = ucfirst($field) . " is missing. Please go back and complete the registration.";
    }
}

if (!preg_match('/^[a-zA-Z\s\-]+$/', $_SESSION['fullName'] ?? '')) {
    $errors['fullName'] = "Full Name can only contain letters, spaces, and hyphens.";
}
if (!preg_match('/^[0-9a-zA-Z\s\-]+$/', $_SESSION['flatNo'] ?? '')) {
    $errors['flatNo'] = "Flat/House No can only contain letters, numbers, spaces, and hyphens.";
}
if (!preg_match('/^[a-zA-Z0-9\s\-,.]+$/', $_SESSION['street'] ?? '')) {
    $errors['street'] = "Street can only contain letters, numbers, spaces, commas, periods, and hyphens.";
}
if (!preg_match('/^[a-zA-Z\s\-]+$/', $_SESSION['city'] ?? '')) {
    $errors['city'] = "City can only contain letters, spaces, and hyphens.";
}
if (!preg_match('/^[a-zA-Z\s\-]+$/', $_SESSION['country'] ?? '')) {
    $errors['country'] = "Country can only contain letters, spaces, and hyphens.";
}
if (!filter_var($_SESSION['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
}
if (!preg_match('/^\+?[0-9\s\-]+$/', $_SESSION['telephone'] ?? '')) {
    $errors['telephone'] = "Telephone can only contain numbers, spaces, and hyphens.";
}
if (!preg_match('/^[a-zA-Z\s]+$/', $_SESSION['qualification'] ?? '')) {
    $errors['qualification'] = "Qualification can only contain letters and spaces.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm']) && empty($errors)) {
    try {
        $pdo = getDatabaseConnection("root", "");

        $userId = mt_rand(1000000000, 9999999999);

        $stmt = $pdo->prepare("
            INSERT INTO users (
                fullName, flatNo, street, city, country, dob, idNumber, email, telephone, role,
                qualification, skills, username, password, userId
            ) VALUES (
                :fullName, :flatNo, :street, :city, :country, :dob, :idNumber, :email, :telephone, 
                :role, :qualification, :skills, :username, :password, :userId
            )
        ");

        $stmt->execute([
            ':fullName' => $_SESSION['fullName'],
            ':flatNo' => $_SESSION['flatNo'],
            ':street' => $_SESSION['street'],
            ':city' => $_SESSION['city'],
            ':country' => $_SESSION['country'],
            ':dob' => $_SESSION['dob'],
            ':idNumber' => $_SESSION['idNumber'],
            ':email' => $_SESSION['email'],
            ':telephone' => $_SESSION['telephone'],
            ':role' => $_SESSION['role'],
            ':qualification' => $_SESSION['qualification'],
            ':skills' => $_SESSION['skills'],
            ':username' => $_SESSION['username'],
            ':password' => $_SESSION['password'], 
            ':userId' => $userId
        ]);

        session_unset();
        session_destroy();

        $successMessage = "
            <h2>Registration Successful!</h2>
            <p class=\"success\">Your User ID is: <strong>" . $userId . "</strong></p>
            <p class=\"success\"><a href='login.php'>Click here to Login</a></p>
        ";
        echo "<meta http-equiv='refresh' content='3;url=login.php'>";
    } catch (PDOException $e) {
        $errors['database'] = "Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Step 3</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; ?>
        <main>
            <h2>User Registration - Step 3: Confirmation</h2>

            <?php if (!empty($successMessage)): ?>
                <div class="success">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="reg3.php">
                <fieldset>
                    <legend>Review Your Information</legend>

                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName"
                           class="<?php echo isset($errors['fullName']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['fullName'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['fullName'])): ?>
                        <p class="error"><?php echo ($errors['fullName']); ?></p>
                    <?php endif; ?>

                    <label for="flatNo">Flat/House No:</label>
                    <input type="text" id="flatNo" name="flatNo"
                           class="<?php echo isset($errors['flatNo']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['flatNo'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['flatNo'])): ?>
                        <p class="error"><?php echo ($errors['flatNo']); ?></p>
                    <?php endif; ?>

                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street"
                           class="<?php echo isset($errors['street']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['street'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['street'])): ?>
                        <p class="error"><?php echo ($errors['street']); ?></p>
                    <?php endif; ?>

                    <label for="city">City:</label>
                    <input type="text" id="city" name="city"
                           class="<?php echo isset($errors['city']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['city'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['city'])): ?>
                        <p class="error"><?php echo ($errors['city']); ?></p>
                    <?php endif; ?>

                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country"
                           class="<?php echo isset($errors['country']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['country'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['country'])): ?>
                        <p class="error"><?php echo ($errors['country']); ?></p>
                    <?php endif; ?>

                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob"
                           value="<?php echo ($_SESSION['dob'] ?? ''); ?>" disabled><br><br>

                    <label for="idNumber">ID Number:</label>
                    <input type="text" id="idNumber" name="idNumber"
                           value="<?php echo ($_SESSION['idNumber'] ?? ''); ?>" disabled><br><br>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email"
                           class="<?php echo isset($errors['email']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['email'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['email'])): ?>
                        <p class="error"><?php echo ($errors['email']); ?></p>
                    <?php endif; ?>

                    <label for="telephone">Telephone:</label>
                    <input type="text" id="telephone" name="telephone"
                           class="<?php echo isset($errors['telephone']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['telephone'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['telephone'])): ?>
                        <p class="error"><?php echo ($errors['telephone']); ?></p>
                    <?php endif; ?>

                    <label for="role">Role:</label>
                    <input type="text" id="role" name="role"
                           value="<?php echo ($_SESSION['role'] ?? ''); ?>" disabled><br><br>

                    <label for="qualification">Qualification:</label>
                    <input type="text" id="qualification" name="qualification"
                           class="<?php echo isset($errors['qualification']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_SESSION['qualification'] ?? ''); ?>" disabled>
                    <?php if (isset($errors['qualification'])): ?>
                        <p class="error"><?php echo ($errors['qualification']); ?></p>
                    <?php endif; ?>

                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" disabled><?php echo ($_SESSION['skills'] ?? ''); ?></textarea><br><br>

                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username"
                           value="<?php echo ($_SESSION['username'] ?? ''); ?>" disabled><br><br>

                    <button type="submit" name="confirm">Confirm Registration</button>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>
