<?php
require_once '../include/_autoload.php';
User::logout();
header('Location: ../login.php');
exit();