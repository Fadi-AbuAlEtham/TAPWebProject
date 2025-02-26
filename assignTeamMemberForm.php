<?php
require "db.php.inc";
session_start();
require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Team Leader" && $_SESSION['role'] !== "Project Leader") {
        header("Location: dashboard.php");
        exit();
    }

$errors = [];
$successMessage = ""; 

$pdo = getDatabaseConnection("root", "");

if (isset($_GET['successMessage'])) {
    $successMessage = urldecode($_GET['successMessage']);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskId = $_POST['taskId'] ?? '';
} elseif (isset($_GET['taskId'])) {
    $taskId = $_GET['taskId'];
} else {
    die("Error: Task ID is required.");
}

$sql = "SELECT taskId, taskName, startDate, endDate FROM tasks WHERE taskId = :taskId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':taskId' => $taskId]);
$task = $stmt->fetch();

if (!$task) {
    die("Error: Task not found.");
}

$sql = "
    SELECT users.userId, users.userName 
    FROM users 
    WHERE users.userId NOT IN (
        SELECT teamAssignments.userId 
        FROM teamAssignments 
        WHERE teamAssignments.taskId = :taskId
    ) AND users.role = 'Team Member'
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':taskId' => $taskId]);
$teamMembers = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $teamMember = $_POST['teamMember'] ?? '';
    $role = $_POST['role'] ?? '';
    $contribution = $_POST['contribution'] ?? '';

    if (empty($teamMember)) {
        $errors['teamMember'] = "You must select a team member.";
    }
    if (empty($role)) {
        $errors['role'] = "You must select a role.";
    }
    if (empty($contribution)) {
        $errors['contribution'] = "Contribution is required.";
    } elseif ($contribution < 1 || $contribution > 100) {
        $errors['contribution'] = "Contribution must be between 1% and 100%.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT SUM(contribution) AS total FROM teamAssignments WHERE taskId = :taskId");
        $stmt->execute([':taskId' => $taskId]);
        $totalContribution = $stmt->fetch()['total'] ?? 0;

        if ($totalContribution + $contribution > 100) {
            $errors['contribution'] = "Total contribution for the task cannot exceed 100%.\nTotal Contribution = " . $totalContribution . "%";
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO teamAssignments (taskId, userId, role, contribution)
            VALUES (:taskId, :userId, :role, :contribution)
        ");
        $stmt->execute([
            ':taskId' => $taskId,
            ':userId' => $teamMember,
            ':role' => $role,
            ':contribution' => $contribution
        ]);

        $successMessage = "Team member successfully assigned to Task: " . htmlspecialchars($taskId) . " as " . htmlspecialchars($role) . ".";

        if (isset($_POST['addAnother'])) {
            header("Location: assignTeamMemberForm.php?taskId=$taskId&successMessage=" . urlencode($successMessage));
            exit();
        } elseif (isset($_POST['finishAllocation'])) {
            echo "<meta http-equiv='refresh' content='2;url=assignTask.php'>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <title>Assign Team Members</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php"; ?>
        <main>
            <h1>Assign Team Members to Task</h1>

            <?php if (!empty($successMessage)): ?>
                <p class="success"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <form action="assignTeamMemberForm.php" method="post">
                <fieldset>
                    <legend>Task Details</legend>
                    <p>Task ID: 
                        <input type="text" name="taskId" 
                               value="<?php echo htmlspecialchars($task['taskId'] ?? $_POST['taskId'] ?? ''); ?>" 
                               readonly>
                    </p>
                    <p>Task Name: 
                        <input type="text" name="taskName" 
                               value="<?php echo htmlspecialchars($task['taskName'] ?? ''); ?>" 
                               readonly>
                    </p>
                </fieldset>
                <fieldset>
                    <legend>Assign Team Member</legend>
                    <p>Start Date: 
                    <input type="text" name="taskStartDate" value="<?php echo date('Y-m-d'); ?>" readonly></p>
                    <?php if (!empty($teamMembers)): ?>
                        <label for="teamMember">Team Member:</label>
                        <select id="teamMember" name="teamMember" class="<?php echo isset($errors['teamMember']) ? 'invalid' : ''; ?>" required>
                            <option value="" disabled selected>Select a Team Member</option>
                            <?php foreach ($teamMembers as $member): ?>
                                <option value="<?php echo htmlspecialchars($member['userId']); ?>" 
                                    <?php echo ($_POST['teamMember'] ?? '') === $member['userId'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($member['userName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['teamMember'])): ?>
                            <p class="error"><?php echo htmlspecialchars($errors['teamMember']); ?></p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p>No available team members for this task.</p>
                    <?php endif; ?>

                    <label for="role">Role:</label>
                    <select id="role" name="role" class="<?php echo isset($errors['role']) ? 'invalid' : ''; ?>" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="Developer" <?php echo ($_POST['role'] ?? '') === 'Developer' ? 'selected' : ''; ?>>Developer</option>
                        <option value="Designer" <?php echo ($_POST['role'] ?? '') === 'Designer' ? 'selected' : ''; ?>>Designer</option>
                        <option value="Tester" <?php echo ($_POST['role'] ?? '') === 'Tester' ? 'selected' : ''; ?>>Tester</option>
                        <option value="Analyst" <?php echo ($_POST['role'] ?? '') === 'Analyst' ? 'selected' : ''; ?>>Analyst</option>
                        <option value="Support" <?php echo ($_POST['role'] ?? '') === 'Support' ? 'selected' : ''; ?>>Support</option>
                    </select>
                    <?php if (isset($errors['role'])): ?>
                        <p class="error"><?php echo htmlspecialchars($errors['role']); ?></p>
                    <?php endif; ?>

                    <label for="contribution">Contribution Percentage(%):</label>
                    <input type="number" id="contribution" name="contribution" 
                           value="<?php echo htmlspecialchars($_POST['contribution'] ?? ''); ?>" 
                           min="1" 
                           class="<?php echo isset($errors['contribution']) ? 'invalid' : ''; ?>" required>
                    <?php if (isset($errors['contribution'])): ?>
                        <p class="error"><?php echo htmlspecialchars($errors['contribution']); ?></p>
                    <?php endif; ?>
                    <br><br>
                    <button type="submit" name="addAnother">Add Another Team Member</button>
                    <button type="submit" name="finishAllocation">Finish Allocation</button>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>