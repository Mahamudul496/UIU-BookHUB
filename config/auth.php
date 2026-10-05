<?php
declare(strict_types=1);

require_once __DIR__ . '/database.php';

header('Content-Type: application/json; charset=utf-8');

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'path' => '/']);
session_start();

function json_out(array $data, int $code = 200): never
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function require_method(string $method): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        json_out(['success' => false, 'message' => "Only {$method} requests are allowed."], 405);
    }
}

/** Returns the logged-in user row, or stops with 401. Re-checks DB so blocked users are kicked out. */
function require_login(): array
{
    global $pdo;
    $id = (int) ($_SESSION['user_id'] ?? 0);
    if ($id > 0) {
        $st = $pdo->prepare('SELECT id, full_name, student_id, department, role, status FROM users WHERE id = ?');
        $st->execute([$id]);
        $user = $st->fetch();
        if ($user && $user['status'] === 'active') {
            return $user;
        }
        $_SESSION = [];
    }
    json_out(['success' => false, 'message' => 'Please login first.'], 401);
}

function require_admin(): array
{
    $user = require_login();
    if ($user['role'] !== 'admin') {
        json_out(['success' => false, 'message' => 'Admin access only.'], 403);
    }
    return $user;
}
