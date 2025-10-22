<?php
// ปรับค่า DB ให้ตรงกับเครื่องคุณ
$DB_HOST = 'localhost';
$DB_NAME = 'garbage_bank';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
