<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid listing ID is required.']);
    exit;
}

$st = $pdo->prepare("SELECT l.id, l.title, l.course_code, l.department, l.subject, l.item_type,
                            l.condition_status, l.price, l.edition_author, l.description, l.image_url,
                            l.status AS listing_status, l.seller_id, u.full_name AS seller_name,
                            COALESCE(sr.rating_average, 0) AS seller_rating,
                            COALESCE(sr.rating_count, 0) AS seller_rating_count
                     FROM listings l INNER JOIN users u ON u.id = l.seller_id
                     LEFT JOIN (
                         SELECT seller_id, AVG(rating) AS rating_average, COUNT(*) AS rating_count
                         FROM reviews GROUP BY seller_id
                     ) sr ON sr.seller_id = l.seller_id
                     WHERE l.id = ?
                       AND (l.status = 'approved'
                            OR (l.status = 'sold' AND EXISTS (
                                SELECT 1 FROM purchase_requests pr
                                WHERE pr.listing_id = l.id AND pr.status = 'accepted'
                                  AND (pr.buyer_id = ? OR l.seller_id = ?)
                            )))");
$viewerId = (int) ($_SESSION['user_id'] ?? 0);
$st->execute([$id, $viewerId, $viewerId]);
$listing = $st->fetch();

if (!$listing) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Listing not found or unavailable.']);
    exit;
}

echo json_encode(['success' => true, 'listing' => $listing], JSON_UNESCAPED_UNICODE);