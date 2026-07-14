<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$rol = $_SESSION["rol"] ?? "";

if ($rol !== "admin" && (int)($_SESSION["puede_editar"] ?? 0) !== 1) {
    die("Acceso denegado");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "guardar_hero") {

        $stmt = $conn->prepare("
            UPDATE match_page SET
                hero_titulo = ?,
                hero_subtitulo = ?,
                hero_boton1_texto = ?,
                hero_boton1_link = ?,
                hero_boton2_texto = ?,
                hero_boton2_link = ?,
                estado = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssssii",
            $_POST["hero_titulo"],
            $_POST["hero_subtitulo"],
            $_POST["hero_boton1_texto"],
            $_POST["hero_boton1_link"],
            $_POST["hero_boton2_texto"],
            $_POST["hero_boton2_link"],
            $_POST["estado"],
            $_POST["id"]
        );

        if (!$stmt->execute()) {
            die("Error hero: " . $stmt->error);
        }

        header("Location: match.php?ok=1");
        exit;
    }

    if ($accion === "guardar_seccion") {

        $stmt = $conn->prepare("
            UPDATE match_sections SET
                nombre_seccion = ?,
                titulo = ?,
                subtitulo = ?,
                contenido = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssiii",
            $_POST["nombre_seccion"],
            $_POST["titulo"],
            $_POST["subtitulo"],
            $_POST["contenido"],
            $_POST["orden"],
            $_POST["estado"],
            $_POST["id"]
        );

        if (!$stmt->execute()) {
            die("Error sección: " . $stmt->error);
        }

        header("Location: match.php?ok=1#secciones");
        exit;
    }

    if ($accion === "guardar_item") {

        $stmt = $conn->prepare("
            UPDATE match_items SET
                section_id = ?,
                titulo = ?,
                descripcion = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "issiii",
            $_POST["section_id"],
            $_POST["titulo"],
            $_POST["descripcion"],
            $_POST["orden"],
            $_POST["estado"],
            $_POST["id"]
        );

        if (!$stmt->execute()) {
            die("Error item: " . $stmt->error);
        }

        header("Location: match.php?ok=1#items");
        exit;
    }
}

$page = $conn->query("SELECT * FROM match_page LIMIT 1")->fetch_assoc();

$sections = $conn->query("
    SELECT * FROM match_sections
    WHERE estado != 0
    ORDER BY orden ASC, id ASC
");

$sectionsSelect = $conn->query("
    SELECT id, nombre_seccion FROM match_sections
    WHERE estado != 0
    ORDER BY orden ASC
");

$items = $conn->query("
    SELECT mi.*, ms.nombre_seccion
    FROM match_items mi
    INNER JOIN match_sections ms ON ms.id = mi.section_id
    WHERE mi.estado != 0
    ORDER BY ms.orden ASC, mi.orden ASC
");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="/admin/index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Match</h4>
</div>

<?php if (isset($_GET["ok"])): ?>
    <div class="alert alert-success">Guardado correctamente</div>
<?php endif; ?>

<!-- HERO -->
<div class="card p-4 mb-4">
    <h5 class="mb-3">Hero principal</h5>

    <form method="POST">
        <input type="hidden" name="accion" value="guardar_hero">
        <input type="hidden" name="id" value="<?= $page['id']; ?>">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Título</label>
                <input type="text" name="hero_titulo" class="form-control"
                    value="<?= htmlspecialchars($page['hero_titulo']); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" <?= $page['estado']==1?'selected':''; ?>>Activo</option>
                    <option value="2" <?= $page['estado']==2?'selected':''; ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Subtítulo</label>
                <textarea name="hero_subtitulo" class="form-control" rows="3"><?= htmlspecialchars($page['hero_subtitulo']); ?></textarea>
            </div>

            <!-- BOTÓN 1 -->
            <div class="col-md-3 mb-3">
                <label>Botón 1 texto</label>
                <input type="text" name="hero_boton1_texto" class="form-control"
                    value="<?= htmlspecialchars($page['hero_boton1_texto']); ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 1 link</label>
                <input type="text" name="hero_boton1_link" class="form-control"
                    value="<?= htmlspecialchars($page['hero_boton1_link']); ?>">
            </div>

            <!-- BOTÓN 2 -->
            <div class="col-md-3 mb-3">
                <label>Botón 2 texto</label>
                <input type="text" name="hero_boton2_texto" class="form-control"
                    value="<?= htmlspecialchars($page['hero_boton2_texto']); ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 2 link</label>
                <input type="text" name="hero_boton2_link" class="form-control"
                    value="<?= htmlspecialchars($page['hero_boton2_link']); ?>">
            </div>

        </div>

        <button class="btn btn-primary">Guardar hero</button>
    </form>
</div>

<!-- SECCIONES -->
<div id="secciones" class="card p-4 mb-4">
    <h5 class="mb-3">Secciones</h5>

    <?php while($s = $sections->fetch_assoc()): ?>
        <form method="POST" class="border rounded p-3 mb-3">

            <input type="hidden" name="accion" value="guardar_seccion">
            <input type="hidden" name="id" value="<?= $s['id']; ?>">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Nombre sección</label>
                    <input class="form-control" name="nombre_seccion" value="<?= $s['nombre_seccion']; ?>">
                </div>

                <div class="col-md-5 mb-3">
                    <label>Título</label>
                    <input class="form-control" name="titulo" value="<?= $s['titulo']; ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Orden</label>
                    <input type="number" class="form-control" name="orden" value="<?= $s['orden']; ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" <?= $s['estado']==1?'selected':''; ?>>Activo</option>
                        <option value="2" <?= $s['estado']==2?'selected':''; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Subtítulo</label>
                    <textarea class="form-control" name="subtitulo"><?= $s['subtitulo']; ?></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Contenido</label>
                    <textarea class="form-control" name="contenido"><?= $s['contenido']; ?></textarea>
                </div>

            </div>

            <button class="btn btn-primary btn-sm">Guardar sección</button>
        </form>
    <?php endwhile; ?>
</div>

<!-- ITEMS -->
<div id="items" class="card p-4">
    <h5 class="mb-3">Items</h5>

    <?php while($i = $items->fetch_assoc()): ?>
        <?php $sectionsSelect->data_seek(0); ?>

        <form method="POST" class="border rounded p-3 mb-3">

            <input type="hidden" name="accion" value="guardar_item">
            <input type="hidden" name="id" value="<?= $i['id']; ?>">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Sección</label>
                    <select name="section_id" class="form-control">
                        <?php while($ss = $sectionsSelect->fetch_assoc()): ?>
                            <option value="<?= $ss['id']; ?>" <?= $ss['id']==$i['section_id']?'selected':''; ?>>
                                <?= $ss['nombre_seccion']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-5 mb-3">
                    <label>Título</label>
                    <input class="form-control" name="titulo" value="<?= $i['titulo']; ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Orden</label>
                    <input type="number" class="form-control" name="orden" value="<?= $i['orden']; ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" <?= $i['estado']==1?'selected':''; ?>>Activo</option>
                        <option value="2" <?= $i['estado']==2?'selected':''; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Descripción</label>
                    <textarea class="form-control" name="descripcion"><?= $i['descripcion']; ?></textarea>
                </div>

            </div>

            <button class="btn btn-primary btn-sm">Guardar item</button>
        </form>
    <?php endwhile; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>