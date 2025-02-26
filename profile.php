<?php
session_start();
require "db.php.inc";
require "auth.php";

    checkAuthentication();

    

$pdo = getDatabaseConnection("root", "");

$userId = $_SESSION['userId'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userId = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    die("Error fetching user profile: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $flatNo = $_POST['flatNo'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $qualification = $_POST['qualification'];
    $skills = $_POST['skills'];

    try {
        $updateStmt = $pdo->prepare("
            UPDATE users 
            SET 
                fullName = :fullName,
                flatNo = :flatNo,
                street = :street,
                city = :city,
                country = :country,
                dob = :dob,
                email = :email,
                telephone = :telephone,
                qualification = :qualification,
                skills = :skills
            WHERE userId = :id
        ");
        $updateStmt->execute([
            'fullName' => $fullName,
            'flatNo' => $flatNo,
            'street' => $street,
            'city' => $city,
            'country' => $country,
            'dob' => $dob,
            'email' => $email,
            'telephone' => $telephone,
            'qualification' => $qualification,
            'skills' => $skills,
            'id' => $userId
        ]);

        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        $successMessage = "Profile updated successfully!";
    } catch (PDOException $e) {
        $errorMessage = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css"> 
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php 
            require "header.php";
            require "navigation.php";
        ?>
        <main>
            <div class="summary">
                <h2>Profile Details</h2>
                <?php if (isset($successMessage)) echo "<p class='success'>$successMessage</p>"; ?>
                <?php if (isset($errorMessage)) echo "<p class='error'>$errorMessage</p>"; ?>
                
                <form action="" method="POST">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" value="<?= ($user['fullName']) ?>" required>

                    <label for="flatNo">Flat No:</label>
                    <input type="text" id="flatNo" name="flatNo" value="<?= ($user['flatNo']) ?>" required>

                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street" value="<?= ($user['street']) ?>" required>

                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" value="<?= ($user['city']) ?>" required>

                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" value="<?= ($user['country']) ?>" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" value="<?= ($user['dob']) ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= ($user['email']) ?>" required>

                    <label for="telephone">Telephone:</label>
                    <input type="text" id="telephone" name="telephone" value="<?= ($user['telephone']) ?>" required>

                    <label for="qualification">Qualification:</label>
                    <input type="text" id="qualification" name="qualification" value="<?= ($user['qualification']) ?>" required>

                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" rows="5" required><?= ($user['skills']) ?></textarea>

                    <button type="submit">Update Profile</button>
                </form>
            </div>
        </main>
        <?php require "footer.php" ?>
    </div>
</body>
</html>
