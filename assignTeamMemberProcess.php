<?php
require "db.php.inc";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pdo = getDatabaseConnection("root", "");

    $taskId = $_POST['taskId'];
    $teamMember = $_POST['teamMember'];
    $role = $_POST['role'];
    $contribution = $_POST['contribution'];

    $stmt = $pdo->prepare("SELECT SUM(contribution) AS total FROM teamAssignments WHERE taskId = :taskId");
    $stmt->execute([':taskId' => $taskId]);
    $totalContribution = $stmt->fetch()['total'] ?? 0;

    if ($totalContribution + $contribution > 100) {
        die("Error: Total contribution exceeds 100%.");
    }

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

    if (isset($_POST['addAnother'])) {
        header("Location: assignTeamMemberForm.php?taskId=$taskId");
        exit();
    } elseif (isset($_POST['finishAllocation'])) {
        header("Location: assignTask.php");
        exit();
    }
}
?>