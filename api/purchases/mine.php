<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$st = $pdo->prepare("SELECT pr.id AS request_id, pr.status, pr.created_at, r.id AS review_id,
                            l.id AS listing_id, l.title, l.course_code, l.price, l.image_url,
                            seller.full_name AS seller_name
                     FROM purchase_requests pr
                     INNER JOIN listings l ON l.id = pr.listing_id
                     INNER JOIN users seller ON seller.id = l.seller_id
                     LEFT JOIN reviews r ON r.purchase_request_id = pr.id
                     WHERE pr.buyer_id = ?
                     ORDER BY pr.created_at DESC");
$st->execute([$user['id']]);
json_out(['success' => true, 'requests' => $st->fetchAll()]);
