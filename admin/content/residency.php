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

        $id = (int)($_POST["id"] ?? 0);

        $hero_titulo = trim($_POST["hero_titulo"] ?? "");
        $hero_subtitulo = trim($_POST["hero_subtitulo"] ?? "");
        $hero_boton1_texto = trim($_POST["hero_boton1_texto"] ?? "");
        $hero_boton1_link = trim($_POST["hero_boton1_link"] ?? "");
        $hero_boton2_texto = trim($_POST["hero_boton2_texto"] ?? "");
        $hero_boton2_link = trim($_POST["hero_boton2_link"] ?? "");
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE residency_page SET
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
            $hero_titulo,
            $hero_subtitulo,
            $hero_boton1_texto,
            $hero_boton1_link,
            $hero_boton2_texto,
            $hero_boton2_link,
            $estado,
            $id
        );

        if (!$stmt->execute()) {
            die("Error al guardar hero: " . $stmt->error);
        }

        header("Location: residency.php?ok=1");
        exit;
    }

    if ($accion === "guardar_seccion") {

        $id = (int)($_POST["id"] ?? 0);

        $nombre_seccion = trim($_POST["nombre_seccion"] ?? "");
        $titulo = trim($_POST["titulo"] ?? "");
        $subtitulo = trim($_POST["subtitulo"] ?? "");
        $contenido = trim($_POST["contenido"] ?? "");
        $orden = (int)($_POST["orden"] ?? 0);
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE residency_sections SET
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
            $nombre_seccion,
            $titulo,
            $subtitulo,
            $contenido,
            $orden,
            $estado,
            $id
        );

        if (!$stmt->execute()) {
            die("Error al guardar sección: " . $stmt->error);
        }

        header("Location: residency.php?ok=1#secciones");
        exit;
    }

    if ($accion === "guardar_item") {

        $id = (int)($_POST["id"] ?? 0);

        $section_id = (int)($_POST["section_id"] ?? 0);
        $titulo = trim($_POST["titulo"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $orden = (int)($_POST["orden"] ?? 0);
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE residency_items SET
                section_id = ?,
                titulo = ?,
                descripcion = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "issiii",
            $section_id,
            $titulo,
            $descripcion,
            $orden,
            $estado,
            $id
        );

        if (!$stmt->execute()) {
            die("Error al guardar item: " . $stmt->error);
        }

        header("Location: residency.php?ok=1#items");
        exit;
    }
}

$pageResult = $conn->query("SELECT * FROM residency_page ORDER BY id ASC LIMIT 1");
$page = $pageResult ? $pageResult->fetch_assoc() : null;

if (!$page) {
    die("No existe registro en residency_page");
}

