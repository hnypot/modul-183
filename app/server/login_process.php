<?php
session_start();
require_once 'db.php';
require_once('../server/csrf.php');

if (!validate_csrf_token()) {
    header('Location: ../client/login.php?msg=csrf_validation_failed');
}

/*
 * why check if the user is already logged in?
 * obviously, and yet again, we don't want the user to register
 * themselves again if they're already logged in since there is
 * no reason to do so, but also we can, in some way, prevent
 * problems and vulnerabilities regarding session (hijacking) by doing this
 */
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['id'])) {
    header('Location: ../../index.php?msg=already_logged_in');
    exit();
}

/*
 * input-validation
 * to make sure that the user accessed the register_process
 * only via a button (signupBtn (or tokenBtn, see elseif))
 */
if (!isset($_POST['signinBtn'])) {
    header('Location: ../client/login.php?msg=unexpected_error');
    exit();
}

$uname = $_POST['username'];
$pwd = $_POST['password'];

$stmt = mysqli_prepare($conn, "SELECT id, username, email, password FROM users WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $uname);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $uname_result, $email_result, $pwd_result);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!password_verify($pwd, $pwd_result) || $uname !== $uname_result) {
    header('Location: ../client/login.php?msg=invalid_input');
    exit();
}

session_regenerate_id(true);

$_SESSION['id'] = $id;
$_SESSION['email'] = $email_result;
$_SESSION['username'] = $uname_result;

header("Location: ../client/profile.php?msg=successful_login");
exit();