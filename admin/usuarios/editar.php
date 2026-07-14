<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET['id'] ?? 0);

$rol = $_SESSION['rol'] ?? '';

if ($rol !== 'admin' && (int)$_SESSION['puede_editar'] !== 1) {
    die("Acceso denegado");
}

$result = $conn->query("SELECT * FROM usuarios WHERE id = $id");
$user = $result->fetch_assoc();

if (!$user) {
    die("Usuario no encontrado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $rolUser = $_POST['rol'];

    $puede_crear = isset($_POST['puede_crear']) ? 1 : 0;
    $puede_editar = isset($_POST['puede_editar']) ? 1 : 0;
    $puede_eliminar = isset($_POST['puede_eliminar']) ? 1 : 0;
    $puede_ver_usuarios = isset($_POST['puede_ver_usuarios']) ? 1 : 0;

    $stmt = $conn->prepare("
        UPDATE usuarios SET
            usuario = ?,
            nombre = ?,
            rol = ?,
            puede_crear = ?,
            puede_editar = ?,
            puede_eliminar = ?,
            puede_ver_usuarios = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssiiiii",
        $usuario,
        $nombre,
        $rolUser,
        $puede_crear,
        $puede_editar,
        $puede_eliminar,
        $puede_ver_usuarios,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Editar Usuario</h4>
</div>

<form method="POST">

    <div class="row">

        <div class="col-md-6 mb-3">
            <label>Usuario</label>
            <input type="text" name="usuario" class="form-control" value="<?= $user['usuario']; ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $user['nombre']; ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control">
                <option value="usuario" <?= $user['rol']=='usuario'?'selected':''; ?>>Usuario</option>
                <option value="admin" <?= $user['rol']=='admin'?'selected':''; ?>>Admin</option>
            </select>
        </div>

    </div>

    <h6 class="mt-3">Permisos</h6>

    <div class="form-check">
        <input type="checkbox" name="puede_crear" class="form-check-input" <?= $user['puede_crear']?'checked':''; ?>>
        <label class="form-check-label">Crear</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="puede_editar" class="form-check-input" <?= $user['puede_editar']?'checked':''; ?>>
        <label class="form-check-label">Editar</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="puede_eliminar" class="form-check-input" <?= $user['puede_eliminar']?'checked':''; ?>>
        <label class="form-check-label">Eliminar</label>
    </div>

    <div class="form-check">
        <input type="checkbox" name="puede_ver_usuarios" class="form-check-input" <?= $user['puede_ver_usuarios']?'checked':''; ?>>
        <label class="form-check-label">Ver usuarios</label>
    </div>

    <button class="btn btn-primary mt-3">Actualizar</button>

</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>