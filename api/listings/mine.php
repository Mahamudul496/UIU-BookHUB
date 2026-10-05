<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}
$st = $pdo->prepare('SELECT id, title, course_code, item_type, price, image_url, status, reject_reason, created_at
                     FROM listings WHERE seller_id = ? ORDER BY created_at DESC');
$st->execute([$user['id']]);
json_out(['success' => true, 'listings' => $st->fetchAll()]);
