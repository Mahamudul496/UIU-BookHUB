<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');
$user = require_login();
if ($user['role'] !== 'student') {
    json_out(['success' => false, 'message' => 'Student access only.'], 403);
}

foreach (['title', 'course_code', 'department', 'condition_status', 'price'] as $field) {
    if (trim((string) ($_POST[$field] ?? '')) === '') {
        json_out(['success' => false, 'message' => "The {$field} field is required."], 422);
    }
}

$price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
if ($price === false || $price < 0 || $price > 100000) {
    json_out(['success' => false, 'message' => 'Price must be a valid number.'], 422);
}

$data = [
    'seller_id'        => $user['id'],
    'title'            => mb_substr(trim((string) $_POST['title']), 0, 180),
    'course_code'      => mb_substr(trim((string) $_POST['course_code']), 0, 30),
    'department'       => mb_substr(trim((string) $_POST['department']), 0, 80),
    'subject'          => mb_substr(trim((string) ($_POST['subject'] ?? '')), 0, 120) ?: null,
    'item_type'        => trim((string) ($_POST['item_type'] ?? 'Textbook')),
    'condition_status' => trim((string) $_POST['condition_status']),
    'price'            => $price,
    'edition_author'   => mb_substr(trim((string) ($_POST['edition_author'] ?? '')), 0, 180) ?: null,
    'description'      => trim((string) ($_POST['description'] ?? '')) ?: null,
    'image_url'        => null,
];

if (!in_array($data['item_type'], ['Textbook', 'Lecture Notes', 'Lab Manual', 'Other Notes'], true)
    || !in_array($data['condition_status'], ['Like New', 'Good', 'Used'], true)) {
    json_out(['success' => false, 'message' => 'Invalid listing type or condition.'], 422);
}

// Optional photo upload
if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        json_out(['success' => false, 'message' => 'Photo must be under 2 MB.'], 422);
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if ($ext === null) {
        json_out(['success' => false, 'message' => 'Only JPG, PNG or WEBP photos are allowed.'], 422);
    }
    $dir = __DIR__ . '/../../uploads/books/';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $name = bin2hex(random_bytes(10)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
        json_out(['success' => false, 'message' => 'Could not save the photo.'], 500);
    }
    $data['image_url'] = 'uploads/books/' . $name;
}

$pdo->prepare(
    "INSERT INTO listings (seller_id, title, course_code, department, subject, item_type, condition_status, price, edition_author, description, image_url)
     VALUES (:seller_id, :title, :course_code, :department, :subject, :item_type, :condition_status, :price, :edition_author, :description, :image_url)"
)->execute($data);

json_out(['success' => true, 'message' => 'Listing submitted for admin approval.', 'listing_id' => (int) $pdo->lastInsertId()], 201);
