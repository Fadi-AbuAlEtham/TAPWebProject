<?php
session_start();
require "db.php.inc";
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Team Leader" && $_SESSION['role'] != "Project Leader") {
        header("Location: dashboard.php");
        exit();
    }

$generalErrors = [];
$fieldErrors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = getDatabaseConnection("root", "");

    $taskId = $_POST['taskId'] ?? '';
    $taskName = $_POST['taskName'] ?? '';
    $taskDescription = $_POST['taskDescription'] ?? '';
    $projectId = $_POST['project'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $effort = $_POST['effort'] ?? '';
    $status = $_POST['status'] ?? '';
    $priority = $_POST['priority'] ?? '';

    if (empty($taskId)) {
        do {
            $taskId = mt_rand(100000, 999999);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE taskId = :taskId");
            $stmt->execute([':taskId' => $taskId]);
            $count = $stmt->fetchColumn();
        } while ($count > 0); 
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE taskId = :taskId");
        $stmt->execute([':taskId' => $taskId]);
        if ($stmt->fetchColumn() > 0) {
            $fieldErrors['taskId'] = "The Task ID already exists. Please provide a unique ID.";
        }
    }
    
    if (empty($taskName)) {
        $fieldErrors['taskName'] = "Task Name is required.";
    }
    if (empty($taskDescription)) {
        $fieldErrors['taskDescription'] = "Task Description is required.";
    }
    if (empty($projectId)) {
        $fieldErrors['project'] = "Project selection is required.";
    }
    if (empty($startDate)) {
        $fieldErrors['startDate'] = "Start Date is required.";
    }
    if (empty($endDate)) {
        $fieldErrors['endDate'] = "End Date is required.";
    }
    if (empty($effort) || $effort <= 0) {
        $fieldErrors['effort'] = "Effort must be a positive number.";
    }
    if (empty($status)) {
        $fieldErrors['status'] = "Status is required.";
    }    
    if (empty($priority)) {
        $fieldErrors['priority'] = "Priority is required.";
    }

    if (empty($fieldErrors['project'])) {
        $projectStmt = $pdo->prepare("
            SELECT startDate AS projectStartDate, endDate AS projectEndDate 
            FROM projects 
            WHERE prjId = :projectId AND teamLeaderID = :teamLeaderID
        ");
        $projectStmt->execute([
            ':projectId' => $projectId,
            ':teamLeaderID' => $_SESSION['userId'] ?? ''
        ]);
        $project = $projectStmt->fetch();

        if (!$project) {
            $fieldErrors['project'] = "The selected project does not exist or is not managed by you.";
        } else {
            $projectStartDate = $project['projectStartDate'];
            $projectEndDate = $project['projectEndDate'];

            if ($startDate < $projectStartDate) {
                $fieldErrors['startDate'] = "Task Start Date cannot be earlier than the Project Start Date ($projectStartDate).";
            }
            if ($endDate > $projectEndDate) {
                $fieldErrors['endDate'] = "Task End Date cannot exceed the Project End Date ($projectEndDate).";
            }
        }
    }

    if (empty($fieldErrors)) {
        try {
            $progress = 0; 
            if ($status === 'Completed') {
                $progress = 100;
            } elseif ($status === 'In Progress') {
                $progress = 1;
            }

            $stmt = $pdo->prepare("
                INSERT INTO tasks (taskId, taskName, taskDescription, projectId, startDate, endDate, effort, status, priority, progress)
                VALUES (:taskId, :taskName, :taskDescription, :projectId, :startDate, :endDate, :effort, :status, :priority, :progress)
            ");
            $stmt->execute([
                ':taskId' => $taskId,
                ':taskName' => $taskName,
                ':taskDescription' => $taskDescription,
                ':projectId' => $projectId,
                ':startDate' => $startDate,
                ':endDate' => $endDate,
                ':effort' => $effort,
                ':status' => $status,
                ':priority' => $priority,
                ':progress' => $progress
            ]);
            $successMessage = "Task '$taskName' successfully created.";
        } catch (PDOException $e) {
            $generalErrors[] = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Task Creation Process</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php 
            require "header.php"; 
            require "navigation.php";
        ?>

        <main>
            <?php if (!empty($successMessage)): ?>
                <p class="success"><?php echo $successMessage; ?></p>
            <?php endif; ?>

            <?php if (!empty($generalErrors)): ?>
                <p class="error"><?php echo $generalErrors; ?></p>
            <?php endif; ?>

            <form action="" method="post">
                <fieldset>
                    <legend>Task Creation Form</legend>

                    <label for="taskId">Task ID:</label>
                    <input type="text" id="taskId" name="taskId" class="<?php echo isset($fieldErrors['taskId']) ? 'invalid' : ''; ?>" 
                    placeholder="e.g. 1330234213"       
                    value="<?php echo ($_POST['taskId'] ?? ''); ?>" >
                    <?php if (isset($fieldErrors['taskId'])): ?>
                        <span class="error"><?php echo $fieldErrors['taskId']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="taskName">Task Name:</label>
                    <input type="text" id="taskName" name="taskName" class="<?php echo isset($fieldErrors['taskName']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['taskName'] ?? ''); ?>" 
                           placeholder="e.g. Some Task"
                           required>
                    <?php if (isset($fieldErrors['taskName'])): ?>
                        <span class="error"><?php echo $fieldErrors['taskName']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="taskDescription">Task Description:</label>
                    <textarea id="taskDescription" name="taskDescription" class="<?php echo isset($fieldErrors['taskDescription']) ? 'invalid' : ''; ?>" 
                             placeholder="Task Description" 
                    required><?php echo ($_POST['taskDescription'] ?? ''); ?></textarea>
                    <?php if (isset($fieldErrors['taskDescription'])): ?>
                        <span class="error"><?php echo $fieldErrors['taskDescription']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="project">Project Name:</label>
                    <select id="project" name="project" class="<?php echo isset($fieldErrors['project']) ? 'invalid' : ''; ?>" required>
                        <option value="" disabled selected>Select Project</option>
                        <?php
                        $pdo = getDatabaseConnection("root", "");
                        $sql = "SELECT prjId, prjTitle FROM projects WHERE teamLeaderID = :teamLeaderID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([':teamLeaderID' => $_SESSION['userId'] ?? '']);
                        $projects = $stmt->fetchAll();
                        foreach ($projects as $project) {
                            $selected = ($_POST['project'] ?? '') == $project['prjId'] ? 'selected' : '';
                            echo "<option value='" . ($project['prjId']) . "' $selected>";
                            echo ($project['prjTitle']);
                            echo "</option>";
                        }
                        ?>
                    </select>
                    <?php if (isset($fieldErrors['project'])): ?>
                        <span class="error"><?php echo $fieldErrors['project']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" class="<?php echo isset($fieldErrors['startDate']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['startDate'] ?? ''); ?>" 
                           required>
                    <?php if (isset($fieldErrors['startDate'])): ?>
                        <span class="error"><?php echo $fieldErrors['startDate']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate" class="<?php echo isset($fieldErrors['endDate']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['endDate'] ?? ''); ?>" 
                           required>
                    <?php if (isset($fieldErrors['endDate'])): ?>
                        <span class="error"><?php echo $fieldErrors['endDate']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="effort">Effort (Man-Months):</label>
                    <input type="number" id="effort" name="effort" class="<?php echo isset($fieldErrors['effort']) ? 'invalid' : ''; ?>" 
                           value="<?php echo ($_POST['effort'] ?? ''); ?>" 
                           placeholder="e.g. 25"
                           required>
                    <?php if (isset($fieldErrors['effort'])): ?>
                        <span class="error"><?php echo $fieldErrors['effort']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="status">Status:</label>
                    <select id="status" name="status" class="<?php echo isset($fieldErrors['status']) ? 'invalid' : ''; ?>" required>
                        <option value="Pending" <?php echo ($_POST['status'] ?? 'Pending') === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="In Progress" <?php echo ($_POST['status'] ?? '') === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Completed" <?php echo ($_POST['status'] ?? '') === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                    <?php if (isset($fieldErrors['status'])): ?>
                        <span class="error"><?php echo $fieldErrors['status']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <label for="priority">Priority:</label>
                    <select id="priority" name="priority" class="<?php echo isset($fieldErrors['priority']) ? 'invalid' : ''; ?>" required>
                        <option value="" disabled selected>Select Priority</option>
                        <option value="High" <?php echo ($_POST['priority'] ?? '') === 'High' ? 'selected' : ''; ?>>High</option>
                        <option value="Medium" <?php echo ($_POST['priority'] ?? '') === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="Low" <?php echo ($_POST['priority'] ?? '') === 'Low' ? 'selected' : ''; ?>>Low</option>
                    </select>
                    <?php if (isset($fieldErrors['priority'])): ?>
                        <span class="error"><?php echo $fieldErrors['priority']; ?></span>
                    <?php endif; ?>
                    <br><br>

                    <button type="submit">Create Task</button>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>