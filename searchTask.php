<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();


$pdo = getDatabaseConnection("root", "");

$whereClause = "1=1";
$params = [];
$orderBy = "t.taskId"; 
$orderDirection = "ASC"; 

if (!empty($_GET['sort']) && in_array($_GET['sort'], ['taskId', 'taskName', 'prjTitle', 'status', 'priority', 'startDate', 'endDate', 'progress'])) {
    $orderBy = $_GET['sort'];
}
if (!empty($_GET['direction']) && in_array(strtoupper($_GET['direction']), ['ASC', 'DESC'])) {
    $orderDirection = strtoupper($_GET['direction']);
}

if ($_SESSION['role'] === 'Manager') {
} elseif ($_SESSION['role'] === 'Team Leader' || $_SESSION['role'] === 'Project Leader') {
    $whereClause .= " AND p.teamLeaderID = :userId";
    $params[':userId'] = $_SESSION['userId'];
} elseif ($_SESSION['role'] === 'Team Member') {
    $whereClause .= " AND EXISTS (
        SELECT 1 FROM teamAssignments ta WHERE ta.taskId = t.taskId AND ta.userId = :userId
    )";
    $params[':userId'] = $_SESSION['userId'];
}

if (!empty($_GET['priority'])) {
    $whereClause .= " AND t.priority = :priority";
    $params[':priority'] = $_GET['priority'];
}

if (!empty($_GET['status'])) {
    $whereClause .= " AND t.status = :status";
    $params[':status'] = $_GET['status'];
}

if (!empty($_GET['startDate']) || !empty($_GET['endDate'])) {
    if (!empty($_GET['startDate']) && !empty($_GET['endDate'])) {
        $whereClause .= " AND (t.startDate >= :startDate AND t.endDate <= :endDate)";
        $params[':startDate'] = $_GET['startDate'];
        $params[':endDate'] = $_GET['endDate'];
    } elseif (!empty($_GET['startDate'])) {
        $whereClause .= " AND t.startDate >= :startDate";
        $params[':startDate'] = $_GET['startDate'];
    } elseif (!empty($_GET['endDate'])) {
        $whereClause .= " AND t.endDate <= :endDate";
        $params[':endDate'] = $_GET['endDate'];
    }
}

if (!empty($_GET['projectId'])) {
    $whereClause .= " AND p.prjId = :projectId";
    $params[':projectId'] = $_GET['projectId'];
}

$sql = "SELECT t.taskId, t.taskName, p.prjTitle, t.status, t.priority, t.startDate, t.endDate, t.progress
        FROM tasks t
        JOIN projects p ON t.projectId = p.prjId
        WHERE $whereClause
        ORDER BY $orderBy $orderDirection";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Tasks</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
    <?php require "header.php"; require "navigation.php";?>
        <main>
        <h1>Search Tasks</h1>
            <form method="get" action="searchTask.php">
                <fieldset>
                <legend>Search Filters</legend>
                    <label for="priority">Priority:</label>
                    <select id="priority" name="priority">
                        <option value="">Select Priority</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select><br><br>
                    <label for="status">Status:</label>
                    <select id="status" name="status">
                        <option value="">Select Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select><br><br>
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate"><br><br>
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate"><br><br>
                    <label for="projectId">Project:</label>
                    <select id="projectId" name="projectId">
                        <option value="">Select Project</option>
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
                        foreach ($projects as $project): ?>
                            <option value="<?php echo $project['prjId']; ?>"><?php echo $project['prjTitle']; ?></option>
                        <?php endforeach; ?>
                    </select><br><br>
                    <button type="submit">Search</button>
                </fieldset>
            </form>

            <?php if (!empty($tasks)): ?>
                <h2>Search Results</h2>
                <table class="zebra-table-sort">
                    <thead>
                        <tr>
                            <th><a href="?sort=taskId&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Task ID</a></th>
                            <th><a href="?sort=taskName&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Title</a></th>
                            <th><a href="?sort=prjTitle&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Project</a></th>
                            <th><a href="?sort=status&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Status</a></th>
                            <th><a href="?sort=priority&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Priority</a></th>
                            <th><a href="?sort=startDate&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Start Date</a></th>
                            <th><a href="?sort=endDate&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Due Date</a></th>
                            <th><a href="?sort=progress&direction=<?php echo $orderDirection === 'ASC' ? 'DESC' : 'ASC'; ?>">Completion Percentage %</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><a href="taskDetails.php?taskId=<?php echo $task['taskId']; ?>"><?php echo $task['taskId']; ?></a></td>
                                <td><?php echo $task['taskName']; ?></td>
                                <td><?php echo $task['prjTitle']; ?></td>
                                <td class="status-<?php echo strtolower(str_replace(' ', '-', $task['status'])); ?>"><?php echo $task['status']; ?></td>
                                <td class="priority-<?php echo strtolower($task['priority']); ?>"><?php echo $task['priority']; ?></td>
                                <td><?php echo $task['startDate']; ?></td>
                                <td><?php echo $task['endDate']; ?></td>
                                <td><?php echo $task['progress']; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="error">No tasks found matching the search criteria.</p>
            <?php endif; ?>
        </main>
        <?php require "footer.php"?>
    </div>
</body>
</html>