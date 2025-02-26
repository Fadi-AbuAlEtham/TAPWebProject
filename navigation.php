 <?php $role = $_SESSION['role'];?>
<nav>
    <ul>
        <?php if ($role === "Manager"): ?>
            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="addProject.php" class="nav-link">Add Project</a></li>
            <li><a href="prjNotAssigned.php" class="nav-link">Allocate Team Leader</a></li>
            <li><a href="searchTask.php" class="nav-link">Search Tasks</a></li>
        <?php elseif ($role === "Team Leader" || $role === "Project Leader"): ?>
            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="createTask.php" class="nav-link">Create Task</a></li>
            <li><a href="assignTask.php" class="nav-link">Assign Team Members</a></li>
            <li><a href="searchTask.php" class="nav-link">Search Tasks</a></li>
        <?php elseif ($role === "Team Member"): ?>
            <?php
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM teamAssignments WHERE userId = :userId AND assignmentStatus = 'Pending'");
            $stmt->execute([':userId' => $_SESSION['userId']]);
            $pendingAssignmentsCount = $stmt->fetchColumn();
            $highlightClass = $pendingAssignmentsCount > 0 ? 'highlight' : '';
            ?>
            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="assignments.php" class="nav-link <?php echo $highlightClass; ?>">Accept Task Assignments</a></li>
            <li><a href="searchTaskstoUpdate.php" class="nav-link">Search and Update Task Progress</a></li>
            <li><a href="searchTask.php" class="nav-link">Search Tasks</a></li>
        <?php endif; ?>
    </ul>
</nav>