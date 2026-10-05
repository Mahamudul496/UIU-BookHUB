<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'A valid donation ID is required.']);
    exit;
}

$statement = $pdo->prepare(
    "SELECT id, donor_identity, title, course_code, department, subject, item_type,
            condition_status, edition_author, description, image_url, created_at
     FROM donations WHERE id = ? AND status = 'approved'"
);
$statement->execute([$id]);
$donation = $statement->fetch();
if (!$donation) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Donation not found or unavailable.']);
    exit;
}

$donation['seller_name'] = $donation['donor_identity'] ?: 'Anonymous';
echo json_encode(['success' => true, 'donation' => $donation], JSON_UNESCAPED_UNICODE);
