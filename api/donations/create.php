<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');

foreach (['title', 'course_code', 'department', 'condition_status'] as $field) {
    if (trim((string) ($_POST[$field] ?? '')) === '') {
        json_out(['success' => false, 'message' => "The {$field} field is required."], 422);
    }
}

if (trim((string) ($_POST['website'] ?? '')) !== '') {
    json_out(['success' => false, 'message' => 'The donation could not be submitted.'], 422);
}

$itemType = trim((string) ($_POST['item_type'] ?? 'Textbook'));
$condition = trim((string) $_POST['condition_status']);
if (!in_array($itemType, ['Textbook', 'Lecture Notes', 'Lab Manual', 'Other Notes'], true)
    || !in_array($condition, ['Like New', 'Good', 'Used'], true)) {
    json_out(['success' => false, 'message' => 'Invalid item type or condition.'], 422);
}

$imageUrl = null;
if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        json_out(['success' => false, 'message' => 'Photo must be under 2 MB.'], 422);
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if ($extension === null) {
        json_out(['success' => false, 'message' => 'Only JPG, PNG or WEBP photos are allowed.'], 422);
    }
    $directory = __DIR__ . '/../../uploads/books/';
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        json_out(['success' => false, 'message' => 'Could not prepare photo storage.'], 500);
    }
    $filename = bin2hex(random_bytes(10)) . '.' . $extension;
    if (!move_uploaded_file($file['tmp_name'], $directory . $filename)) {
        json_out(['success' => false, 'message' => 'Could not save the photo.'], 500);
    }
    $imageUrl = 'uploads/books/' . $filename;
}

$statement = $pdo->prepare(
    'INSERT INTO donations
        (donor_identity, title, course_code, department, subject, item_type, condition_status, edition_author, description, image_url)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$statement->execute([
    mb_substr(trim((string) ($_POST['donor_identity'] ?? '')), 0, 120) ?: null,
    mb_substr(trim((string) $_POST['title']), 0, 180),
    mb_substr(trim((string) $_POST['course_code']), 0, 30),
    mb_substr(trim((string) $_POST['department']), 0, 80),
    mb_substr(trim((string) ($_POST['subject'] ?? '')), 0, 120) ?: null,
    $itemType,
    $condition,
    mb_substr(trim((string) ($_POST['edition_author'] ?? '')), 0, 180) ?: null,
    trim((string) ($_POST['description'] ?? '')) ?: null,
    $imageUrl,
]);

json_out([
    'success' => true,
    'message' => 'Donation submitted. It will appear in the marketplace after admin approval.',
    'donation_id' => (int) $pdo->lastInsertId(),
], 201);
