<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

/* =========================
   PERMISOS
========================= */
$rol = $_SESSION['rol'] ?? '';
$puedeVer = (int)($_SESSION['puede_ver_usuarios'] ?? 0);

if ($rol !== 'admin' && $puedeVer !== 1) {
    die("Acceso denegado");
}

/* =========================
   LISTADO
========================= */
$result = $conn->query("SELECT * FROM usuarios ORDER BY id DESC");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Usuarios</h4>

    <?php if ($rol === 'admin' || $_SESSION['puede_crear'] == 1): ?>
        <a href="crear.php" class="btn btn-primary">
            <i class="bi bi-plus"></i> Nuevo usuario
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['usuario']); ?></td>
                        <td><?= htmlspecialchars($row['nombre']); ?></td>
                        <td>
                            <span class="badge bg-primary">
                                <?= $row['rol']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['estado'] == 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>

                            <?php if ($rol === 'admin' || $_SESSION['puede_editar'] == 1): ?>
                                <a href="editar.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($rol === 'admin' || $_SESSION['puede_eliminar'] == 1): ?>
                                <a href="eliminar.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endwhile; ?>

            </tbody>

        </table>

    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>