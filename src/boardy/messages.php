<?php

	session_set_cookie_params([
		'lifetime' => 0,
		'path' => '/',
		'secure' => true,
		'httponly' => true
	]);

	session_start();

	require_once 'db.php';

	$stmt = $pdo->query(
		'SELECT posts.body, users.name, posts.created_at, posts.body
		FROM posts
		JOIN users ON posts.author_id = users.id
		ORDER BY posts.created_at DESC'
	);

	$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include __DIR__ . '/partials/head.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>

<main>

	<h2>Все сообщения</h2>

	<?php if (empty($messages)): ?>

		<p>Сообщений пока нет.</p>

	<?php else: ?>

		<table border="1" cellpadding="8" style="border-collapse:collapse;width:100%">
			<tr>
				<th>Дата</th>
				<th>Автор</th>
				<th>Сообщение</th>
			</tr>

			<?php foreach ($messages as $msg): ?>

				<tr>
					<td><?= htmlspecialchars($msg['created_at']) ?></td>
					<td><?= htmlspecialchars($msg['name']) ?></td>
					<td><?= htmlspecialchars($msg['body']) ?></td>
				</tr>

			<?php endforeach; ?>

		</table>

	<?php endif; ?>

	<p style="margin-top:20px">
		<a href="/submit.php">Написать</a> |
		<a href="/">На главную</a>
	</p>

</main>

</body>
</html>
