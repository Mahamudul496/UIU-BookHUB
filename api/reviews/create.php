<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$requestId = filter_var($_POST['request_id'] ?? null, FILTER_VALIDATE_INT);
$rating = filter_var($_POST['rating'] ?? null, FILTER_VALIDATE_INT);
$comment = trim((string) ($_POST['comment'] ?? ''));
if ($requestId === false || $requestId === null || $requestId < 1 || $rating === false || $rating === null || $rating < 1 || $rating > 5) {
    json_out(['success' => false, 'message' => 'Choose a rating from 1 to 5.'], 422);
}
if (mb_strlen($comment) > 1000) {
    json_out(['success' => false, 'message' => 'Review must be 1,000 characters or fewer.'], 422);
}

$requestSt = $pdo->prepare("SELECT pr.id, pr.listing_id, l.seller_id
                            FROM purchase_requests pr
                            INNER JOIN listings l ON l.id = pr.listing_id
                            WHERE pr.id = ? AND pr.buyer_id = ? AND pr.status = 'accepted'");
$requestSt->execute([$requestId, $user['id']]);
$request = $requestSt->fetch();
if (!$request) {
    json_out(['success' => false, 'message' => 'Only accepted purchase requests can be reviewed.'], 403);
}

$existing = $pdo->prepare('SELECT id FROM reviews WHERE purchase_request_id = ?');
$existing->execute([$requestId]);
if ($existing->fetchColumn()) {
    json_out(['success' => false, 'message' => 'You have already reviewed this purchase.'], 409);
}

try {
    $insert = $pdo->prepare('INSERT INTO reviews (purchase_request_id, listing_id, reviewer_id, seller_id, rating, comment)
                             VALUES (?, ?, ?, ?, ?, ?)');
    $insert->execute([$requestId, $request['listing_id'], $user['id'], $request['seller_id'], $rating, $comment ?: null]);
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000' && (int) ($exception->errorInfo[1] ?? 0) === 1062) {
        json_out(['success' => false, 'message' => 'You have already reviewed this purchase.'], 409);
    }
    throw $exception;
}

json_out(['success' => true, 'message' => 'Thank you for reviewing the seller.'], 201);
