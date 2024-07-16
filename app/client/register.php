<?php
session_start();
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['id'])) {
    header('Location: ../../index.php?msg=already_logged_in');
    exit();
}
if (isset($_SESSION['token'])) {
    session_unset();
    $_SESSION = array();
    session_destroy();
    session_regenerate_id(true);
    header('Location: ../../index.php?msg=invalid_registration');
    exit();
}
include('../server/error.php');
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<script>alert("' . $error[$msg] . '")</script>';
}
require_once('../server/csrf.php');
generate_csrf_token();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../../assets/css/app.css">
    <title>sign up</title>
</head>
<body>
<div class="authTitle">
    <h1>sign up</h1>
    <a href="../../index.php">
        <button class="btn">
            back
        </button>
    </a>
</div>
<div class="form-container">
    <form method="post" action="../server/register_process.php" class="form">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="username">username</label>
        <input type="text" name="username" placeholder="username" class="input" required>
        <label for="email">email</label>
        <input type="email" name="email" placeholder="email" class="input" required>
        <label for="password">password</label>
        <input type="password" name="password" placeholder="password" class="input" id="pass" required>
        <label for="confirm_password">confirm password</label>
        <input type="password" name="confirm_password" placeholder="confirm password" class="input" required>
        <button type="submit" name="signupBtn" class="btn">sign up</button>
    </form>
</div>
</body>
</html>