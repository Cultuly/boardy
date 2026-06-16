<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

require __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = $_POST['body'] ?? '';

    if (!$body) {
        $error = 'Введите текст сообщения';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO posts (title, body, author_id) VALUES (?, ?, ?)'
        );
        $stmt->execute(['Пост', $body, $_SESSION['user_id']]);

        header('Location: /messages.php');
        exit;
    }
}
?>

<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<main>
    <h1>Новый пост</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <textarea name="body" placeholder="Текст сообщения"></textarea>
        <button type="submit">Опубликовать</button>
    </form>
</main>

<?php include __DIR__ . '/partials/foot.php'; ?>
