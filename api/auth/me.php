<?php
declare(strict_types=1);
require_once __DIR__ . '/../../config/auth.php';
$user = require_login();
json_out(['success' => true, 'user' => $user]);
