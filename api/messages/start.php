<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$listingId = filter_var($_POST['listing_id'] ?? null, FILTER_VALIDATE_INT);
if ($listingId === false || $listingId === null || $listingId < 1) {
    json_out(['success' => false, 'message' => 'A valid listing ID is required.'], 422);
}

$listingSt = $pdo->prepare("SELECT l.id, l.seller_id
                            FROM listings l
                            WHERE l.id = ?
                              AND (l.status = 'approved'
                                   OR (l.status = 'sold' AND EXISTS (
                                       SELECT 1 FROM purchase_requests pr
                                       WHERE pr.listing_id = l.id AND pr.status = 'accepted'
                                         AND pr.buyer_id = ?
                                   )))");
$listingSt->execute([$listingId, $user['id']]);
$listing = $listingSt->fetch();
if (!$listing) {
    json_out(['success' => false, 'message' => 'This listing is unavailable for chat.'], 404);
}

$sellerId = (int) $listing['seller_id'];
if ($sellerId === (int) $user['id']) {
    json_out(['success' => false, 'message' => 'You cannot start a chat with yourself.'], 422);
}

$buyerId = (int) $user['id'];
$existing = $pdo->prepare('SELECT id FROM conversations WHERE listing_id = ? AND buyer_id = ?');
$existing->execute([$listingId, $buyerId]);
$conversationId = $existing->fetchColumn();
if (!$conversationId) {
    $insert = $pdo->prepare('INSERT IGNORE INTO conversations (listing_id, buyer_id, seller_id) VALUES (?, ?, ?)');
    $insert->execute([$listingId, $buyerId, $sellerId]);
    $existing->execute([$listingId, $buyerId]);
    $conversationId = $existing->fetchColumn();
}

json_out(['success' => true, 'conversation_id' => (int) $conversationId]);
