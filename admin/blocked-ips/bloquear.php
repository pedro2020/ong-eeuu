<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("UPDATE blocked_ips SET estado = 1 WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error al bloquear IP: " . $stmt->error);
}

header("Location: index.php?ok=1");
exit;