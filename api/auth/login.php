<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
require_method('POST');

$studentId = strtoupper(trim((string) ($_POST['student_id'] ?? '')));
$name      = trim((string) ($_POST['full_name'] ?? ''));
$password  = (string) ($_POST['password'] ?? '');

if ($studentId === '') {
    json_out(['success' => false, 'message' => 'Please enter your Student ID.'], 422);
}

$st = $pdo->prepare('SELECT * FROM users WHERE student_id = ?');
$st->execute([$studentId]);
$user = $st->fetch();

if (!$user) {
    // First visit: auto-create a student account (no registration form needed)
    if (!preg_match('/^[0-9]{6,15}$/', $studentId)) {
        json_out(['success' => false, 'message' => 'Student ID must be 6-15 digits.'], 422);
    }
    $name = mb_substr($name !== '' ? $name : "Student {$studentId}", 0, 120);
    $pdo->prepare('INSERT INTO users (full_name, student_id) VALUES (?, ?)')->execute([$name, $studentId]);
    $st = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $st->execute([(int) $pdo->lastInsertId()]);
    $user = $st->fetch();
}

if ($user['status'] !== 'active') {
    json_out(['success' => false, 'message' => 'This account has been blocked by admin.'], 403);
}

// Admin must prove identity with a password
if ($user['role'] === 'admin') {
    if ($password === '') {
        json_out(['success' => false, 'needs_password' => true, 'message' => 'Admin password required.'], 401);
    }
    if (!password_verify($password, (string) $user['password_hash'])) {
        json_out(['success' => false, 'needs_password' => true, 'message' => 'Wrong admin password.'], 401);
    }
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];

json_out([
    'success'  => true,
    'role'     => $user['role'],
    'redirect' => $user['role'] === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php',
]);
