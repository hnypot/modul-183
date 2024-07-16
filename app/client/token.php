<?php
session_start();
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['id'])) {
    header('Location: ../../index.php?msg=already_logged_in');
    exit();
}
if (!isset($_SESSION['token'])) {
    header('Location: ../../index.php?msg=invalid_registration');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../assets/css/app.css" type="text/css">
    <title>token</title>
</head>
<body>
<div class="authTitle">
    <h1>verify your identity</h1>
    <h2>you have received a token via email, check your inbox and your spam/junk folder!</h2>
</div>
<div class="form-container">
    <form method="post" action="../server/register_process.php" class="form">
        <input type="text" name="token" id="token" class="input" placeholder="token">
        <button type="submit" name="tokenBtn" class="btn">verify</button>
    </form>
</div>
</body>
</html>