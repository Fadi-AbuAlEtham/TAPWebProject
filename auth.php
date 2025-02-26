<?php
session_start();

function checkAuthentication() {
    if (empty($_SESSION['userId'])) {
        header("Location: login.php");
        exit();
    }
}
?>
