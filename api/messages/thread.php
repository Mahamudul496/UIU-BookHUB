<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$id = filter_var($_GET['conversation_id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    json_out(['success' => false, 'message' => 'A valid conversation ID is required.'], 422);
}

$conversationSt = $pdo->prepare("SELECT c.id, c.listing_id, l.title AS listing_title,
                                        CASE WHEN c.buyer_id = ? THEN seller.full_name ELSE buyer.full_name END AS peer_name,
                                        CASE WHEN c.buyer_id = ? THEN seller.id ELSE buyer.id END AS peer_id
                                 FROM conversations c
                                 INNER JOIN listings l ON l.id = c.listing_id
                                 INNER JOIN users buyer ON buyer.id = c.buyer_id
                                 INNER JOIN users seller ON seller.id = c.seller_id
                                 WHERE c.id = ? AND (c.buyer_id = ? OR c.seller_id = ?)");
$conversationSt->execute([$user['id'], $user['id'], $id, $user['id'], $user['id']]);
$conversation = $conversationSt->fetch();
if (!$conversation) {
    json_out(['success' => false, 'message' => 'Conversation not found.'], 404);
}

$messagesSt = $pdo->prepare('SELECT m.id, m.sender_id, m.body, m.created_at, u.full_name AS sender_name
                             FROM messages m INNER JOIN users u ON u.id = m.sender_id
                             WHERE m.conversation_id = ?
                             ORDER BY m.created_at ASC, m.id ASC
                             LIMIT 300');
$messagesSt->execute([$id]);
json_out([
    'success' => true,
    'conversation' => $conversation,
    'messages' => $messagesSt->fetchAll(),
    'current_user_id' => (int) $user['id'],
]);
