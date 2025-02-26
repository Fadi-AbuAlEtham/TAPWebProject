<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();


if (!isset($_GET['taskId']) || empty($_GET['taskId'])) {
    die("Error: Task ID is required.");
}

$pdo = getDatabaseConnection("root", "");

$sql = "SELECT t.taskId, t.taskName, t.taskDescription, p.prjTitle, t.startDate, t.endDate, t.progress, t.status, t.priority
        FROM tasks t
        JOIN projects p ON t.projectId = p.prjId
        WHERE t.taskId = :taskId";
$stmt = $pdo->prepare($sql);
if (!$stmt->execute([':taskId' => $_GET['taskId']])) {
    print_r($stmt->errorInfo());
    die("Error fetching task details.");
}
$task = $stmt->fetch();

if (!$task) {
    die("Error: Task not found.");
}

$sql = "
    SELECT u.userId, u.userName,
           t.startDate, t.endDate, ta.contribution
    FROM teamAssignments ta
    JOIN users u ON ta.userId = u.userId
    JOIN tasks t ON ta.taskId = t.taskId
    WHERE ta.taskId = :taskId
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':taskId' => $_GET['taskId']]);
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles2.css">
    <title>Task Details</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
    <?php require "header.php"; require "navigation.php"; ?>
    <main>
        <?php if ($task): ?>
            <section class="task-details">
                <h2>Task Details</h2>
                <form>
                    <fieldset>
                        <legend>Task Information</legend>
                        <label for="taskId"><strong>Task ID:</strong></label>
                        <input type="text" id="taskId" value="<?php echo ($task['taskId']); ?>" readonly><br><br>

                        <label for="taskName"><strong>Task Name:</strong></label>
                        <input type="text" id="taskName" value="<?php echo ($task['taskName']); ?>" readonly><br><br>

                        <label for="taskDescription"><strong>Description:</strong></label>
                        <textarea id="taskDescription" readonly><?php echo ($task['taskDescription']); ?></textarea><br><br>

                        <label for="prjTitle"><strong>Project:</strong></label>
                        <input type="text" id="prjTitle" value="<?php echo ($task['prjTitle']); ?>" readonly><br><br>

                        <label for="startDate"><strong>Start Date:</strong></label>
                        <input type="text" id="startDate" value="<?php echo ($task['startDate']); ?>" readonly><br><br>

                        <label for="endDate"><strong>End Date:</strong></label>
                        <input type="text" id="endDate" value="<?php echo ($task['endDate']); ?>" readonly><br><br>

                        <label for="progress"><strong>Completion Percentage:</strong></label>
                        <input type="text" id="progress" value="<?php echo ($task['progress']); ?>%" readonly><br><br>

                        <label for="status"><strong>Status:</strong></label>
                        <input type="text" id="status" value="<?php echo ($task['status']); ?>" readonly><br><br>

                        <label for="priority"><strong>Priority:</strong></label>
                        <input type="text" id="priority" value="<?php echo ($task['priority']); ?>" readonly><br><br>
                    </fieldset>
                </form>
            </section>
        <?php else: ?>
            <p>No task details available.</p>
        <?php endif; ?>

        <?php if (!empty($teamMembers)): ?>
            <section class="team-members">
                <h2>Team Members</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Effort Allocated (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teamMembers as $member): ?>
                            <tr>
                                <td><img src="<?php echo file_exists('uploads/photos/user.png') ? 'uploads/photos/user.png' : 'icons/user.png'; ?>" alt="Profile"></td>
                                <td><?php echo ($member['userId']); ?></td>
                                <td><?php echo ($member['userName']); ?></td>
                                <td><?php echo ($member['startDate']); ?></td>
                                <td><?php 
                                    if($task["status"] === "In Progress")
                                        echo "<strong>In Progress</strong>";
                                    else
                                        echo ($member['endDate']); ?></td>
                                <td><?php echo ($member['contribution']); ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        <?php else: ?>
            <p>No team members assigned to this task.</p>
        <?php endif; ?>
    </main>
    <?php require "footer.php"; ?>
    </div>
</body>
</html>
