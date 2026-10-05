<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$id = filter_var($_POST['listing_id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    json_out(['success' => false, 'message' => 'A valid listing ID is required.'], 422);
}

try {
    $pdo->beginTransaction();
    $listing = $pdo->prepare("SELECT seller_id FROM listings WHERE id = ? AND status = 'approved' FOR UPDATE");
    $listing->execute([$id]);
    $row = $listing->fetch();
    if (!$row) {
        $pdo->rollBack();
        json_out(['success' => false, 'message' => 'This listing is no longer available.'], 404);
    }
    if ((int) $row['seller_id'] === (int) $user['id']) {
        $pdo->rollBack();
        json_out(['success' => false, 'message' => 'You cannot request to buy your own listing.'], 422);
    }

    $existing = $pdo->prepare("SELECT id, status FROM purchase_requests
                               WHERE listing_id = ? AND buyer_id = ? AND status IN ('pending', 'accepted')
                               LIMIT 1 FOR UPDATE");
    $existing->execute([$id, $user['id']]);
    if ($existing->fetch()) {
        $pdo->rollBack();
        json_out(['success' => false, 'message' => 'You already have an active request for this item.'], 409);
    }

    $insert = $pdo->prepare("INSERT INTO purchase_requests (listing_id, buyer_id, status) VALUES (?, ?, 'pending')");
    $insert->execute([$id, $user['id']]);
    $pdo->commit();
    json_out(['success' => true, 'message' => 'Buy request sent to the seller.']);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $exception;
}
