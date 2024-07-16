<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == 'error.php'){
    header('Location: ../../index.php?msg=access_denied');
    exit();
}
$error = array(
    'invalid_username' => 'Error: Invalid username! Please only use a maximum of 25 characters which can ONLY contain: letters, numbers, underscores, hyphen. Username must not end or start with either an underscore or hyphen!',
    'invalid_email' => 'Error: Invalid email!',
    'email_exists' => 'Error: Email already exists!',
    'invalid_password' => 'Error: Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
    'username_exists' => 'Error: Username already exists!',
    'unexpected_error' => 'Error: An unexpected error occurred. Please try again.',
    'wrong_token' => 'Error: Wrong token! Try to register again with the same email. Please also check your spam/junk folder if you cant find your token inside your inbox!',
    'invalid_input' => 'Error: Username or password are invalid!',
    'not_logged_in' => 'Error: Not logged in yet! Do so here.',
    'already_logged_in' => 'Error: Already logged in!',
    'invalid_registration' => 'Error: Wrong approach. Please try to register again. You can access the page below.',
    'access_denied' => 'Error: Access denied!',
    'successful_registration' => 'Success: You have been successfully registered! You can now sign in.',
    'successful_login' => 'Success: You have been successfully logged in! Enjoy.',
    'successful_logout' => 'Success: You have been successfully logged out! Bye.'
);