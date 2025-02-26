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

$currentUserId = $_SESSION['userId'] ?? null;

if (!$currentUserId) {
    die("Unauthorized access. Please log in.");
}

$whereClause = "ta.userId = :currentUserId AND ta.assignmentStatus = 'Accepted'";
$params = [':currentUserId' => $currentUserId];

if (!empty($_GET['taskId'])) {
    $whereClause .= " AND t.taskId LIKE :taskId";
    $params[':taskId'] = "%" . $_GET['taskId'] . "%";
}

if (!empty($_GET['taskName'])) {
    $whereClause .= " AND t.taskName LIKE :taskName";
    $params[':taskName'] = "%" . $_GET['taskName'] . "%";
}

if (!empty($_GET['projectName'])) {
    $whereClause .= " AND p.prjTitle LIKE :projectName";
    $params[':projectName'] = "%" . $_GET['projectName'] . "%";
}

$sql = "SELECT t.taskId, t.taskName, p.prjTitle, t.progress, t.status
        FROM tasks t
        JOIN projects p ON t.projectId = p.prjId
        JOIN teamAssignments ta ON t.taskId = ta.taskId
        WHERE $whereClause";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Search Tasks</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php";?>
        <main>
        <h1>Search Tasks</h1>
            <form method="get" action="searchTaskstoUpdate.php">
                <label for="taskId">Task ID:</label>
                <input type="text" id="taskId" name="taskId" placeholder="Search by Task ID"><br><br>
                <label for="taskName">Task Name:</label>
                <input type="text" id="taskName" name="taskName" placeholder="Search by Task Name"><br><br>
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName" name="projectName" placeholder="Search by Project Name"><br><br>
                <button type="submit">Search</button>
            </form>

            <?php if (!empty($tasks)): ?>
                <h2>Search Results</h2>
                <table class="zebra-table">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Project Name</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo $task['taskId']; ?></td>
                                <td><?php echo $task['taskName']; ?></td>
                                <td><?php echo $task['prjTitle']; ?></td>
                                <td><?php echo $task['progress']; ?>%</td>
                                <td><?php echo $task['status']; ?></td>
                                <td>
                                    <a href="updateTask.php?taskId=<?php echo $task['taskId']; ?>">Update</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="error">No tasks found matching the search criteria.</p>
            <?php endif; ?>
        </main>
        <?php require "footer.php";?>
    </div>
</body>
</html>