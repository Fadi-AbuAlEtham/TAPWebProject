<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();


$pdo = getDatabaseConnection("root", "");
$userId = $_SESSION['userId'];
$role = $_SESSION['role'];
$userName = $_SESSION['username'];

if ($role === "Manager") {
    $totalProjects = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    $totalTasks = $pdo->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
} elseif ($role === "Team Leader" || $role === "Project Leader") {
    $totalProjects = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE teamLeaderID = :userId");
    $totalProjects->execute([':userId' => $userId]);
    $totalProjects = $totalProjects->fetchColumn();

    $totalTasks = $pdo->prepare("SELECT COUNT(*) FROM tasks t JOIN projects p ON t.projectId = p.prjId WHERE p.teamLeaderID = :userId");
    $totalTasks->execute([':userId' => $userId]);
    $totalTasks = $totalTasks->fetchColumn();
} elseif ($role === "Team Member") {
    $acceptedTasks = $pdo->prepare("SELECT COUNT(*) FROM teamAssignments WHERE userId = :userId AND assignmentStatus='Pending'");
    $acceptedTasks->execute([':userId' => $userId]);
    $acceptedTasks = $acceptedTasks->fetchColumn();

    $assignedTasks = $pdo->prepare("SELECT COUNT(*) FROM teamAssignments WHERE userId = :userId");
    $assignedTasks->execute([':userId' => $userId]);
    $assignedTasks = $assignedTasks->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"?>

            <?php require "navigation.php"?>

            <main>
                <h1>Dashboard</h1>
                <p>Role: <?php echo $role; ?></p>

                <div class="summary">
                    <?php if ($role === "Manager"): ?>
                        <p>Total Projects: <?php echo $totalProjects; ?></p>
                        <p>Total Tasks: <?php echo $totalTasks; ?></p>
                    <?php elseif ($role === "Team Leader" || $role === "Project Leader"): ?>
                        <p>Total Projects You Lead: <?php echo $totalProjects; ?></p>
                        <p>Total Tasks in Your Projects: <?php echo $totalTasks; ?></p>
                    <?php elseif ($role === "Team Member"): ?>
                        <p>Pending tasks: <?php echo $acceptedTasks; ?></p>
                        <p>Assigned Tasks: <?php echo $assignedTasks; ?></p>
                    <?php endif; ?>
                </div>
                <?php
                $pdo = getDatabaseConnection("root", "");
                    $tasks = [];
                    if ($role === "Manager") {
                        $sql = "
                            SELECT 
                                t.*, 
                                p.prjTitle AS projectName 
                            FROM tasks t
                            JOIN projects p ON t.projectId = p.prjId
                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $tasks = $stmt->fetchAll();
                    } elseif ($role === "Team Leader" || $role === "Project Leader") {
                        $sql = "
                            SELECT 
                                t.*, 
                                p.prjTitle AS projectName 
                            FROM tasks t
                            JOIN projects p ON t.projectId = p.prjId
                            WHERE p.teamLeaderId = :teamLeaderId
                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':teamLeaderId' => $_SESSION['userId']]);
                        $tasks = $stmt->fetchAll();
                    } elseif ($role === "Team Member") {
                        $sql = "
                            SELECT 
                                t.*, 
                                p.prjTitle AS projectName 
                            FROM tasks t
                            JOIN projects p ON t.projectId = p.prjId
                            JOIN teamAssignments ta ON ta.taskId = t.taskId
                            WHERE ta.userId = :teamMemberId AND assignmentStatus='Accepted'
                        ";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':teamMemberId' => $_SESSION['userId']]);
                        $tasks = $stmt->fetchAll();
                    }
                ?>
                <h2>Task Summary</h2>
                <table class="zebra-table">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Priority</th>
                            <th>Progress</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($tasks)): ?>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td>
                                    <a href="taskDetails.php?taskId=<?php echo $task['taskId']; ?>">
                                        <?php echo htmlspecialchars($task['taskId']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($task['taskName']); ?></td>
                                <td><?php echo htmlspecialchars($task['projectName']); ?></td>
                                <td class="priority-<?php echo strtolower($task['priority']); ?>">
                                    <?php echo htmlspecialchars($task['priority']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($task['progress']); ?>%</td>
                                <td><?php echo htmlspecialchars($task['endDate']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: red;">
                                        <?php
                                        if ($role === "Manager") {
                                            echo "There are no tasks found in the database.";
                                        } else {
                                            echo "There are no tasks assigned to you.";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
                <?php
                if($role === "Manager"){
                    $stmt = $pdo->prepare("SELECT prjId, prjTitle, prjDesc, endDate FROM projects;");
                    $stmt->execute();
                    $projects = $stmt->fetchAll();
                }else if ($role === "Team Leader" || $role === "Project Leader"){
                    $stmt = $pdo->prepare("SELECT prjId, prjTitle, prjDesc, endDate FROM projects WHERE teamLeaderId=:teamLeaderId");
                    $stmt->bindParam(':teamLeaderId', $_SESSION['userId']);
                    $stmt->execute();
                    $projects = $stmt->fetchAll();
                }elseif ($role === "Team Member") {
                    $stmt = $pdo->prepare("
                        SELECT DISTINCT p.prjId, p.prjTitle, p.prjDesc, p.endDate
                        FROM projects p
                        JOIN tasks t ON t.projectId = p.prjId
                        JOIN teamAssignments ta ON ta.taskId = t.taskId
                        WHERE ta.userId = :teamMemberId AND assignmentStatus='Accepted'
                    ");
                    $stmt->bindParam(':teamMemberId', $_SESSION['userId']);
                    $stmt->execute();
                    $projects = $stmt->fetchAll();
                }                
                ?>

                <h2>Projects Summary</h2>
                <table class="zebra-table">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($projects)): ?>
                            <?php foreach ($projects as $project): ?>
                                <tr>
                                    <td><?php echo $project['prjId']; ?></td>
                                    <td><?php echo $project['prjTitle']; ?></td>
                                    <td><?php echo $project['prjDesc']; ?></td>
                                    <td><?php echo $project['endDate']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: red;">
                                        <?php 
                                        if ($role === "Manager") {
                                            echo "There are no projects found in the database.";
                                        } else {
                                            echo "There are no projects assigned to you.";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                    </tbody>
                </table>
            </main>
        <?php
            require "footer.php";
        ?>
    </div>
</body>
</html>


