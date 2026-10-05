<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}
$id = (int) ($_POST['id'] ?? 0);
$st = $pdo->prepare("UPDATE listings SET status = 'sold' WHERE id = ? AND seller_id = ? AND status = 'approved'");
$st->execute([$id, $user['id']]);
if ($st->rowCount() === 0) {
    json_out(['success' => false, 'message' => 'Only your approved listings can be marked as sold.'], 422);
}
$pending = $pdo->prepare("UPDATE purchase_requests SET status = 'rejected' WHERE listing_id = ? AND status = 'pending'");
$pending->execute([$id]);
$pdo->prepare('DELETE FROM wishlists WHERE listing_id = ?')->execute([$id]);
json_out(['success' => true, 'message' => 'Marked as sold.']);
