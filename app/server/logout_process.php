<?php
session_start();
if (session_status() == PHP_SESSION_ACTIVE && !isset($_SESSION['id'])) {
    header('Location: ../client/login.php?msg=not_logged_in');
    exit();
}
session_unset();
$_SESSION = array();
session_destroy();
session_regenerate_id(true);
header("Location: ../../index.php?msg=successful_logout");