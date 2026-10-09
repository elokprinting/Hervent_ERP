<?php
// HERVENT - koneksi ke database lama Hostinger.
$db_host = 'localhost';
$db_name = 'u966833962_erp_hervent';
$db_user = 'u966833962_erp_hervent';
$db_pass = '2026Rebound';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        )
    );
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('ok'=>false,'error'=>'Koneksi database gagal. Periksa db.php.'));
    exit;
}
