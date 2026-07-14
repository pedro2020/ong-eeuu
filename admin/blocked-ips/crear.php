<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ip_address = trim($_POST["ip_address"] ?? "");
    $reason = trim($_POST["reason"] ?? "");
    $estado = (int)($_POST["estado"] ?? 1);

    if ($ip_address !== "") {
        $stmt = $conn->prepare("
            INSERT INTO blocked_ips (ip_address, reason, estado)
            VALUES (?, ?, ?)
        ");

        $stmt->bind_param("ssi", $ip_address, $reason, $estado);

        if (!$stmt->execute()) {
            die("Error al bloquear IP: " . $stmt->error);
        }

        header("Location: index.php?ok=1");
        exit;
    }
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Bloquear IP</h4>
</div>

<div class="card p-4">
    <form method="POST">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>IP</label>
                <input type="text" name="ip_address" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1">Bloqueada</option>
                    <option value="0">Desbloqueada</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Razón</label>
                <textarea name="reason" class="form-control" rows="4">Bloqueada manualmente desde panel administrativo</textarea>
            </div>

        </div>

        <button class="btn btn-primary">
            Guardar
        </button>

    </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>