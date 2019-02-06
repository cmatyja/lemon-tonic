<?php
try {
    if (!session_start() || !$_SESSION['admin']) {
        throw new Exception('Merci de vous authentifier');
    } else if ($_SESSION['admin'] !== true) {
        throw new Exception('Désolé, vous ne pouvez pas visualiser cette page');
    }
} catch (Exception $ex) {
    header('location: login.php');
    exit();
}