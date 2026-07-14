<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

function e($v)
{
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

function empiezaCon($texto, $inicio)
{
    return substr($texto, 0, strlen($inicio)) === $inicio;
}

function rutaImagenHome($img)
{
    $img = trim((string)$img);

    if ($img === "") return "";

    if (empiezaCon($img, "http://") || empiezaCon($img, "https://")) return $img;
    if (empiezaCon($img, "/")) return $img;
    if (empiezaCon($img, "images/")) return "/" . $img;
    if (empiezaCon($img, "home/")) return "/images/" . $img;

    return "/images/home/" . $img;
}

function subirImagenHome($campo, $actual = "")
{
    if (empty($_FILES[$campo]["name"])) return $actual;

    $ext = strtolower(pathinfo($_FILES[$campo]["name"], PATHINFO_EXTENSION));
    $permitidos = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($ext, $permitidos)) return $actual;

    $dir = __DIR__ . "/../../images/home/";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $nombre = "home_" . time() . "_" . rand(1000, 9999) . "." . $ext;

    if (move_uploaded_file($_FILES[$campo]["tmp_name"], $dir . $nombre)) {
        return "images/home/" . $nombre;
    }

    return $actual;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "editar_slide") {
        $id = (int)($_POST["id"] ?? 0);

        $titulo = trim($_POST["titulo"] ?? "");
        $subtitulo = trim($_POST["subtitulo"] ?? "");
        $boton1_texto = trim($_POST["boton1_texto"] ?? "");
        $boton1_link = trim($_POST["boton1_link"] ?? "");
        $boton2_texto = trim($_POST["boton2_texto"] ?? "");
        $boton2_link = trim($_POST["boton2_link"] ?? "");
        $imagen = subirImagenHome("imagen", $_POST["imagen_actual"] ?? "");
        $orden = (int)($_POST["orden"] ?? 0);
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE slider_home SET
                titulo = ?,
                subtitulo = ?,
                boton1_texto = ?,
                boton1_link = ?,
                boton2_texto = ?,
                boton2_link = ?,
                imagen = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "sssssssiii",
            $titulo,
            $subtitulo,
            $boton1_texto,
            $boton1_link,
            $boton2_texto,
            $boton2_link,
            $imagen,
            $orden,
            $estado,
            $id
        );
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }

    if ($accion === "eliminar_slide") {
        $id = (int)($_POST["id"] ?? 0);

        $stmt = $conn->prepare("UPDATE slider_home SET estado = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }

    if ($accion === "guardar_page") {
        $id = (int)($_POST["id"] ?? 1);

        $hero_titulo = trim($_POST["hero_titulo"] ?? "");
        $hero_subtitulo = trim($_POST["hero_subtitulo"] ?? "");
        $hero_boton1_texto = trim($_POST["hero_boton1_texto"] ?? "");
        $hero_boton1_link = trim($_POST["hero_boton1_link"] ?? "");
        $hero_boton2_texto = trim($_POST["hero_boton2_texto"] ?? "");
        $hero_boton2_link = trim($_POST["hero_boton2_link"] ?? "");
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE home_page SET
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
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }

    if ($accion === "guardar_section") {
        $id = (int)($_POST["id"] ?? 0);

        $nombre_seccion = trim($_POST["nombre_seccion"] ?? "");
        $titulo = trim($_POST["titulo"] ?? "");
        $subtitulo = trim($_POST["subtitulo"] ?? "");
        $contenido = trim($_POST["contenido"] ?? "");
        $imagen = subirImagenHome("imagen", $_POST["imagen_actual"] ?? "");
        $boton_texto = trim($_POST["boton_texto"] ?? "");
        $boton_link = trim($_POST["boton_link"] ?? "");
        $orden = (int)($_POST["orden"] ?? 0);
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE home_sections SET
                nombre_seccion = ?,
                titulo = ?,
                subtitulo = ?,
                contenido = ?,
                imagen = ?,
                boton_texto = ?,
                boton_link = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "sssssssiii",
            $nombre_seccion,
            $titulo,
            $subtitulo,
            $contenido,
            $imagen,
            $boton_texto,
            $boton_link,
            $orden,
            $estado,
            $id
        );
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }

    if ($accion === "guardar_item") {
        $id = (int)($_POST["id"] ?? 0);

        $titulo = trim($_POST["titulo"] ?? "");
        $descripcion = trim($_POST["descripcion"] ?? "");
        $imagen = subirImagenHome("imagen", $_POST["imagen_actual"] ?? "");
        $boton_texto = trim($_POST["boton_texto"] ?? "");
        $boton_link = trim($_POST["boton_link"] ?? "");
        $orden = (int)($_POST["orden"] ?? 0);
        $estado = (int)($_POST["estado"] ?? 1);

        $stmt = $conn->prepare("
            UPDATE home_items SET
                titulo = ?,
                descripcion = ?,
                imagen = ?,
                boton_texto = ?,
                boton_link = ?,
                orden = ?,
                estado = ?
            WHERE id = ?
        ");
        $stmt->bind_param(
            "sssssiii",
            $titulo,
            $descripcion,
            $imagen,
            $boton_texto,
            $boton_link,
            $orden,
            $estado,
            $id
        );
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }

    if ($accion === "eliminar_item") {
        $id = (int)($_POST["id"] ?? 0);

        $stmt = $conn->prepare("UPDATE home_items SET estado = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        header("Location: home.php?ok=1");
        exit;
    }
}

$page = $conn->query("SELECT * FROM home_page LIMIT 1")->fetch_assoc();
$slider = $conn->query("SELECT * FROM slider_home WHERE estado != 0 ORDER BY orden ASC, id ASC");
$sections = $conn->query("SELECT * FROM home_sections WHERE estado != 0 ORDER BY orden ASC, id ASC");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<style>
    .builder-card {
        border: 1px solid #e9eef5;
        border-radius: 16px;
        box-shadow: 0 8px 22px rgba(16, 24, 40, 0.04);
        overflow: hidden;
    }

    .builder-header {
        background: #fff;
        border-bottom: 1px solid #eef2f7;
        padding: 18px 22px;
    }

    .builder-section-title {
        font-size: 13px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .preview-img {
        width: 180px;
        height: 115px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
    }

    .preview-icon {
        width: 74px;
        height: 74px;
        object-fit: contain;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        padding: 10px;
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
        color: #172554;
    }

    .builder-help {
        font-size: 12px;
        color: #64748b;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex align-items-center gap-2">
        <a href="/admin/index.php" class="btn btn-light">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0">Home Builder</h4>
            <small class="text-muted">Edita textos, imágenes, botones, slider, secciones e items.</small>
        </div>
    </div>
</div>

<?php if (isset($_GET["ok"])): ?>
    <div class="alert alert-success">Cambios guardados correctamente.</div>
<?php endif; ?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-slider" type="button">
            Hero Slider
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-page" type="button">
            Page / Botones
        </button>
    </li>

    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-sections" type="button">
            Secciones e Items
        </button>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="tab-slider">
        <div class="card builder-card mb-4">
            <div class="builder-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="builder-section-title">Hero principal</div>
                        <h5 class="mb-0">Slider del Home</h5>
                    </div>
                    <span class="badge bg-primary">slider_home</span>
                </div>
            </div>

            <div class="card-body p-4">
                <?php while ($s = $slider->fetch_assoc()): ?>
                    <div class="card builder-card mb-4">
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="accion" value="editar_slide">
                                <input type="hidden" name="id" value="<?= (int)$s["id"]; ?>">
                                <input type="hidden" name="imagen_actual" value="<?= e($s["imagen"] ?? ""); ?>">

                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label class="form-label">Imagen del slide</label><br>

                                        <?php if (!empty($s["imagen"])): ?>
                                            <img src="<?= e(rutaImagenHome($s["imagen"])); ?>" class="preview-img">
                                        <?php else: ?>
                                            <div class="preview-img d-flex align-items-center justify-content-center text-muted">
                                                Sin imagen
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="imagen" class="form-control mt-3" accept=".jpg,.jpeg,.png,.webp">
                                    </div>

                                    <div class="col-md-9">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Título</label>
                                                <input type="text" name="titulo" class="form-control" value="<?= e($s["titulo"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Subtítulo</label>
                                                <input type="text" name="subtitulo" class="form-control" value="<?= e($s["subtitulo"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Botón 1 texto</label>
                                                <input type="text" name="boton1_texto" class="form-control" value="<?= e($s["boton1_texto"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Botón 1 link</label>
                                                <input type="text" name="boton1_link" class="form-control" value="<?= e($s["boton1_link"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Botón 2 texto</label>
                                                <input type="text" name="boton2_texto" class="form-control" value="<?= e($s["boton2_texto"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Botón 2 link</label>
                                                <input type="text" name="boton2_link" class="form-control" value="<?= e($s["boton2_link"] ?? ""); ?>">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Orden</label>
                                                <input type="number" name="orden" class="form-control" value="<?= (int)($s["orden"] ?? 0); ?>">
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Estado</label>
                                                <select name="estado" class="form-control">
                                                    <option value="1" <?= (int)$s["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                                                    <option value="2" <?= (int)$s["estado"] === 2 ? "selected" : ""; ?>>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-primary btn-sm">Guardar slide</button>

                                            <button
                                                class="btn btn-danger btn-sm"
                                                name="accion"
                                                value="eliminar_slide"
                                                onclick="return confirm('¿Eliminar este slide?')">
                                                Eliminar slide
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-page">
        <div class="card builder-card">
            <div class="builder-header">
                <div class="builder-section-title">home_page</div>
                <h5 class="mb-0">Datos generales</h5>
                <div class="builder-help">Estos campos son generales. El hero visible principal viene del slider.</div>
            </div>

            <div class="card-body p-4">
                <form method="POST">
                    <input type="hidden" name="accion" value="guardar_page">
                    <input type="hidden" name="id" value="<?= (int)($page["id"] ?? 1); ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hero título</label>
                            <input type="text" name="hero_titulo" class="form-control" value="<?= e($page["hero_titulo"] ?? ""); ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Hero subtítulo</label>
                            <input type="text" name="hero_subtitulo" class="form-control" value="<?= e($page["hero_subtitulo"] ?? ""); ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Botón 1 texto</label>
                            <input type="text" name="hero_boton1_texto" class="form-control" value="<?= e($page["hero_boton1_texto"] ?? ""); ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Botón 1 link</label>
                            <input type="text" name="hero_boton1_link" class="form-control" value="<?= e($page["hero_boton1_link"] ?? ""); ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Botón 2 texto</label>
                            <input type="text" name="hero_boton2_texto" class="form-control" value="<?= e($page["hero_boton2_texto"] ?? ""); ?>">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Botón 2 link</label>
                            <input type="text" name="hero_boton2_link" class="form-control" value="<?= e($page["hero_boton2_link"] ?? ""); ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-control">
                                <option value="1" <?= (int)($page["estado"] ?? 1) === 1 ? "selected" : ""; ?>>Activo</option>
                                <option value="0" <?= (int)($page["estado"] ?? 1) === 0 ? "selected" : ""; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary mt-3">Guardar datos generales</button>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tab-sections">

        <?php while ($sec = $sections->fetch_assoc()): ?>

            <div class="card builder-card mb-4">
                <div class="builder-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="builder-section-title">Sección</div>
                            <h5 class="mb-0"><?= e($sec["nombre_seccion"] ?? ""); ?></h5>
                        </div>

                        <span class="badge bg-secondary">Orden <?= (int)($sec["orden"] ?? 0); ?></span>
                    </div>
                </div>

                <div class="card-body p-4">

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="guardar_section">
                        <input type="hidden" name="id" value="<?= (int)$sec["id"]; ?>">
                        <input type="hidden" name="imagen_actual" value="<?= e($sec["imagen"] ?? ""); ?>">

                        <div class="row g-4">
                            <div class="col-md-3">
                                <label class="form-label">Imagen de la sección</label><br>

                                <?php if (!empty($sec["imagen"])): ?>
                                    <img src="<?= e(rutaImagenHome($sec["imagen"])); ?>" class="preview-img">
                                <?php else: ?>
                                    <div class="preview-img d-flex align-items-center justify-content-center text-muted">
                                        Sin imagen
                                    </div>
                                <?php endif; ?>

                                <input type="file" name="imagen" class="form-control mt-3" accept=".jpg,.jpeg,.png,.webp">
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Nombre sección</label>
                                        <input type="text" name="nombre_seccion" class="form-control" value="<?= e($sec["nombre_seccion"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Título</label>
                                        <input type="text" name="titulo" class="form-control" value="<?= e($sec["titulo"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Subtítulo / etiqueta</label>
                                        <input type="text" name="subtitulo" class="form-control" value="<?= e($sec["subtitulo"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Contenido</label>
                                        <textarea name="contenido" class="form-control" rows="3"><?= e($sec["contenido"] ?? ""); ?></textarea>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Botón texto</label>
                                        <input type="text" name="boton_texto" class="form-control" value="<?= e($sec["boton_texto"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-5">
                                        <label class="form-label">Botón link</label>
                                        <input type="text" name="boton_link" class="form-control" value="<?= e($sec["boton_link"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Orden</label>
                                        <input type="number" name="orden" class="form-control" value="<?= (int)($sec["orden"] ?? 0); ?>">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-control">
                                            <option value="1" <?= (int)$sec["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                                            <option value="0" <?= (int)$sec["estado"] === 0 ? "selected" : ""; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm">Guardar sección</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Items de esta sección</h6>
                        <span class="text-muted small">home_items</span>
                    </div>

                    <?php
                    $stmtItems = $conn->prepare("
                        SELECT *
                        FROM home_items
                        WHERE section_id = ? AND estado != 0
                        ORDER BY orden ASC, id ASC
                    ");
                    $stmtItems->bind_param("i", $sec["id"]);
                    $stmtItems->execute();
                    $items = $stmtItems->get_result();
                    ?>

                    <?php if ($items->num_rows > 0): ?>
                        <?php while ($item = $items->fetch_assoc()): ?>

                            <form method="POST" enctype="multipart/form-data" class="border rounded p-3 mb-3">
                                <input type="hidden" name="accion" value="guardar_item">
                                <input type="hidden" name="id" value="<?= (int)$item["id"]; ?>">
                                <input type="hidden" name="imagen_actual" value="<?= e($item["imagen"] ?? ""); ?>">

                                <div class="row g-3 align-items-start">
                                    <div class="col-md-2">
                                        <label class="form-label">Imagen / icono</label><br>

                                        <?php if (!empty($item["imagen"])): ?>
                                            <img src="<?= e(rutaImagenHome($item["imagen"])); ?>" class="preview-icon">
                                        <?php else: ?>
                                            <div class="preview-icon d-flex align-items-center justify-content-center text-muted">
                                                -
                                            </div>
                                        <?php endif; ?>

                                        <input type="file" name="imagen" class="form-control mt-2" accept=".jpg,.jpeg,.png,.webp">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Título</label>
                                        <input type="text" name="titulo" class="form-control" value="<?= e($item["titulo"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Descripción</label>
                                        <textarea name="descripcion" class="form-control" rows="2"><?= e($item["descripcion"] ?? ""); ?></textarea>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Botón texto</label>
                                        <input type="text" name="boton_texto" class="form-control" value="<?= e($item["boton_texto"] ?? ""); ?>">

                                        <label class="form-label mt-2">Botón link</label>
                                        <input type="text" name="boton_link" class="form-control" value="<?= e($item["boton_link"] ?? ""); ?>">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Orden</label>
                                        <input type="number" name="orden" class="form-control" value="<?= (int)($item["orden"] ?? 0); ?>">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-control">
                                            <option value="1" <?= (int)$item["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                                            <option value="0" <?= (int)$item["estado"] === 0 ? "selected" : ""; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <button class="btn btn-success btn-sm">Guardar item</button>

                                    <button
                                        class="btn btn-outline-danger btn-sm"
                                        name="accion"
                                        value="eliminar_item"
                                        onclick="return confirm('¿Eliminar este item?')">
                                        Eliminar item
                                    </button>
                                </div>
                            </form>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-light border mb-0">
                            Esta sección no tiene items.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        <?php endwhile; ?>

    </div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>