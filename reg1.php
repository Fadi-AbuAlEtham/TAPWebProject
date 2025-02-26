<?php
session_start();
$errors = []; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (empty($_POST['fullName'])) {
        $errors['fullName'] = "Full Name is required.";
    }

    if (empty($_POST['flatNo'])) {
        $errors['flatNo'] = "Flat/House No is required.";
    } elseif (!preg_match('/^[0-9a-zA-Z\s\-]+$/', $_POST['flatNo'])) {
        $errors['flatNo'] = "Flat/House No can only contain letters, numbers, spaces, and hyphens.";
    }

    if (empty($_POST['street'])) {
        $errors['street'] = "Street is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9\s\-,.]+$/', $_POST['street'])) {
        $errors['street'] = "Street can only contain letters, numbers, spaces, commas, periods, and hyphens.";
    }

    if (empty($_POST['city'])) {
        $errors['city'] = "City is required.";
    } elseif (!preg_match('/^[a-zA-Z\s\-]+$/', $_POST['city'])) {
        $errors['city'] = "City can only contain letters, spaces, and hyphens.";
    }

    if (empty($_POST['country'])) {
        $errors['country'] = "Country is required.";
    } elseif (!preg_match('/^[a-zA-Z\s\-]+$/', $_POST['country'])) {
        $errors['country'] = "Country can only contain letters, spaces, and hyphens.";
    }

    if (empty($_POST['dob'])) {
        $errors['dob'] = "Date of Birth is required.";
    } else {
        $dob = DateTime::createFromFormat('Y-m-d', $_POST['dob']);
        $today = new DateTime();
        $age = $today->diff($dob)->y;

        if ($age < 22) {
            $errors['dob'] = "You must be at least 22 years old.";
        }
    }

    if (empty($_POST['idNumber']) || !preg_match('/^\d{9}$/', $_POST['idNumber'])) {
        $errors['idNumber'] = "ID Number must be exactly 9 digits.";
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "A valid Email Address is required.";
    }

    if (empty($_POST['telephone']) || !preg_match('/^\+?\d{10,14}$/', $_POST['telephone'])) {
        $errors['telephone'] = "A valid Telephone number is required.";
    }

    if (empty($_POST['role'])) {
        $errors['role'] = "Role selection is required.";
    }

    if (empty($_POST['qualification'])) {
        $errors['qualification'] = "Qualification is required.";
    }

    if (empty($_POST['skills'])) {
        $errors['skills'] = "Skills are required.";
    }

    if (empty($errors)) {
        $_SESSION["fullName"] = $_POST['fullName'];
        $_SESSION["flatNo"] = $_POST['flatNo'];
        $_SESSION["street"] = $_POST['street'];
        $_SESSION["city"] = $_POST['city'];
        $_SESSION["country"] = $_POST['country'];
        $_SESSION["dob"] = $_POST['dob'];
        $_SESSION["idNumber"] = $_POST['idNumber'];
        $_SESSION["email"] = $_POST['email'];
        $_SESSION["telephone"] = $_POST['telephone'];
        $_SESSION["role"] = $_POST['role'];
        $_SESSION["qualification"] = $_POST['qualification'];
        $_SESSION["skills"] = $_POST['skills'];

        header("Location: reg2.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Step 1</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
    <?php require "header.php";?>
        <main>
            <h2>User Registration - Step 1: User Information</h2>

            <form method="post" action="reg1.php">
                <fieldset>
                    <legend>User Information</legend>

                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" 
                           class="<?php echo isset($errors['fullName']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['fullName'] ?? ''); ?>" 
                           placeholder="e.g. William Smith" required>
                           <?php if (isset($errors['fullName'])) echo "<p style='color:red;'>{$errors['fullName']}</p>"; ?>
                           <br><br>

                    <label for="address">Address:</label><br>
                    <label for="flatNo">Flat/House No:</label>
                    <input type="text" id="flatNo" name="flatNo" 
                           class="<?php echo isset($errors['flatNo']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['flatNo'] ?? ''); ?>" 
                           placeholder="e.g. 20" required>
                           <?php if (isset($errors['flatNo'])) echo "<p style='color:red;'>{$errors['flatNo']}</p>"; ?>
                    <br>
                    <label for="street">Street:</label>
                    <input type="text" id="street" name="street" 
                           class="<?php echo isset($errors['street']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['street'] ?? ''); ?>" 
                           placeholder="e.g. Main Street" required>
                           <?php if (isset($errors['street'])) echo "<p style='color:red;'>{$errors['street']}</p>"; ?>
                    <br>
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" 
                           class="<?php echo isset($errors['city']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['city'] ?? ''); ?>" 
                           placeholder="e.g. London" required>
                           <?php if (isset($errors['city'])) echo "<p style='color:red;'>{$errors['city']}</p>"; ?>
                           <br>
                    <label for="country">Country:</label>
                    <input type="text" id="country" name="country" 
                           class="<?php echo isset($errors['country']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['country'] ?? ''); ?>" 
                           placeholder="e.g. United Kingdom" required>
                           <?php if (isset($errors['country'])) echo "<p style='color:red;'>{$errors['country']}</p>"; ?>
                           <br><br>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" 
                           class="<?php echo isset($errors['dob']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['dob'] ?? ''); ?>" required>
                           <?php if (isset($errors['dob'])) echo "<p style='color:red;'>{$errors['dob']}</p>"; ?>
                           <br><br>

                    <label for="idNumber">ID Number:</label>
                    <input type="text" id="idNumber" name="idNumber" 
                           class="<?php echo isset($errors['idNumber']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['idNumber'] ?? ''); ?>" 
                           placeholder="e.g. 123456789" maxlength="9" required>
                           <?php if (isset($errors['idNumber'])) echo "<p style='color:red;'>{$errors['idNumber']}</p>"; ?>
                           <br><br>

                    <label for="email">E-mail Address:</label>
                    <input type="email" id="email" name="email" 
                           class="<?php echo isset($errors['email']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['email'] ?? ''); ?>" 
                           placeholder="e.g. example@email.com" required>
                           <?php if (isset($errors['email'])) echo "<p style='color:red;'>{$errors['email']}</p>"; ?>
                           <br><br>

                    <label for="telephone">Telephone:</label>
                    <input type="tel" id="telephone" name="telephone" 
                           class="<?php echo isset($errors['telephone']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['telephone'] ?? ''); ?>" 
                           placeholder="e.g. +972 56-977-6361" minlength="10" maxlength="14" required>
                           <?php if (isset($errors['telephone'])) echo "<p style='color:red;'>{$errors['telephone']}</p>"; ?>
                           <br><br>

                    <label for="role">Role:</label>
                    <select id="role" name="role" 
                            class="<?php echo isset($errors['role']) ? 'invalid' : ''; ?>" required>
                        <option value="" disabled <?php echo empty($_POST['role']) ? 'selected' : ''; ?>>Select Role</option>
                        <option value="Manager" <?php echo ($_POST['role'] ?? '') === 'Manager' ? 'selected' : ''; ?>>Manager</option>
                        <option value="Project Leader" <?php echo ($_POST['role'] ?? '') === 'Project Leader' ? 'selected' : ''; ?>>Project Leader</option>
                        <option value="Team Member" <?php echo ($_POST['role'] ?? '') === 'Team Member' ? 'selected' : ''; ?>>Team Member</option>
                    </select>
                    <?php if (isset($errors['role'])) echo "<p style='color:red;'>{$errors['role']}</p>"; ?>
                    <br><br>

                    <label for="qualification">Qualification:</label>
                    <input type="text" id="qualification" name="qualification" 
                           class="<?php echo isset($errors['qualification']) ? 'invalid' : ''; ?>"
                           value="<?php echo ($_POST['qualification'] ?? ''); ?>" 
                           placeholder="e.g. Bachelor's Degree" required>
                           <?php if (isset($errors['qualification'])) echo "<p style='color:red;'>{$errors['qualification']}</p>"; ?>
                           <br><br>

                    <label for="skills">Skills:</label>
                    <textarea id="skills" name="skills" 
                              class="<?php echo isset($errors['skills']) ? 'invalid' : ''; ?>"
                              placeholder="e.g. Project Management, Coding" rows="4" required><?php echo ($_POST['skills'] ?? ''); ?></textarea>
                              <?php if (isset($errors['skills'])) echo "<p style='color:red;'>{$errors['skills']}</p>"; ?>
                              <br><br>

                    <button type="submit" name="submit">Proceed</button>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>