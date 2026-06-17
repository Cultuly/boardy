<?php
// Endpoint для React: возвращает JWT по активной PHP-сессии
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$secret_key = 'boardy-secret-key';

$header = rtrim(strtr(base64_encode(json_encode([
    'alg' => 'HS256', 'typ' => 'JWT'
])), '+/', '-_'), '=');

$payload = rtrim(strtr(base64_encode(json_encode([
    'user_id' => $_SESSION['user_id'],
    'name'    => $_SESSION['user_name'] ?? 'User',
    'exp'     => time() + 3600       // 1 час
])), '+/', '-_'), '=');

$signature = rtrim(strtr(base64_encode(
    hash_hmac('sha256', "$header.$payload", $secret_key, true)
), '+/', '-_'), '=');

$jwt = "$header.$payload.$signature";

echo json_encode(['token' => $jwt]);
