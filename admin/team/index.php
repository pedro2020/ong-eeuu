<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$sql = "SELECT * FROM team WHERE estado != 0 ORDER BY orden ASC, id DESC";
$result = $conn->query($sql);

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Team</h4>

    <a href="crear.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Nuevo miembro
    </a>
</div>

<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th width="70">Foto</th>
                    <th>Nombre</th>
                    <th>Título</th>
                    <th>Especialidad</th>
                    <th>Grupo</th>
                    <th>Orden</th>
                    <th>Estado</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['foto'])): ?>
                                <img src="/images/<?= htmlspecialchars($row['foto']); ?>" width="50" height="50" style="object-fit:cover;border-radius:50%;">
                            <?php else: ?>
                                <span class="text-muted">Sin foto</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($row['nombre_completo']); ?></td>
                        <td><?= htmlspecialchars($row['titulo']); ?></td>

                        <td><?= htmlspecialchars($row['especialidad']); ?></td>

                        <td>
                            <?=
                            match ($row['grupo'] ?? 'leadership') {
                                'step1' => 'Step 1 Team',
                                'step2' => 'Step 2 Team',
                                'step3' => 'Step 3 Team',
                                'socialmedia' => 'Social Media Team',
                                'pediatric' => 'Pediatric Team',
                                'internalmedicine' => 'Internal Medicine Team',
                                default => 'Leadership Team'
                            };
                            ?>
                        </td>

                        <td><?= (int)$row['orden']; ?></td>


                        <td>
                            <?php if ((int)$row['estado'] === 1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="editar.php?id=<?= (int)$row['id']; ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="eliminar.php?id=<?= (int)$row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar miembro?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>