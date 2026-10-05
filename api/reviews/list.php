<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$listingId = filter_var($_GET['listing_id'] ?? null, FILTER_VALIDATE_INT);
if ($listingId === false || $listingId === null || $listingId < 1) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid listing ID is required.']);
    exit;
}

$listing = $pdo->prepare("SELECT seller_id FROM listings WHERE id = ? AND status IN ('approved', 'sold')");
$listing->execute([$listingId]);
$sellerId = $listing->fetchColumn();
if (!$sellerId) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Listing not found.']);
    exit;
}

$st = $pdo->prepare('SELECT r.rating, r.comment, r.created_at, u.full_name AS reviewer_name
                     FROM reviews r INNER JOIN users u ON u.id = r.reviewer_id
                     WHERE r.seller_id = ?
                     ORDER BY r.created_at DESC LIMIT 50');
$st->execute([$sellerId]);
$reviews = $st->fetchAll();
$avg = $pdo->prepare('SELECT AVG(rating), COUNT(*) FROM reviews WHERE seller_id = ?');
$avg->execute([$sellerId]);
$summary = $avg->fetch(PDO::FETCH_NUM);
echo json_encode([
    'success' => true,
    'average' => round((float) ($summary[0] ?: 0), 1),
    'count' => (int) $summary[1],
    'reviews' => $reviews,
], JSON_UNESCAPED_UNICODE);
