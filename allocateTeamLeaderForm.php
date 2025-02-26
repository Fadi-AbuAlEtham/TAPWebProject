<?php require_once 'db.php.inc'; session_start(); 
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Manager") {
        header("Location: dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Team Leader</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php"; ?>
        <main>
            <h2>Allocate Team Leader</h2>
            <?php
            $errorMessage = "";
            $successMessage = "";
            $projectDetails = [];
            $teamLeaders = [];
            $documents = [];

            try {
                $pdo = getDatabaseConnection("root", "");

                $projectId = $_GET['projectId'] ?? $_POST['projectId'] ?? null;

                if (!$projectId) {
                    $errorMessage = "Error: No project ID provided.";
                } else {
                    if ($_SERVER["REQUEST_METHOD"] === "POST") {
                        $teamLeaderId = $_POST['teamLeader'] ?? null;

                        if (!$teamLeaderId) {
                            $errorMessage = "Error: You must select a team leader.";
                        } else {
                            $stmt = $pdo->prepare("
                                UPDATE projects
                                SET teamLeaderID = :teamLeaderId
                                WHERE prjId = :projectId
                            ");
                            $stmt->execute([
                                ':teamLeaderId' => $teamLeaderId,
                                ':projectId' => $projectId,
                            ]);
                            $successMessage = "Team Leader ID: " .$teamLeaderId. " successfully allocated to Project ID: $projectId!";
                        }
                    }

                    $projectStmt = $pdo->prepare("
                        SELECT prjTitle, prjDesc, custName, totalBudget, startDate, endDate, doc1, doc2, doc3
                        FROM projects
                        WHERE prjId = :projectId
                    ");
                    $projectStmt->execute([':projectId' => $projectId]);
                    $projectDetails = $projectStmt->fetch(PDO::FETCH_ASSOC);

                    if (!$projectDetails) {
                        $errorMessage = "Error: No project found with ID $projectId.";
                    } else {
                        $documents = [
                            "Document 1" => $projectDetails['doc1'] ?? "",
                            "Document 2" => $projectDetails['doc2'] ?? "",
                            "Document 3" => $projectDetails['doc3'] ?? "",
                        ];

                        $teamLeaderStmt = $pdo->prepare("SELECT userId AS id, fullName AS name FROM users WHERE role IN ('Project Leader', 'Team Leader')");
                        $teamLeaderStmt->execute();
                        $teamLeaders = $teamLeaderStmt->fetchAll();
                    }
                }
            } catch (PDOException $e) {
                $errorMessage = "Database Error: " . $e->getMessage();
            }
            ?>

            <?php if (!empty($successMessage)): ?>
                <p class="success"><?php echo $successMessage; ?></p>
            <?php elseif (!empty($errorMessage)): ?>
                <p class="error"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <?php if (!empty($projectDetails) && empty($errorMessage)): ?>
                <form method="post">
                    <fieldset>
                        <legend>Project Details</legend>
                        <label for="projectIdDisplay">Project ID:</label>
                        <input type="text" id="projectIdDisplay" value="<?php echo $projectId; ?>" disabled>
                        <input type="hidden" name="projectId" value="<?php echo $projectId; ?>">

                        <label for="prjTitle">Project Title:</label>
                        <input type="text" id="prjTitle" value="<?php echo $projectDetails['prjTitle']; ?>" disabled><br><br>

                        <label for="prjDesc">Project Description:</label>
                        <textarea id="prjDesc" rows="4" disabled><?php echo $projectDetails['prjDesc']; ?></textarea><br><br>

                        <label for="custName">Customer Name:</label>
                        <input type="text" id="custName" value="<?php echo $projectDetails['custName']; ?>" disabled><br><br>

                        <label for="totalBudget">Total Budget:</label>
                        <input type="text" id="totalBudget" value="<?php echo "$" . $projectDetails['totalBudget']; ?>" disabled><br><br>

                        <label for="startDate">Start Date:</label>
                        <input type="text" id="startDate" value="<?php echo $projectDetails['startDate']; ?>" disabled><br><br>

                        <label for="endDate">End Date:</label>
                        <input type="text" id="endDate" value="<?php echo $projectDetails['endDate']; ?>" disabled><br><br>
                    </fieldset>
                    <fieldset>
                        <legend>Select Team Leader</legend>
                        <label for="teamLeader">Team Leader:</label>
                        <select id="teamLeader" name="teamLeader" required>
                            <option value="" disabled selected>Select a Team Leader</option>
                            <?php foreach ($teamLeaders as $leader): ?>
                                <option value="<?php echo ($leader['id']); ?>">
                                    <?php echo ($leader['name']) . " - ID " . $leader['id']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select><br><br>
                        <button type="submit">Confirm Allocation</button>
                    </fieldset>
                </form>
                <section class="supporting-documents-section">
                    <h3>Supporting Documents</h3>
                    <ul class="supporting-documents">
                        <?php foreach ($documents as $docLabel => $docPath): ?>
                            <?php if (!empty($docPath)): ?>
                                <?php 
                                    $fileName = pathinfo($docPath, PATHINFO_BASENAME);
                                ?>
                                <li class="document">
                                    <a href="<?php echo $docPath; ?>" target="_blank" class="<?php echo pathinfo($docPath, PATHINFO_EXTENSION); ?>">
                                        <?php echo $docLabel . ": " . $fileName; ?>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="no-document"><?php echo $docLabel; ?>: No file available</li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>
