<?php
require "db.php.inc";
session_start();
require "auth.php";

checkAuthentication();

if ($_SESSION['role'] !== "Team Member") {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$successMessage = "";

if (!isset($_GET['taskId'])) {
    die("Error: Task ID is required.");
}

$pdo = getDatabaseConnection("root", "");

$sql = "SELECT t.taskId, t.taskName, p.prjTitle, t.progress, t.status
        FROM tasks t
        JOIN projects p ON t.projectId = p.prjId
        WHERE t.taskId = :taskId";
$stmt = $pdo->prepare($sql);
$stmt->execute([':taskId' => $_GET['taskId']]);
$task = $stmt->fetch();

if (!$task) {
    die("Error: Task not found.");
}

$progressValue = $_POST['progress'] ?? $task['progress'];
$statusValue = $task['status'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $progress = isset($_POST['progress']) ? (int)$_POST['progress'] : $task['progress'];
    $status = $_POST['status'] ?? $task['status'];

    if ($status === "Pending") {
        $progress = 0;
    } elseif ($status === "In Progress") {
        if ($progress === 0 || $progress === 100) {
            $progress = 1;
        }
    } elseif ($status === "Completed") {
        $progress = 100;
    }

    if ($progress == 100 && $status !== "Completed") {
        $status = "Completed";
    } elseif ($progress > 0 && $progress < 100 && $status !== "In Progress") {
        $status = "In Progress";
    } elseif ($progress == 0 && $status !== "Pending") {
        $status = "Pending";
    }

    if ($progress < 0 || $progress > 100) {
        $errors['progress'] = "Progress must be between 0% and 100%.";
    }

    if (empty($status) || !in_array($status, ["Pending", "In Progress", "Completed"])) {
        $errors['status'] = "Invalid status.";
    }

    if (empty($errors)) {
        $sql = "UPDATE tasks SET progress = :progress, status = :status WHERE taskId = :taskId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':progress' => $progress, ':status' => $status, ':taskId' => $_GET['taskId']]);

        $successMessage = "Task updated successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
    <title>Update Task</title>
    <script>
        function updateSliderValue(value) {
            document.getElementById("sliderValue").textContent = value + "%";
            const statusElement = document.getElementById("status");
            if (value == 0) {
                statusElement.value = "Pending";
            } else if (value > 0 && value < 100) {
                statusElement.value = "In Progress";
            } else if (value == 100) {
                statusElement.value = "Completed";
            }
        }

        function updateStatus(value) {
            const slider = document.getElementById("progress");
            const sliderValue = document.getElementById("sliderValue");
            if (value === "Pending") {
                slider.value = 0;
                sliderValue.textContent = "0%";
            } else if (value === "In Progress") {
                slider.value = slider.value > 0 && slider.value < 100 ? slider.value : 1;
                sliderValue.textContent = slider.value + "%";
            } else if (value === "Completed") {
                slider.value = 100;
                sliderValue.textContent = "100%";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php"; ?>
        <main>
            <h1>Update Task Progress</h1>

            <?php if (!empty($successMessage)): ?>
                <p class="success"><?php echo ($successMessage); ?></p>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-summary">
                    <h3>Validation Errors:</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo ($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="updateTask.php?taskId=<?php echo ($task['taskId']); ?>" method="post"
                  class="<?php echo !empty($errors) ? 'invalid' : ''; ?>">
                <fieldset>
                    <legend>Task Details</legend>
                    <label for="taskId">Task ID:</label>
                    <input type="text" id="taskId" value="<?php echo ($task['taskId']); ?>" disabled><br><br>

                    <label for="taskName">Task Name:</label>
                    <input type="text" id="taskName" value="<?php echo ($task['taskName']); ?>" disabled><br><br>

                    <label for="projectName">Project Name:</label>
                    <input type="text" id="projectName" value="<?php echo ($task['prjTitle']); ?>" disabled><br><br>
                </fieldset>
                <fieldset>
                    <legend>Update Progress</legend>

                    <label for="progress">Progress:</label>
                    <input type="range" id="progress" name="progress" min="0" max="100" 
                           value="<?php echo ($progressValue); ?>" 
                           oninput="updateSliderValue(this.value)">
                    <span id="sliderValue"><?php echo ($progressValue); ?>%</span><br><br>
                    <?php if (isset($errors['progress'])): ?>
                        <p class="error"><?php echo ($errors['progress']); ?></p>
                    <?php endif; ?>

                    <label for="status">Status:</label>
                    <select id="status" name="status" required onchange="updateStatus(this.value)">
                        <option value="Pending" <?php if ($statusValue === "Pending") echo "selected"; ?>>Pending</option>
                        <option value="In Progress" <?php if ($statusValue === "In Progress") echo "selected"; ?>>In Progress</option>
                        <option value="Completed" <?php if ($statusValue === "Completed") echo "selected"; ?>>Completed</option>
                    </select><br><br>
                    <?php if (isset($errors['status'])): ?>
                        <p class="error"><?php echo ($errors['status']); ?></p>
                    <?php endif; ?>

                    <button type="submit">Save Changes</button>
                    <a href="dashboard.php">Cancel</a>
                </fieldset>
            </form>
        </main>
        <?php require "footer.php"; ?>
    </div>
</body>
</html>
