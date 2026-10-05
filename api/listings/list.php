<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json; charset=utf-8');

$search     = trim((string) ($_GET['search'] ?? ''));
$department = trim((string) ($_GET['department'] ?? ''));
$course     = trim((string) ($_GET['course'] ?? ''));
$conditions = array_filter((array) ($_GET['condition'] ?? []), 'is_string');
$types      = array_filter((array) ($_GET['type'] ?? []), 'is_string');
$sort       = $_GET['sort'] ?? 'newest';

$orderBy = match ($sort) {
    'price_low'  => 'l.price ASC',
    'price_high' => 'l.price DESC',
    default      => 'l.created_at DESC',
};

$sql = "SELECT l.id, l.title, l.course_code, l.department, l.subject, l.item_type,
       l.condition_status, l.price, l.image_url, l.created_at, u.full_name AS seller_name,
               COALESCE(sr.rating_average, 0) AS seller_rating,
               COALESCE(sr.rating_count, 0) AS seller_rating_count
        FROM listings l INNER JOIN users u ON u.id = l.seller_id
        LEFT JOIN (
            SELECT seller_id, AVG(rating) AS rating_average, COUNT(*) AS rating_count
            FROM reviews GROUP BY seller_id
        ) sr ON sr.seller_id = l.seller_id
        WHERE l.status = 'approved'";
$params = [];

if ($search !== '') {
    $sql .= ' AND (l.title LIKE :s1 OR l.course_code LIKE :s2 OR l.subject LIKE :s3)';
    $params += ['s1' => "%{$search}%", 's2' => "%{$search}%", 's3' => "%{$search}%"];
}
if ($department !== '' && $department !== 'All Departments') {
    $sql .= ' AND l.department = :department';
    $params['department'] = $department;
}
if ($course !== '') {
    $sql .= ' AND l.course_code LIKE :course';
    $params['course'] = "%{$course}%";
}
foreach ([['condition_status', $conditions, 'c'], ['item_type', $types, 't']] as [$col, $vals, $p]) {
    $vals = array_values($vals);
    if ($vals) {
        $ph = [];
        foreach ($vals as $i => $v) { $ph[] = ":{$p}{$i}"; $params["{$p}{$i}"] = $v; }
        $sql .= " AND l.{$col} IN (" . implode(',', $ph) . ')';
    }
}

$st = $pdo->prepare($sql . " ORDER BY {$orderBy}");
$st->execute($params);
$rows = $st->fetchAll();
echo json_encode(['success' => true, 'count' => count($rows), 'listings' => $rows], JSON_UNESCAPED_UNICODE);
