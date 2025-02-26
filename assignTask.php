<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Team Leader" && $_SESSION['role'] !== "Project Leader") {
        header("Location: dashboard.php");
        exit();
    }

$pdo = getDatabaseConnection("root", "");

$sql = "SELECT prjId, prjTitle FROM projects WHERE teamLeaderID = :teamLeaderID";
$stmt = $pdo->prepare($sql);
$stmt->execute([':teamLeaderID' => $_SESSION['userId']]);
$projects = $stmt->fetchAll();

$tasks = [];
if (!empty($_GET['projectId'])) {
    $sql = "SELECT taskId, taskName, startDate, status, priority 
            FROM tasks 
            WHERE projectId = :projectId
            ORDER BY 
                (SELECT COUNT(*) FROM teamAssignments WHERE tasks.taskId = teamAssignments.taskId) ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':projectId' => $_GET['projectId']]);
    $tasks = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Assign Task</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php"; ?>
        <main>
        <h1>Assign Team Members</h1>
        <form method="get" action="assignTask.php">
            <label for="projectId">Select Project:</label>
            <select id="projectId" name="projectId" required>
                <option value="" disabled selected>Select a Project</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?php echo $project['prjId']; ?>" <?php echo isset($_GET['projectId']) && $_GET['projectId'] === $project['prjId'] ? 'selected' : ''; ?>>
                        <?php echo $project['prjTitle']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">View Tasks</button>
        </form>

        <?php if (isset($_GET['projectId'])): ?>
            <?php if (!empty($tasks)): ?>
                <h2>Tasks</h2>
                <table class="zebra-table">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo $task['taskId']; ?></td>
                                <td><?php echo $task['taskName']; ?></td>
                                <td><?php echo $task['startDate']; ?></td>
                                <td><?php echo $task['status']; ?></td>
                                <td><?php echo $task['priority']; ?></td>
                                <td>
                                    <a href="assignTeamMemberForm.php?taskId=<?php echo $task['taskId']; ?>">Assign Team Members</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="error">No tasks found for this project.</p>
            <?php endif; ?>
        <?php endif; ?>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>