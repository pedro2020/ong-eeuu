<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT * FROM blocked_ips WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$ip = $stmt->get_result()->fetch_assoc();

if (!$ip) {
    die("IP no encontrada");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ip_address = trim($_POST["ip_address"] ?? "");
    $reason = trim($_POST["reason"] ?? "");
    $estado = (int)($_POST["estado"] ?? 1);

    $update = $conn->prepare("
        UPDATE blocked_ips SET
            ip_address = ?,
            reason = ?,
            estado = ?
        WHERE id = ?
    ");

    $update->bind_param("ssii", $ip_address, $reason, $estado, $id);

    if (!$update->execute()) {
        die("Error al editar IP: " . $update->error);
    }

    header("Location: index.php?ok=1");
    exit;
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Editar IP bloqueada</h4>
</div>

<div class="card p-4">
    <form method="POST">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>IP</label>
                <input type="text" name="ip_address" class="form-control" value="<?= htmlspecialchars($ip["ip_address"]); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" <?= (int)$ip["estado"] === 1 ? "selected" : ""; ?>>Bloqueada</option>
                    <option value="0" <?= (int)$ip["estado"] === 0 ? "selected" : ""; ?>>Desbloqueada</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Razón</label>
                <textarea name="reason" class="form-control" rows="4"><?= htmlspecialchars($ip["reason"]); ?></textarea>
            </div>

        </div>

        <button class="btn btn-primary">
            Guardar cambios
        </button>

    </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>