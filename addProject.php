<?php
    session_start();
    require 'db.php.inc'; 
    require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Manager") {
        header("Location: dashboard.php");
        exit();
    }
    
    $errors = []; 
    $generalErrors = []; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $prjId = $_POST['prjId'] ?? '';
        $prjTitle = $_POST['prjTitle'] ?? '';
        $prjDesc = $_POST['prjDesc'] ?? '';
        $custName = $_POST['custName'] ?? '';
        $totalBudget = $_POST['totalBudget'] ?? '';
        $startDate = $_POST['startDate'] ?? '';
        $endDate = $_POST['endDate'] ?? '';

        if (!preg_match('/^[A-Z]{4}-[0-9]{5}$/', $prjId)) {
            $errors['prjId'] = "Project ID must follow the format: 4 uppercase letters, a dash (-), and 5 digits (e.g., PROJ-12345).";
        }

        if(empty($prjTitle)){
            $errors['prjTitle'] = "Project title is required.";
        }
        if(empty($prjDesc)){
            $errors['prjDesc'] = "Project description is required.";
        }
        if(empty($custName)){
            $errors['custName'] = "Customer name is required.";
        }

        if (!is_numeric($totalBudget) || $totalBudget < 0) {
            $errors['totalBudget'] = "Total Budget must be a positive numeric value.";
        }

        if (empty($startDate) || !strtotime($startDate)) {
            $errors['startDate'] = "Start Date is required and must be a valid date.";
        }

        if (empty($endDate) || !strtotime($endDate)) {
            $errors['endDate'] = "End Date is required and must be a valid date.";
        } elseif (strtotime($endDate) <= strtotime($startDate)) {
            $errors['endDate'] = "End Date must be later than Start Date.";
        }

        $allowedTypes = ['pdf', 'docx', 'png', 'jpg'];
        $maxFileSize = 2 * 1024 * 1024; 

        $uploadedFiles = [null, null, null];
        $documentTitles = [null, null, null];

        for ($i = 1; $i <= 3; $i++) {
        $fileKey = "docs$i";
        $titleKey = "docTitle$i";
    
        if (!empty($_FILES[$fileKey]['name'])) {
            $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
            $fileName = $_FILES[$fileKey]['name'];
            $fileSize = $_FILES[$fileKey]['size'];
            $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    
            if (!in_array(strtolower($fileExt), $allowedTypes)) {
                $errors["docs$i"] = "File $i must be of type: PDF, DOCX, PNG, or JPG.";
            }
    
            if ($fileSize > $maxFileSize) {
                $errors["docs$i"] = "File $i must not exceed 2MB.";
            }
    
            if (empty($_POST[$titleKey])) {
                $errors[$titleKey] = "Title for Document $i is required when uploading a file.";
            } else {
                $documentTitles[$i - 1] = $_POST[$titleKey];
            }
    
            if (empty($errors["docs$i"]) && empty($errors[$titleKey])) {
                $safeTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $_POST[$titleKey]);
                $destination = 'uploads/' . $safeTitle . '.' . $fileExt;
    
                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $uploadedFiles[$i - 1] = $destination;
                } else {
                    $errors["docs$i"] = "File $i could not be uploaded. Please try again.";
                }
            }
        }
    }


        try {
            $pdo = getDatabaseConnection("root", "");
        
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE prjId = :prjId");
            $stmt->execute([':prjId' => $prjId]);
            $count = $stmt->fetchColumn();
        
            if ($count > 0) {
                $errors['prjId'] = "The Project ID already exists. Please use a different ID.";
            } else {
                if (empty($errors)) {
                    $stmt = $pdo->prepare("
                        INSERT INTO projects (prjId, prjTitle, prjDesc, custName, totalBudget, startDate, endDate, doc1, doc2, doc3, docTitle1, docTitle2, docTitle3)
                        VALUES (:prjId, :prjTitle, :prjDesc, :custName, :totalBudget, :startDate, :endDate, :doc1, :doc2, :doc3, :docTitle1, :docTitle2, :docTitle3)
                    ");
                    $stmt->execute([
                        ':prjId' => $prjId,
                        ':prjTitle' => $prjTitle,
                        ':prjDesc' => $prjDesc,
                        ':custName' => $custName,
                        ':totalBudget' => $totalBudget,
                        ':startDate' => $startDate,
                        ':endDate' => $endDate,
                        ':doc1' => $uploadedFiles[0] ?? null,
                        ':doc2' => $uploadedFiles[1] ?? null,
                        ':doc3' => $uploadedFiles[2] ?? null,
                        ':docTitle1' => $documentTitles[0] ?? null,
                        ':docTitle2' => $documentTitles[1] ?? null,
                        ':docTitle3' => $documentTitles[2] ?? null,
                    ]);
        
                    $successMessage = "Project successfully added!";
                }
            }
        } catch (PDOException $e) {
            $generalErrors[] = "Database Error: " . $e->getMessage();
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
    <title>Add Project</title>
</head>
<body>
    <div class="container">
    <?php require "header.php" ?>
    <?php require "navigation.php"?>

    <main>
        <h2>Project Details</h2>

        <?php if (!empty($successMessage)): ?>
                <p style="color: green; font-weight: bold;"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <?php if (!empty($generalErrors)): ?>
            <div class="error-summary">
                <h3>There were errors:</h3>
                <ul>
                    <?php foreach ($generalErrors as $generalError): ?>
                        <li style="color:red;"><?php echo ($generalError); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif;?>

        <form method="post" action="addProject.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Project Details</legend>

                <label for="prjId">Project ID:</label>
                <input type="text" id="prjId" name="prjId" class="<?php echo isset($errors['prjId']) ? 'invalid' : ''; ?>" value="<?php echo ($_POST['prjId'] ?? ''); ?>" 
                placeholder="e.g. PROJ-12345"
                required>
                <?php if (isset($errors['prjId'])) echo "<p style='color:red;'>{$errors['prjId']}</p>"; ?>
                <br><br>

                <label for="prjTitle">Project Title:</label>
                <input type="text" id="prjTitle" name="prjTitle" class="<?php echo isset($errors['prjTitle']) ? 'invalid' : ''; ?>"  value="<?php echo ($_POST['prjTitle'] ?? ''); ?>" 
                placeholder="e.g. Example Project"
                required>
                <br><br>

                <label for="prjDesc">Project Description:</label>
                <textarea id="prjDesc" name="prjDesc" class="<?php echo isset($errors['prjDesc']) ? 'invalid' : ''; ?>"  rows="4" 
                placeholder="Project Description..."
                required><?php echo ($_POST['prjDesc'] ?? ''); ?></textarea>
                <br><br>

                <label for="custName">Customer Name:</label>
                <input type="text" id="custName" name="custName" class="<?php echo isset($errors['custName']) ? 'invalid' : ''; ?>"  value="<?php echo ($_POST['custName'] ?? ''); ?>" 
                placeholder="Customer Name"
                required>
                <br><br>

                <label for="totalBudget">Total Budget:</label>
                <input type="number" id="totalBudget" name="totalBudget" min="0" step="0.01" class="<?php echo isset($errors['totalBudget']) ? 'invalid' : ''; ?>"  value="<?php echo ($_POST['totalBudget'] ?? ''); ?>" 
                placeholder="e.g. 200000"
                required>
                <?php if (isset($errors['totalBudget'])) echo "<p style='color:red;'>{$errors['totalBudget']}</p>"; ?>
                <br><br>

                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate" class="<?php echo isset($errors['startDate']) ? 'invalid' : ''; ?>"  value="<?php echo ($_POST['startDate'] ?? ''); ?>" required>
                <?php if (isset($errors['startDate'])) echo "<p style='color:red;'>{$errors['startDate']}</p>"; ?>
                <br><br>

                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="endDate" class="<?php echo isset($errors['endDate']) ? 'invalid' : ''; ?>"  value="<?php echo ($_POST['endDate'] ?? ''); ?>" required>
                <?php if (isset($errors['endDate'])) echo "<p style='color:red;'>{$errors['endDate']}</p>"; ?>
                <br><br>

                <label>Supporting Documents (Max 2MB each, allowed formats: .pdf, .docx, .png, .jpg):</label><br>
                <label for="docs1">File 1:</label>
                <input type="file" id="docs1" name="docs1" class="<?php echo isset($errors['docs1']) ? 'invalid' : ''; ?>" accept=".pdf,.docx,.png,.jpg">
                <?php if (isset($errors['docs1'])) echo "<p style='color:red;'>{$errors['docs1']}</p>"; ?>
                <br>
                <label for="docTitle1">Title for File 1:</label>
                <input type="text" id="docTitle1" name="docTitle1" placeholder="Title for the first file" class="<?php echo isset($errors['docTitle1']) ? 'invalid' : ''; ?>" value="<?php echo ($_POST['docTitle1'] ?? ''); ?>">
                <?php if (isset($errors['docTitle1'])) echo "<p style='color:red;'>{$errors['docTitle1']}</p>"; ?>
                <br><br>

                <label for="docs2">File 2:</label>
                <input type="file" id="docs2" name="docs2" class="<?php echo isset($errors['docs2']) ? 'invalid' : ''; ?>" accept=".pdf,.docx,.png,.jpg">
                <?php if (isset($errors['docs2'])) echo "<p style='color:red;'>{$errors['docs2']}</p>"; ?>
                <br>
                <label for="docTitle2">Title for File 2:</label>
                <input type="text" id="docTitle2" name="docTitle2" placeholder="Title for the second file" class="<?php echo isset($errors['docTitle2']) ? 'invalid' : ''; ?>" value="<?php echo ($_POST['docTitle2'] ?? ''); ?>">
                <?php if (isset($errors['docTitle2'])) echo "<p style='color:red;'>{$errors['docTitle2']}</p>"; ?>
                <br><br>

                <label for="docs3">File 3:</label>
                <input type="file" id="docs3" name="docs3" class="<?php echo isset($errors['docs3']) ? 'invalid' : ''; ?>" accept=".pdf,.docx,.png,.jpg">
                <?php if (isset($errors['docs3'])) echo "<p style='color:red;'>{$errors['docs3']}</p>"; ?>
                <br>
                <label for="docTitle3">Title for File 3:</label>
                <input type="text" id="docTitle3" name="docTitle3" placeholder="Title for the third file" class="<?php echo isset($errors['docTitle3']) ? 'invalid' : ''; ?>" value="<?php echo ($_POST['docTitle3'] ?? ''); ?>">
                <?php if (isset($errors['docTitle3'])) echo "<p style='color:red;'>{$errors['docTitle3']}</p>"; ?>
                <br><br>

                <button type="submit">Add Project</button>
            </fieldset>
        </form>
    </main>
    <?php require "footer.php"; ?>
    </div>
</body>
</html>
