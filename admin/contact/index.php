<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$where = "1=1";
$params = [];
$types = "";

if (($_GET["fecha"] ?? "") === "hoy") {
    $where .= " AND DATE(created_at) = CURDATE()";
}

if (!empty($_GET["desde"]) && !empty($_GET["hasta"])) {
    $where .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $_GET["desde"];
    $params[] = $_GET["hasta"];
    $types .= "ss";
}

$sql = "SELECT * FROM contact_messages WHERE $where ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$messages = $stmt->get_result();

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="/admin/index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Mensajes de Contacto</h4>
</div>

<div class="card p-4 mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label>Filtro rápido</label>
            <select name="fecha" class="form-control">
                <option value="">Todos</option>
                <option value="hoy" <?= ($_GET["fecha"] ?? "") === "hoy" ? "selected" : ""; ?>>Hoy</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Desde</label>
            <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($_GET["desde"] ?? ""); ?>">
        </div>

        <div class="col-md-3">
            <label>Hasta</label>
            <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($_GET["hasta"] ?? ""); ?>">
        </div>

        <div class="col-md-3">
            <button class="btn btn-primary">Filtrar</button>
            <a href="index.php" class="btn btn-light">Limpiar</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>IP</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th width="260">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $messages->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["first_name"] . " " . $row["last_name"]); ?></td>
                        <td><?= htmlspecialchars($row["email"]); ?></td>
                        <td><?= htmlspecialchars($row["inquiry_type"]); ?></td>
                        <td><?= htmlspecialchars($row["ip_address"]); ?></td>
                        <td>
                            <?php if ((int)$row["estado"] === 2): ?>
                                <span class="badge bg-success">Leído</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">No leído</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row["created_at"]); ?></td>
                        <td>
                            <a href="ver.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="editar.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="marcar_estado.php?id=<?= (int)$row["id"]; ?>" class="btn btn-sm btn-secondary">
                                <i class="bi bi-check2-circle"></i>
                            </a>

                            <a href="bloquear_ip.php?ip=<?= urlencode($row["ip_address"]); ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Bloquear esta IP?')">
                                <i class="bi bi-ban"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>