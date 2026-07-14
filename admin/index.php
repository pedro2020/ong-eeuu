<?php
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/../config/db.php";

function contar($conn, $tabla, $where = "")
{
    $sql = "SELECT COUNT(*) AS total FROM $tabla";
    if ($where !== "") {
        $sql .= " WHERE $where";
    }

    $result = $conn->query($sql);
    if (!$result) {
        return 0;
    }

    $row = $result->fetch_assoc();
    return (int)($row["total"] ?? 0);
}

$totalMensajes = contar($conn, "contact_messages");
$totalTeam = contar($conn, "team", "estado = 1");
$totalIps = contar($conn, "blocked_ips", "estado = 1");

require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/sidebar.php";
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center mb-4">
    <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Bienvenido, <?= htmlspecialchars($_SESSION["admin_user"] ?? "Administrador"); ?>
        </p>
    </div>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-4 col-lg-3">
        <a href="/admin/contact/index.php" class="text-decoration-none">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3 text-primary">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Mensajes</h6>
                        <h4 class="mb-0 text-dark"><?= $totalMensajes; ?></h4>
                        <small class="text-muted">Contactos recibidos</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 col-lg-3">
        <a href="/admin/team/index.php" class="text-decoration-none">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">Team</h6>
                        <h4 class="mb-0 text-dark"><?= $totalTeam; ?></h4>
                        <small class="text-muted">Miembros activos</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4 col-lg-3">
        <a href="/admin/blocked-ips/index.php" class="text-decoration-none">
            <div class="card h-100 p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="fs-3 text-primary">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-dark">IPs bloqueadas</h6>
                        <h4 class="mb-0 text-dark"><?= $totalIps; ?></h4>
                        <small class="text-muted">Seguridad</small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <?php if (($_SESSION["rol"] ?? "") === "admin" || (int)($_SESSION["puede_ver_usuarios"] ?? 0) === 1): ?>
        <div class="col-md-4 col-lg-3">
            <a href="/admin/usuarios/index.php" class="text-decoration-none">
                <div class="card h-100 p-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-3 text-primary">
                            <i class="bi bi-person-gear"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 text-dark">Usuarios</h6>
                            <small class="text-muted">Gestionar accesos</small>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>