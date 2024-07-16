<?php
session_start();

require_once 'db.php';
require_once '../../config.php';
require_once('../server/csrf.php');

if (!validate_csrf_token()) {
    header('Location: ../client/register.php?msg=csrf_validation_failed');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

/*
 * why check if the user is already logged in?
 * obviously, and yet again, we don't want the user to register themselves again if they're already logged in
 * since there is no reason to do so. doing so prevents problems and vulnerabilities, for example session hijacking
 */
if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['id'])) {
    header('Location: ../../index.php?msg=already_logged_in');
    exit();
}

/*
 * why unset and destroy the session id's?
 * because we don't want the user to have access to all the session tokens etc. we have set earlier.
 * And to prevent session hijacking, we also regenerate the session id frequently
 */
function deleteSession(): void {
    session_unset();
    $_SESSION = array();
    session_destroy();
    session_regenerate_id(true);
}

/*
 * input-validation to make sure that the user accessed the register_process only via a button
 * (signupBtn (or tokenBtn, see elseif))
 */
if (isset($_POST['signupBtn'])) {
  $uname = trim($_POST['username']);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $pwd = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

    /*
     * username-regex 
     * this will validate the user's username
     * why? because we don't want to overcomplicate the login process
     * the user shouldn't let's say try to log in with weird, non-utf8 characters. also for simplicity purposes
     */
    if (!preg_match("/^[a-z\d_]{2,23}$/", $uname)) {
        deleteSession();
        header("Location: ../client/register.php?msg=invalid_username");
        exit();
    }
    
    /*
     * Email-Regex
     * Must:
     * be validated by php, have >1 and <254 characters, be a valid email (see https://en.wikipedia.org/wiki/Email_address)
     * why? obviously, we don't want the user to use an invalid email and therefore,
     * we require the user to use an *actual* email
     */
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) < 1 || strlen($email) > 254
        || !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i', $email)) {
        deleteSession();
        header('Location: ../client/register.php?msg=invalid_email');
        exit();
    }

    /*
     * Password-Regex
     * Validating the password (input) and confirm_passowrd (input) 
     * Must:
     * >8 chars, capitalized, at least 1 number and
     * at least 1 special character (!?-_><~) etc.
     * why? because we want to ensure and maximize security in our program
     * and this approach is, especially nowadays, way better and way more
     * secure than using a common password list because we spare memory, storage
     * and even gain a (tiny) bit of performance
     */
    if ($pwd !== $confirm_password || strlen($pwd) < 8
        || !preg_match("/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)[0-9a-zA-Z\W]{8,}$/", $pwd)) {
        deleteSession();
        header("Location: ../client/register.php?msg=invalid_password");
        exit();
    }

    /*
     * why use prepared statements?
     * using mysqli prepared statements helps to prevent SQL injection attacks, makes code more secure,
     * and allows for efficient reuse of queries.
     */
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM users WHERE email = ? UNION SELECT COUNT(*) FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $uname);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $emailCount);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_next_result($stmt);
    mysqli_stmt_bind_result($stmt, $unameCount);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    /*
     * why do we check this?
     * obviously we don't want multiple users having the same email, and it is important to do this
     * to prevent duplicate user entries and maintain data uniqueness and data integrity.
     */
    if ($emailCount > 0) {
        deleteSession();
        header("Location: ../client/register.php?msg=email_exists");
        exit();
    }

    /*
     * why do we check this?
     * the same reason as above AND;
     * this is also especially important because of the login process which is only possible
     * with the username and password of a user. therefore, we check if there is someone who has that exact username
     */
    if ($unameCount > 0) {
        deleteSession();
        header("Location: ../client/register.php?msg=username_exists");
        exit();
    }

    session_regenerate_id(true); // why? because, if all the regex and validation went right,
                                                 // regenerate the session id again to prevent session hijacking

    /*
     * why pass the username, email and password into the session?
     * this way, passing variables over will ease the whole process. also, session hijacking
     * won't be an issue when HTTPS is used and when session IDs are being regenerated regularly
     * (which is already done so).
     */
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    /*
     * Password-Hashing
     * Basic measure for all passwords and tokens nowadays
     * why? we want to ensure (and in this case, enforce) security for our users, which also is why blank passwords
     * will NEVER be inserted into a database NOR should be stored non-hashed anywhere else.
     */
    $_SESSION['pwd'] = password_hash($pwd, PASSWORD_BCRYPT);

    $uname_sh = $_SESSION['username'];
    $email_sh = $_SESSION['email'];
    $pwd_sh = $_SESSION['pwd'];

    /*
     * this token is important for security reasons because it helps prevent cross-site request forgery (CSRF) attacks
     * in this specific case, the generated token is used to verify the user's email address
     */
    try {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    } catch (\Exception $e) {
        deleteSession();
        echo 'An error occurred while generating the token. ERR_INFO: ' . $e->getMessage();
        exit();
    }

    //starting instance of PHPMailer, following with the try/catch for the mail verification
    $mail = new PHPMailer(true);
    try {
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // use only if something doesn't work after pressing signupBtn
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Sender and recipient of the mail
        $mail->setFrom(SMTP_ADDRESS, SMTP_NAME);
        $mail->addAddress($email);

        //Content of the mail
        $mail->isHTML(true); // why html? because of rich formatting, better branding, better tracking, ... 
        $mail->Subject = 'Your Token';
        $mail->Body = 'Hello ' . $email . ' ! Your token to verify your email is: <b>' . $_SESSION['token'] . '</b>';
        /*
         * Why use an "AltBody"? because, some (ancient) email providers do NOT support html
         * such as mutt or Eudora, and to ease this process we also send the mail with plain-text characters
         */
        $mail->AltBody = 'Hello ' . $email . ' ! Your token to verify your email is: ' . $_SESSION['token'];
        $mail->send();
        header('Location: ../client/token.php');
    } catch (Exception $e) {
        echo "Message could not be sent. ERR_INFO: {$mail->ErrorInfo}";
        exit();
    }
} elseif (isset($_POST['tokenBtn'])) {
    $uname = $_SESSION['username'];
    $email = $_SESSION['email'];
    $pwd_hash = $_SESSION['pwd'];
    $token = $_SESSION['token'];
    if ($_POST['token'] !== $token) {
        deleteSession();
        header('Location: ../client/register.php?msg=wrong_token');
        exit();
    } 

    $verified = 1;

    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, is_email_verified) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $uname, $email, $pwd_hash, $verified);
    mysqli_stmt_execute($stmt);

    /*
     * this statement checks if exactly one row was affected by the previous MySQL operation
     * it is important to use this to ensure that the query performed as expected
     * and avoid unintended updates or deletions.
     */
    if (mysqli_affected_rows($conn) !== 1) {
        header('Location: ../client/register.php?msg=unexpected_error');
        exit();
    }
    deleteSession();
    header('Location: ../client/login.php?msg=successful_registration');
    exit();
} else {
    deleteSession();
    header('Location: ../client/register.php?msg=unexpected_error');
    exit();
}