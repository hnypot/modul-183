<?php
session_start();
/*
 * why check if the session variable 'token' isset? because, the user can access this page through the token
 * verification where the user's session variables are set (which were set beforehand to pass over the email, username
 * and password, respectively) and since we don't want this for security reasons we delete and unset the last session
 * and regenerate a new session id, ultimately to prevent session hijacking and many other (possible) vulnerabilities
 */
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['token'])) {
    session_unset();
    $_SESSION = array();
    session_destroy();
    session_regenerate_id(true);
    header('Location: ?msg=invalid_registration');
    exit();
}
include('app/server/error.php');
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<script>alert("' . $error[$msg] . '")</script>';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/app.css" type="text/css">
    <title>index</title>
</head>
<body>
<div class="container">
<?php
if (session_status() == PHP_SESSION_ACTIVE) {
    if (!isset($_SESSION['id'])) {
        ?>
        <h1>welcome !</h1>
        <p>it looks like you're not logged in yet!</p>
        <div>
            <a href="app/client/register.php">register</a>
            |
            <a href="app/client/login.php">login</a>
        </div>
        <?php
    } else {
        ?>
        <p>it looks like you're already logged in, good job!</p>
        <p>you can visit your profile details <a href="app/client/profile.php">here</a>.</p>
        <?php
    }
} else {
    echo 'Something went wrong.';
    exit();
}
?>
</div>
<div class="credit">
    <p>this website was written by <a href="https://github.com/alperen-dev" target="_blank">Alperen Yilmaz</a> <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" width="1%" height="1%"> for school purposes</p>
    <a href="app/client/impressum.php">Impressum</a>
</div>
</body>
</html>
