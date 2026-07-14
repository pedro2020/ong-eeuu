<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$ip = trim($_GET["ip"] ?? "");

if ($ip === "") {
    header("Location: index.php");
    exit;
}

$reason = "Bloqueada desde panel administrativo";

$stmt = $conn->prepare("
    INSERT INTO blocked_ips (ip_address, reason, estado)
    VALUES (?, ?, 1)
    ON DUPLICATE KEY UPDATE
        reason = VALUES(reason),
        estado = 1
");

$stmt->bind_param("ss", $ip, $reason);
$stmt->execute();

header("Location: index.php");
exit;