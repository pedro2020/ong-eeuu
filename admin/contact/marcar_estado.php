<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("
    UPDATE contact_messages
    SET estado = CASE 
        WHEN estado = 2 THEN 1
        ELSE 2
    END
    WHERE id = ?
");

$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error al cambiar estado: " . $stmt->error);
}

header("Location: index.php");
exit;