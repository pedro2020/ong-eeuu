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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"] ?? "");
    $last_name = trim($_POST["last_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $country = trim($_POST["country"] ?? "");
    $specialty = trim($_POST["specialty"] ?? "");
    $inquiry_type = trim($_POST["inquiry_type"] ?? "");
    $message_text = trim($_POST["message"] ?? "");
    $estado = (int)($_POST["estado"] ?? 1);

    $update = $conn->prepare("
        UPDATE contact_messages SET
            first_name = ?,
            last_name = ?,
            email = ?,
            phone = ?,
            country = ?,
            specialty = ?,
            inquiry_type = ?,
            message = ?,
            estado = ?
        WHERE id = ?
    ");

    $update->bind_param(
        "ssssssssii",
        $first_name,
        $last_name,
        $email,
        $phone,
        $country,
        $specialty,
        $inquiry_type,
        $message_text,
        $estado,
        $id
    );

    if (!$update->execute()) {
        die("Error al editar mensaje: " . $update->error);
    }

    header("Location: ver.php?id=" . $id);
    exit;
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="ver.php?id=<?= (int)$id; ?>" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Editar mensaje</h4>
</div>

<div class="card p-4">
    <form method="POST">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($message["first_name"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Apellido</label>
                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($message["last_name"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($message["email"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Teléfono</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($message["phone"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>País</label>
                <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($message["country"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Especialidad</label>
                <input type="text" name="specialty" class="form-control" value="<?= htmlspecialchars($message["specialty"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tipo</label>
                <input type="text" name="inquiry_type" class="form-control" value="<?= htmlspecialchars($message["inquiry_type"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" <?= (int)$message["estado"] === 1 ? "selected" : ""; ?>>No leído</option>
                    <option value="2" <?= (int)$message["estado"] === 2 ? "selected" : ""; ?>>Leído</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mensaje</label>
                <textarea name="message" class="form-control" rows="6"><?= htmlspecialchars($message["message"]); ?></textarea>
            </div>
        </div>

        <button class="btn btn-primary">
            Guardar cambios
        </button>

    </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>