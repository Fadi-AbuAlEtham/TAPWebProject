<?php
    session_start();
    require "db.php.inc";
    require "auth.php";

    checkAuthentication();

    if ($_SESSION['role'] !== "Manager") {
        header("Location: dashboard.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Not Assigned Projects</title>
    <link rel="icon" href="data:image/x-icon;base64,AA==" type="image/x-icon">
</head>
<body>
    <div class="container">
        <?php require "header.php"; require "navigation.php";?>

        <main>
            <h2>Not Assigned Projects</h2>
            <table>
                <tr>
                    <th>Project ID</th>
                    <th>Project Title</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Action</th>
                </tr>
                <?php
                    try {
                        $pdo = getDatabaseConnection("root", "");
                        $sql = "SELECT * FROM projects WHERE teamLeaderID IS NULL Order by startDate ASC;";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                        $rows = $stmt->fetchAll();

                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . ($row['prjId']) . "</td>";
                            echo "<td>" . ($row['prjTitle']) . "</td>";
                            echo "<td>" . ($row['startDate']) . "</td>";
                            echo "<td>" . ($row['endDate']) . "</td>";
                            echo "<td><a href='allocateTeamLeaderForm.php?projectId=" . $row['prjId'] . "'>Assign</a></td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . ($e->getMessage());
                    }
                ?>
            </table>
        </main>
        <?php require "footer.php" ?>
    </div>
</body>
</html>
