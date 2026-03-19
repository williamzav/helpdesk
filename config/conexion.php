<?php
// ==========================================
// CONFIGURACIÓN DE CONEXIÓN A BASE DE DATOS
// ==========================================

$host     = "localhost";
$dbname   = "helpdesk";
$user     = "root";
$password = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "msg" => "Error de conexión: " . $e->getMessage()]);
    exit;
}
?>