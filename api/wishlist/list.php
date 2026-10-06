<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$st = $pdo->prepare("SELECT l.id, l.title, l.course_code, l.department, l.subject, l.item_type,
                            l.condition_status, l.price, l.image_url, u.full_name AS seller_name,
                            COALESCE(sr.rating_average, 0) AS seller_rating,
                            COALESCE(sr.rating_count, 0) AS seller_rating_count
                     FROM wishlists w
                     INNER JOIN listings l ON l.id = w.listing_id
                     INNER JOIN users u ON u.id = l.seller_id
                     LEFT JOIN (
                         SELECT seller_id, AVG(rating) AS rating_average, COUNT(*) AS rating_count
                         FROM reviews GROUP BY seller_id
                     ) sr ON sr.seller_id = l.seller_id
                     WHERE w.user_id = ? AND l.status = 'approved'
                     ORDER BY w.created_at DESC");
$st->execute([$user['id']]);
$listings = $st->fetchAll();
json_out(['success' => true, 'listings' => $listings, 'ids' => array_column($listings, 'id')]);
