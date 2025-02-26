<?php
session_start();
require_once 'db.php.inc';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projectId = $_POST['projectId'] ?? null;
    $teamLeaderId = $_POST['teamLeader'] ?? null;

    if (empty($projectId)) {
        echo "<p style='color:red;'>Error: No project ID provided.</p>";
        echo "<a href='prjNotAssigned.php'>Back to Projects</a>";
        exit();
    }

    if (empty($teamLeaderId)) {
        echo "<p style='color:red;'>Error: You must select a team leader.</p>";
        echo "<a href='allocateTeamLeaderForm.php?projectId=" . $projectId . "'>Go Back</a>";
        exit();
    }

    try {
        $pdo = getDatabaseConnection("root", "");

        $stmt = $pdo->prepare("
            UPDATE projects
            SET teamLeaderID = :teamLeaderId
            WHERE prjId = :projectId
        ");
        $stmt->execute([
            ':teamLeaderId' => $teamLeaderId,
            ':projectId' => $projectId
        ]);

        echo "<h3 style='color:green;'>Team Leader successfully allocated to Project ID: $projectId!</h3>";
        echo "<a href='prjNotAssigned.php'>Back to Projects</a>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Database Error: " . $e->getMessage() . "</p>";
    }
}
