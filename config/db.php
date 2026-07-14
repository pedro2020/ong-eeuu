<?php
$host = "localhost";
$db   = "aindbite_doctores";
$user = "aindbite_doctores";
$pass = "Theverve2020%";

// ======================================
// CONEXIÓN PDO (SISTEMA ACTUAL)
// ======================================

try {
    $conexion = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}

// ======================================
// 🔥 CONEXIÓN MYSQLI (NUEVO SISTEMA DINÁMICO)
// ======================================

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error MYSQLI: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>