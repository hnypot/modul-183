<?php
require_once '../../config.php';
/*
 * why check if the address field/script filename is db.php?
 * because we don't want the user to land in this page
 * or generally we don't want the user to do anything with this
 * file at all for security reasons
 */
if (basename($_SERVER['SCRIPT_FILENAME']) == 'db.php'){
    header('Location: ../../index.php?msg=access_denied');
    exit();
}

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');