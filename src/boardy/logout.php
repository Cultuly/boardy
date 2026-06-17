<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

session_destroy();

setcookie('PHPSESSID', '', time() - 3600, '/');

header('Location: /messages.php');
exit;
