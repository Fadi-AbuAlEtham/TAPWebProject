<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Team Member") {
        header("Location: dashboard.php");
        exit();
    }

$pdo = getDatabaseConnection("root", "");

$sql = "SELECT t.taskId, t.taskName, p.prjTitle, t.startDate 
        FROM tasks t
        JOIN projects p ON t.projectId = p.prjId
        JOIN teamAssignments ta ON t.taskId = ta.taskId
        WHERE ta.userId = :userId AND ta.assignmentStatus = 'Pending'";
$stmt = $pdo->prepare($sql);
$stmt->execute([':userId' => $_SESSION['userId']]);
$assignments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Assigned Tasks</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php";?>
        <main>
            <h1>Assigned Tasks</h1>
            <table class="zebra-table">
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Task Name</th>
                        <th>Project Name</th>
                        <th>Start Date</th>
                        <th>Confirm</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(!empty($assignments)){
                    foreach ($assignments as $assignment): ?>
                        <tr>
                            <td><?php echo $assignment['taskId']; ?></td>
                            <td><?php echo $assignment['taskName']; ?></td>
                            <td><?php echo $assignment['prjTitle']; ?></td>
                            <td><?php echo $assignment['startDate']; ?></td>
                            <td>
                                <a href="confirmTask.php?taskId=<?php echo $assignment['taskId']; ?>">Confirm</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php } else { ?>
                            <tr>
                            <td colspan="5" style="text-align: center; color: red;">
                                <?php 
                                    echo "There are no tasks that needs your approval.";
                                ?>
                            </td>
                        </tr>
                         <?php } ?>
                </tbody>
            </table>
        </main>
        <?php require "footer.php" ?>
    </div>
</body>
</html>
