<?php
define('DBHOST', 'localhost');
define('DBNAME', 'web1220316_WebTAPProject');
define('DBCONNSTRING', "mysql:host=".DBHOST.";dbname=".DBNAME.";charset=utf8mb4");

function getDatabaseConnection($username, $password) {
    try {
        $pdo = new PDO(DBCONNSTRING, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
