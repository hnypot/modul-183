<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == 'csrf.php'){
    header('Location: ../../index.php?msg=access_denied');
    exit();
}
function generate_csrf_token(): string {
    if (!isset($_SESSION['csrf_token'])) {
        try {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } catch (Exception $e) {
            echo 'Something went wrong. ERR_INFO: ' . $e->getMessage();
            exit();
        }
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token(): bool {
    if (isset($_POST['csrf_token']) && isset($_SESSION['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        return true;
    }
    return false;
}