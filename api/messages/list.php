<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$st = $pdo->prepare("SELECT c.id AS conversation_id, c.listing_id, l.title AS listing_title,
                            CASE WHEN c.buyer_id = :buyer_match THEN seller.id ELSE buyer.id END AS peer_id,
                                   CASE WHEN c.buyer_id = :name_match THEN seller.full_name ELSE buyer.full_name END AS peer_name,
                            latest.body AS last_message, latest.created_at AS last_message_at
                     FROM conversations c
                     INNER JOIN listings l ON l.id = c.listing_id
                     INNER JOIN users buyer ON buyer.id = c.buyer_id
                     INNER JOIN users seller ON seller.id = c.seller_id
                     LEFT JOIN messages latest ON latest.id = (
                         SELECT m.id FROM messages m
                         WHERE m.conversation_id = c.id
                         ORDER BY m.created_at DESC, m.id DESC LIMIT 1
                     )
                     WHERE c.buyer_id = :buyer_id OR c.seller_id = :seller_id
                     ORDER BY COALESCE(latest.created_at, c.created_at) DESC");
$st->execute([
    'buyer_match' => $user['id'],
    'name_match' => $user['id'],
    'buyer_id' => $user['id'],
    'seller_id' => $user['id'],
]);
json_out(['success' => true, 'conversations' => $st->fetchAll()]);
