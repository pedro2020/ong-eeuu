<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$ip = trim($_GET["ip"] ?? "");

if ($ip !== "") {
    $stmt = $conn->prepare("UPDATE blocked_ips SET estado = 0 WHERE ip_address = ?");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
}

header("Location: index.php");
exit;