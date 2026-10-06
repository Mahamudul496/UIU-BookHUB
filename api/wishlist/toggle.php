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

$listing = $pdo->prepare("SELECT id FROM listings WHERE id = ? AND status = 'approved'");
$listing->execute([$id]);
if (!$listing->fetch()) {
    json_out(['success' => false, 'message' => 'This listing is no longer available.'], 404);
}

$exists = $pdo->prepare('SELECT 1 FROM wishlists WHERE user_id = ? AND listing_id = ?');
$exists->execute([$user['id'], $id]);
if ($exists->fetchColumn()) {
    $st = $pdo->prepare('DELETE FROM wishlists WHERE user_id = ? AND listing_id = ?');
    $st->execute([$user['id'], $id]);
    json_out(['success' => true, 'saved' => false, 'message' => 'Removed from wishlist.']);
}

$st = $pdo->prepare('INSERT IGNORE INTO wishlists (user_id, listing_id) VALUES (?, ?)');
$st->execute([$user['id'], $id]);
json_out(['success' => true, 'saved' => true, 'message' => 'Added to wishlist.']);
