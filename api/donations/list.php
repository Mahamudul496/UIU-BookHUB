<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$search = trim((string) ($_GET['search'] ?? ''));
$department = trim((string) ($_GET['department'] ?? ''));
$course = trim((string) ($_GET['course'] ?? ''));
$conditions = array_values(array_filter((array) ($_GET['condition'] ?? []), 'is_string'));
$types = array_values(array_filter((array) ($_GET['type'] ?? []), 'is_string'));

$sql = "SELECT id, donor_identity, title, course_code, department, subject, item_type,
               condition_status, edition_author, description, image_url, created_at
        FROM donations WHERE status = 'approved'";
$params = [];
if ($search !== '') {
    $sql .= ' AND (title LIKE :search_title OR course_code LIKE :search_course OR subject LIKE :search_subject)';
    $params += [
        'search_title' => "%{$search}%",
        'search_course' => "%{$search}%",
        'search_subject' => "%{$search}%",
    ];
}
if ($department !== '' && $department !== 'All Departments') {
    $sql .= ' AND department = :department';
    $params['department'] = $department;
}
if ($course !== '') {
    $sql .= ' AND course_code LIKE :course';
    $params['course'] = "%{$course}%";
}
foreach ([['condition_status', $conditions, 'condition'], ['item_type', $types, 'type']] as [$column, $values, $prefix]) {
    if (!$values) {
        continue;
    }
    $placeholders = [];
    foreach ($values as $index => $value) {
        $placeholder = "{$prefix}_{$index}";
        $placeholders[] = ':' . $placeholder;
        $params[$placeholder] = $value;
    }
    $sql .= ' AND ' . $column . ' IN (' . implode(', ', $placeholders) . ')';
}

$statement = $pdo->prepare($sql . ' ORDER BY created_at DESC');
$statement->execute($params);
$donations = array_map(static function (array $donation): array {
    return $donation + [
        'is_donation' => true,
        'seller_name' => $donation['donor_identity'] ?: 'Anonymous',
        'seller_rating' => 0,
        'seller_rating_count' => 0,
        'price' => 0,
        'listing_status' => 'donated',
    ];
}, $statement->fetchAll());

echo json_encode(['success' => true, 'count' => count($donations), 'donations' => $donations], JSON_UNESCAPED_UNICODE);
