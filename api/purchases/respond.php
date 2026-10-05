<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

$id = filter_var($_POST['request_id'] ?? null, FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';
if ($id === false || $id === null || $id < 1 || !in_array($action, ['accept', 'reject'], true)) {
    json_out(['success' => false, 'message' => 'A valid request and action are required.'], 422);
}

try {
    $pdo->beginTransaction();
    $st = $pdo->prepare("SELECT pr.id, pr.listing_id, l.status AS listing_status
                         FROM purchase_requests pr
                         INNER JOIN listings l ON l.id = pr.listing_id
                         WHERE pr.id = ? AND l.seller_id = ? AND pr.status = 'pending'
                         FOR UPDATE");
    $st->execute([$id, $user['id']]);
    $request = $st->fetch();
    if (!$request) {
        $pdo->rollBack();
        json_out(['success' => false, 'message' => 'This buy request is no longer available.'], 404);
    }

    if ($action === 'accept') {
        if ($request['listing_status'] !== 'approved') {
            $pdo->rollBack();
            json_out(['success' => false, 'message' => 'This listing is no longer available.'], 409);
        }
        $updateListing = $pdo->prepare("UPDATE listings SET status = 'sold' WHERE id = ? AND seller_id = ? AND status = 'approved'");
        $updateListing->execute([$request['listing_id'], $user['id']]);
        if ($updateListing->rowCount() !== 1) {
            $pdo->rollBack();
            json_out(['success' => false, 'message' => 'This listing is no longer available.'], 409);
        }
        $updateRequest = $pdo->prepare("UPDATE purchase_requests SET status = 'accepted' WHERE id = ? AND status = 'pending'");
        $updateRequest->execute([$id]);
        $rejectOthers = $pdo->prepare("UPDATE purchase_requests SET status = 'rejected'
                                       WHERE listing_id = ? AND id <> ? AND status = 'pending'");
        $rejectOthers->execute([$request['listing_id'], $id]);
        $removeFromWishlists = $pdo->prepare('DELETE FROM wishlists WHERE listing_id = ?');
        $removeFromWishlists->execute([$request['listing_id']]);
        $message = 'Request accepted. The listing is now marked as sold.';
    } else {
        $updateRequest = $pdo->prepare("UPDATE purchase_requests SET status = 'rejected' WHERE id = ? AND status = 'pending'");
        $updateRequest->execute([$id]);
        $message = 'Buy request declined.';
    }

    $pdo->commit();
    json_out(['success' => true, 'message' => $message]);
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    throw $exception;
}
