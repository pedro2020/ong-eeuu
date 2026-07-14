<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$result = $conn->query("
    SELECT *
    FROM blocked_ips
    ORDER BY created_at DESC, id DESC
");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="/admin/index.php" class="btn btn-light">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0">IPs bloqueadas</h4>
    </div>

    <a href="crear.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Bloquear IP
    </a>
</div>

<?php if (isset($_GET["ok"])): ?>
    <div class="alert alert-success">
        Cambios guardados correctamente.
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Razón</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th width="180">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row["id"]; ?></td>
                        <td><?= htmlspecialchars($row["ip_address"]); ?></td>
                        <td><?= htmlspecialchars($row["reason"]); ?></td>
                        <td>
                            <?php if ((int)$row["estado"] === 1): ?>
                                <span class="badge bg-danger">Bloqueada</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Desbloqueada</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row["created_at"]); ?></td>
                        <td>
                            <a href="editar.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <?php if ((int)$row["estado"] === 1): ?>
                                <a href="desbloquear.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-success" onclick="return confirm('¿Desbloquear esta IP?')">
                                    <i class="bi bi-unlock"></i>
                                </a>
                            <?php else: ?>
                                <a href="bloquear.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Bloquear nuevamente esta IP?')">
                                    <i class="bi bi-lock"></i>
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