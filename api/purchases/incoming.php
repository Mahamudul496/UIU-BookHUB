<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$st = $pdo->prepare("SELECT pr.id AS request_id, pr.status, pr.created_at,
                            l.id AS listing_id, l.title, l.course_code, l.price,
                            buyer.full_name AS buyer_name, buyer.student_id AS buyer_student_id
                     FROM purchase_requests pr
                     INNER JOIN listings l ON l.id = pr.listing_id
                     INNER JOIN users buyer ON buyer.id = pr.buyer_id
                     WHERE l.seller_id = ? AND pr.status = 'pending'
                     ORDER BY pr.created_at DESC");
$st->execute([$user['id']]);
json_out(['success' => true, 'requests' => $st->fetchAll()]);
