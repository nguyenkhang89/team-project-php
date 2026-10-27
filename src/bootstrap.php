<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Ho_Chi_Minh');
ini_set('session.use_strict_mode', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
session_start();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Cache-Control: no-store');
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(): never { header('Location: index.php', true, 303); exit; }
function field(string $key, int $max): string {
    $raw = $_POST[$key] ?? '';
    if (!is_string($raw)) throw new InvalidArgumentException('Dữ liệu không hợp lệ.');
    $value = trim($raw);
    if ($value === '' || strlen($value) > $max) throw new InvalidArgumentException('Thiếu nội dung hoặc nội dung quá dài.');
    return $value;
}
function positiveId(mixed $value): int {
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) throw new InvalidArgumentException('Mã bản ghi không hợp lệ.');
    return $id;
}
$configFile = dirname(__DIR__) . '/config/config.local.php';
if (!is_file($configFile)) { http_response_code(503); exit('Chưa có config/config.local.php. Xem README.md để cấu hình.'); }
$config = require $configFile;
try {
    $db = new PDO('mysql:host='.$config['host'].';dbname='.$config['database'].';charset=utf8mb4',
        $config['username'], $config['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
} catch (PDOException $ex) {
    error_log($ex->getMessage()); http_response_code(503);
    exit('Không kết nối được CSDL. Kiểm tra dịch vụ MySQL và cấu hình.');
}
