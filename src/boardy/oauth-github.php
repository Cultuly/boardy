<?php
session_start();

$client_id = 'Ov23lizitsl5nYYJ2xkD';  // заменить
$redirect_uri = 'https://boardy.cultuly.ai-info.ru/oauth-callback.php';

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$params = http_build_query([
    'client_id'    => $client_id,
    'redirect_uri' => $redirect_uri,
    'scope'        => 'read:user',
    'state'        => $state,
]);

header("Location: https://github.com/login/oauth/authorize?$params");
exit;
