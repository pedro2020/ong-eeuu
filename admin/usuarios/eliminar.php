<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET['id'] ?? 0);

$rol = $_SESSION['rol'] ?? '';

if ($rol !== 'admin' && (int)$_SESSION['puede_eliminar'] !== 1) {
    die("Acceso denegado");
}

$stmt = $conn->prepare("UPDATE usuarios SET estado = 0 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit;