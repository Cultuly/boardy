<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// 1. CSRF-проверка state
if (($_GET['state'] ?? '') !== ($_SESSION['oauth_state'] ?? '')) {
    die('Invalid state — possible CSRF attack');
}

$client_id     = 'Ov23lizitsl5nYYJ2xkD';        // тот же, что в oauth-github.php
$client_secret = 'ffee3fc13f04cd927287d285e104e5a01e683391';    // секрет из GitHub OAuth App
$code          = $_GET['code'] ?? null;

if (!$code) {
    die('No code received from GitHub');
}

// 2. Обмен code на access_token
$ch = curl_init('https://github.com/login/oauth/access_token');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'client_id'     => $client_id,
        'client_secret' => $client_secret,
        'code'          => $code,
    ]),
    CURLOPT_HTTPHEADER     => ['Accept: application/json'],
    CURLOPT_RETURNTRANSFER => true,
]);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($response['access_token'])) {
    die('Failed to get access_token: ' . json_encode($response));
}
$access_token = $response['access_token'];

// 3. Запрос профиля GitHub
$ch = curl_init('https://api.github.com/user');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $access_token",
        'User-Agent: Boardy'
    ],
    CURLOPT_RETURNTRANSFER => true,
]);
$profile = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($profile['id'])) {
    die('Failed to get GitHub profile');
}

// 4. Поиск или создание пользователя в локальной БД
require __DIR__ . '/db.php';  // ваш файл с $pdo

$stmt = $pdo->prepare('SELECT id, name FROM users WHERE github_id = ?');
$stmt->execute([$profile['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $name = $profile['login'] ?? $profile['name'] ?? 'github_user';
    $email = $profile['email'] ?? '';
    $stmt = $pdo->prepare('INSERT INTO users (name, email, github_id, password_hash) VALUES (?, ?, ?, NULL)');
    $stmt->execute([$name, $email, $profile['id']]);
    $user = ['id' => $pdo->lastInsertId(), 'name' => $name];
}

// 5. Создаём сессию
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
header('Location: /messages.php');
exit;