$sections = $conn->query("
    SELECT * 
    FROM residency_sections 
    WHERE estado != 0
    ORDER BY orden ASC, id ASC
");

$sectionsSelect = $conn->query("
    SELECT id, titulo 
    FROM residency_sections 
    WHERE estado != 0
    ORDER BY orden ASC, id ASC
");

$items = $conn->query("
    SELECT ri.*, rs.titulo AS seccion_titulo
    FROM residency_items ri
    LEFT JOIN residency_sections rs ON rs.id = ri.section_id
    WHERE ri.estado != 0
    ORDER BY ri.section_id ASC, ri.orden ASC, ri.id ASC
");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="/admin/index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Residency</h4>
</div>

<?php if (isset($_GET["ok"])): ?>
    <div class="alert alert-success">
        Cambios guardados correctamente.
    </div>
<?php endif; ?>

<div class="card p-4 mb-4">
    <h5 class="mb-3">Hero principal</h5>

    <form method="POST">
        <input type="hidden" name="accion" value="guardar_hero">
        <input type="hidden" name="id" value="<?= (int)$page["id"]; ?>">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Título</label>
                <input type="text" name="hero_titulo" class="form-control" value="<?= htmlspecialchars($page["hero_titulo"] ?? ""); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" <?= (int)($page["estado"] ?? 1) === 1 ? "selected" : ""; ?>>Activo</option>
                    <option value="2" <?= (int)($page["estado"] ?? 1) === 2 ? "selected" : ""; ?>>Inactivo</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Subtítulo</label>
                <textarea name="hero_subtitulo" class="form-control" rows="3"><?= htmlspecialchars($page["hero_subtitulo"] ?? ""); ?></textarea>
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 1 texto</label>
                <input type="text" name="hero_boton1_texto" class="form-control" value="<?= htmlspecialchars($page["hero_boton1_texto"] ?? ""); ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 1 link</label>
                <input type="text" name="hero_boton1_link" class="form-control" value="<?= htmlspecialchars($page["hero_boton1_link"] ?? ""); ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 2 texto</label>
                <input type="text" name="hero_boton2_texto" class="form-control" value="<?= htmlspecialchars($page["hero_boton2_texto"] ?? ""); ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Botón 2 link</label>
                <input type="text" name="hero_boton2_link" class="form-control" value="<?= htmlspecialchars($page["hero_boton2_link"] ?? ""); ?>">
            </div>

        </div>

        <button type="submit" class="btn btn-primary">
            Guardar hero
        </button>
    </form>
</div>

<div id="secciones" class="card p-4 mb-4">
    <h5 class="mb-3">Secciones</h5>

    <?php while ($section = $sections->fetch_assoc()): ?>
        <form method="POST" class="border rounded p-3 mb-3">
            <input type="hidden" name="accion" value="guardar_seccion">
            <input type="hidden" name="id" value="<?= (int)$section["id"]; ?>">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Nombre sección</label>
                    <input type="text" name="nombre_seccion" class="form-control" value="<?= htmlspecialchars($section["nombre_seccion"] ?? ""); ?>">
                </div>

                <div class="col-md-5 mb-3">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($section["titulo"] ?? ""); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Orden</label>
                    <input type="number" name="orden" class="form-control" value="<?= (int)($section["orden"] ?? 0); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" <?= (int)$section["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                        <option value="2" <?= (int)$section["estado"] === 2 ? "selected" : ""; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Subtítulo</label>
                    <textarea name="subtitulo" class="form-control" rows="2"><?= htmlspecialchars($section["subtitulo"] ?? ""); ?></textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Contenido</label>
                    <textarea name="contenido" class="form-control" rows="4"><?= htmlspecialchars($section["contenido"] ?? ""); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-primary btn-sm">
                Guardar sección
            </button>
        </form>
    <?php endwhile; ?>
</div>

<div id="items" class="card p-4">
    <h5 class="mb-3">Items / Cards</h5>

    <?php while ($item = $items->fetch_assoc()): ?>
        <?php $sectionsSelect->data_seek(0); ?>

        <form method="POST" class="border rounded p-3 mb-3">
            <input type="hidden" name="accion" value="guardar_item">
            <input type="hidden" name="id" value="<?= (int)$item["id"]; ?>">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Sección</label>
                    <select name="section_id" class="form-control">
                        <?php while ($sectionOption = $sectionsSelect->fetch_assoc()): ?>
                            <option value="<?= (int)$sectionOption["id"]; ?>" <?= (int)$sectionOption["id"] === (int)$item["section_id"] ? "selected" : ""; ?>>
                                <?= htmlspecialchars($sectionOption["titulo"]); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-5 mb-3">
                    <label>Título</label>
                    <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($item["titulo"] ?? ""); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Orden</label>
                    <input type="number" name="orden" class="form-control" value="<?= (int)($item["orden"] ?? 0); ?>">
                </div>

                <div class="col-md-2 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="1" <?= (int)$item["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                        <option value="2" <?= (int)$item["estado"] === 2 ? "selected" : ""; ?>>Inactivo</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($item["descripcion"] ?? ""); ?></textarea>
                </div>

            </div>

            <button type="submit" class="btn btn-primary btn-sm">
                Guardar item
            </button>
        </form>
    <?php endwhile; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>