<?php
session_start();
if (session_status() == PHP_SESSION_ACTIVE && !isset($_SESSION['id'])) {
    session_unset();
    $_SESSION = array();
    session_destroy();
    session_regenerate_id(true);
    header('Location: ../client/login.php?msg=not_logged_in');
    exit();
}
include('../server/error.php');
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<script>alert("' . $error[$msg] . '")</script>';
}
$id = $_SESSION['id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../assets/css/app.css">
    <title>Document</title>
</head>
<body>
<div class="profile-container">
    <p>welcome <?php echo $username ?> !</p>
    <p>your profile:</p>
    <ul>
        <li>
            user id: <?php echo $id ?>
        </li>
        <li>
            username: <?php echo $username ?>
        </li>
        <li>
            email: <?php echo $email ?>
        </li>
    </ul>
</div>
<div class="navProfile">
    <a href="../../index.php">
        <button class="btn">
            home
        </button>
    </a>
    <a href="../server/logout_process.php">
        <button class="btn">
            logout
        </button>
    </a>
</div>
</body>
</html>