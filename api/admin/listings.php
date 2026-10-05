<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_admin();
$st = $pdo->query("SELECT l.id, l.title, l.course_code, l.department, l.item_type, l.condition_status, l.price,
                          l.description, l.image_url, l.status, l.reject_reason, l.created_at, u.full_name AS seller_name, u.student_id
                   FROM listings l INNER JOIN users u ON u.id = l.seller_id
                   ORDER BY l.created_at DESC");
$all = $st->fetchAll();
$pending = array_values(array_filter($all, fn ($r) => $r['status'] === 'pending'));

$donations = $pdo->query("SELECT id, title, course_code, department, item_type, condition_status, 0 AS price,
                                 description, image_url, status, reject_reason, donor_identity AS seller_name,
                                 created_at, 1 AS is_donation
                          FROM donations ORDER BY created_at DESC")->fetchAll();
$pendingDonations = array_values(array_filter($donations, fn ($r) => $r['status'] === 'pending'));
json_out([
    'success' => true,
    'pending_count' => count($pending) + count($pendingDonations),
    'pending' => $pending,
    'pending_donations' => $pendingDonations,
    'all' => $all,
    'donations' => $donations,
]);
