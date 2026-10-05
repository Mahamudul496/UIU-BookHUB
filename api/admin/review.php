<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$admin = require_admin();

$id     = (int) ($_POST['id'] ?? 0);
$action = (string) ($_POST['action'] ?? '');
$kind   = (string) ($_POST['kind'] ?? 'listing');
$reason = mb_substr(trim((string) ($_POST['reason'] ?? '')), 0, 255);

if ($id < 1 || !in_array($action, ['approve', 'reject'], true) || !in_array($kind, ['listing', 'donation'], true)) {
    json_out(['success' => false, 'message' => 'Invalid action.'], 422);
}
if ($action === 'reject' && $reason === '') {
    json_out(['success' => false, 'message' => 'Please give a reject reason.'], 422);
}

$status = $action === 'approve' ? 'approved' : 'rejected';
$table = $kind === 'donation' ? 'donations' : 'listings';
$st = $pdo->prepare("UPDATE {$table} SET status = ?, reject_reason = ?, reviewed_by = ?, reviewed_at = NOW()
                     WHERE id = ? AND status = 'pending'");
$st->execute([$status, $action === 'reject' ? $reason : null, $admin['id'], $id]);

if ($st->rowCount() === 0) {
    json_out(['success' => false, 'message' => 'Submission not found or already reviewed.'], 404);
}
json_out([
    'success' => true,
    'message' => $action === 'approve'
        ? ($kind === 'donation' ? 'Donation approved.' : 'Listing approved.')
        : ($kind === 'donation' ? 'Donation rejected.' : 'Listing rejected.'),
]);
