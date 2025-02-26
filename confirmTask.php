<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Team Member") {
        header("Location: dashboard.php");
        exit();
    }

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$confirmationMessage = "";

if (!isset($_GET['taskId'])) {
    die("Error: Task ID is required.");
}

$pdo = getDatabaseConnection("root", "");

$sql = "SELECT t.taskId, t.taskName, t.taskDescription, t.priority, t.status, t.effort, t.startDate, t.endDate
        FROM tasks t
        WHERE t.taskId = :taskId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':taskId' => $_GET['taskId']]);
$task = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['accept'])) {
        $sql = "UPDATE teamAssignments SET assignmentStatus = 'Accepted' WHERE taskId = :taskId AND userId = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':taskId' => $task['taskId'], ':userId' => $_SESSION['userId']]);
        $confirmationMessage = "<p style='color:green;'>Task successfully accepted and activated. Redirecting...</p>";
    } elseif (isset($_POST['reject'])) {
        $sql = "DELETE FROM teamAssignments WHERE taskId = :taskId AND userId = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':taskId' => $task['taskId'], ':userId' => $_SESSION['userId']]);
        $confirmationMessage = "<p style='color:red;'>Task assignment successfully rejected. Redirecting...</p>";
    }

    header("refresh:3;url=assignments.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
    <title>Task Confirmation</title>
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php"; ?>
        <main>
            <h1>Confirm Task</h1>

            <?php if (!empty($confirmationMessage)): ?>
                <?php echo $confirmationMessage; ?>
            <?php endif; ?>

            <form action="confirmTask.php?taskId=<?php echo ($task['taskId']); ?>" method="post">
                <fieldset>
                    <legend>Task Details</legend>
                    <label for="taskId">Task ID:</label>
                    <input type="text" id="taskId" value="<?php echo ($task['taskId']); ?>" disabled><br><br>

                    <label for="taskName">Task Name:</label>
                    <input type="text" id="taskName" value="<?php echo ($task['taskName']); ?>" disabled><br><br>

                    <label for="description">Description:</label>
                    <textarea id="description" disabled><?php echo ($task['taskDescription']); ?></textarea><br><br>

                    <label for="priority">Priority:</label>
                    <input type="text" id="priority" value="<?php echo ucfirst(($task['priority'])); ?>" disabled><br><br>

                    <label for="status">Status:</label>
                    <input type="text" id="status" value="Pending" disabled><br><br>

                    <label for="effort">Total Effort:</label>
                    <input type="text" id="effort" value="<?php echo ($task['effort']); ?> Man-Months" disabled><br><br>

                    <label for="startDate">Start Date:</label>
                    <input type="text" id="startDate" value="<?php echo ($task['startDate']); ?>" disabled><br><br>

                    <label for="endDate">End Date:</label>
                    <input type="text" id="endDate" value="<?php echo ($task['endDate']); ?>" disabled><br><br>
                </fieldset>
                <button type="submit" name="accept">Accept Task</button>
                <button type="submit" name="reject">Reject Task</button>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>