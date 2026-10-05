<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$id = filter_var($_POST['conversation_id'] ?? null, FILTER_VALIDATE_INT);
$body = trim((string) ($_POST['body'] ?? ''));
if ($id === false || $id === null || $id < 1 || $body === '' || mb_strlen($body) > 2000) {
    json_out(['success' => false, 'message' => 'Enter a message up to 2,000 characters.'], 422);
}

$check = $pdo->prepare('SELECT id FROM conversations WHERE id = ? AND (buyer_id = ? OR seller_id = ?)');
$check->execute([$id, $user['id'], $user['id']]);
if (!$check->fetch()) {
    json_out(['success' => false, 'message' => 'Conversation not found.'], 404);
}

$insert = $pdo->prepare('INSERT INTO messages (conversation_id, sender_id, body) VALUES (?, ?, ?)');
$insert->execute([$id, $user['id'], $body]);
json_out(['success' => true, 'message_id' => (int) $pdo->lastInsertId()]);
