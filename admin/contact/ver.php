<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT * FROM contact_messages WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$message = $stmt->get_result()->fetch_assoc();

if (!$message) {
    die("Mensaje no encontrado");
}

$update = $conn->prepare("UPDATE contact_messages SET estado = 2 WHERE id = ?");
$update->bind_param("i", $id);
$update->execute();

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Detalle del mensaje</h4>
</div>

<div class="card p-4">

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Nombre:</strong><br>
            <?= htmlspecialchars($message["first_name"] . " " . $message["last_name"]); ?>
        </div>

        <div class="col-md-6">
            <strong>Email:</strong><br>
            <?= htmlspecialchars($message["email"]); ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <strong>Teléfono:</strong><br>
            <?= htmlspecialchars($message["phone"]); ?>
        </div>

        <div class="col-md-4">
            <strong>País:</strong><br>
            <?= htmlspecialchars($message["country"]); ?>
        </div>

        <div class="col-md-4">
            <strong>Especialidad:</strong><br>
            <?= htmlspecialchars($message["specialty"]); ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <strong>Tipo:</strong><br>
            <?= htmlspecialchars($message["inquiry_type"]); ?>
        </div>

        <div class="col-md-4">
            <strong>IP:</strong><br>
            <?= htmlspecialchars($message["ip_address"]); ?>
        </div>

        <div class="col-md-4">
            <strong>Fecha:</strong><br>
            <?= htmlspecialchars($message["created_at"]); ?>
        </div>
    </div>

    <hr>

    <strong>Mensaje:</strong>
    <div class="border rounded p-3 mt-2">
        <?= nl2br(htmlspecialchars($message["message"])); ?>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="editar.php?id=<?= (int)$message["id"]; ?>" class="btn btn-warning">
            Editar
        </a>

        <a href="bloquear_ip.php?ip=<?= urlencode($message["ip_address"]); ?>" class="btn btn-danger" onclick="return confirm('¿Bloquear esta IP?')">
            Bloquear IP
        </a>

        <a href="index.php" class="btn btn-light">
            Volver
        </a>
    </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>