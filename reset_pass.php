<?php
require_once __DIR__ . '/config/database.php';

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hash]);
    echo "Password admin berhasil direset menjadi: admin123";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
