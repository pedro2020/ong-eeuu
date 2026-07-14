<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

function e($v){ return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    if ($accion === "guardar_hero") {

        $stmt = $conn->prepare("
            UPDATE pathways_page SET
                hero_titulo=?,
                hero_subtitulo=?,
                hero_boton1_texto=?,
                hero_boton1_link=?,
                hero_boton2_texto=?,
                hero_boton2_link=?,
                estado=?
            WHERE id=?
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

        $stmt->execute();
        header("Location: pathways.php?ok=1"); exit;
    }

    if ($accion === "guardar_seccion") {

        $stmt = $conn->prepare("
            UPDATE pathways_sections SET
                nombre_seccion=?,
                titulo=?,
                subtitulo=?,
                contenido=?,
                orden=?,
                estado=?
            WHERE id=?
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

        $stmt->execute();
        header("Location: pathways.php?ok=1#tab-secciones"); exit;
    }

    if ($accion === "guardar_item") {

        $stmt = $conn->prepare("
            UPDATE pathways_items SET
                section_id=?,
                titulo=?,
                descripcion=?,
                orden=?,
                estado=?
            WHERE id=?
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

        $stmt->execute();
        header("Location: pathways.php?ok=1#tab-items"); exit;
    }
}

$page = $conn->query("SELECT * FROM pathways_page LIMIT 1")->fetch_assoc();

$sections = $conn->query("SELECT * FROM pathways_sections ORDER BY orden ASC");
$sectionsSelect = $conn->query("SELECT id,nombre_seccion FROM pathways_sections ORDER BY orden ASC");
$items = $conn->query("
    SELECT pi.*, ps.nombre_seccion
    FROM pathways_items pi
    LEFT JOIN pathways_sections ps ON ps.id=pi.section_id
    ORDER BY ps.orden ASC, pi.orden ASC
");

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="/admin/index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Pathways</h4>
</div>

<?php if(isset($_GET['ok'])): ?>
<div class="alert alert-success">Guardado correctamente</div>
<?php endif; ?>

<!-- TABS -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-hero">Hero</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-secciones">Secciones</button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-items">Items</button>
    </li>
</ul>

<div class="tab-content">

<!-- HERO -->
<div class="tab-pane fade show active" id="tab-hero">
<div class="card p-4">

<form method="POST">
<input type="hidden" name="accion" value="guardar_hero">
<input type="hidden" name="id" value="<?= $page['id']; ?>">

<div class="row">

<div class="col-md-6 mb-3">
<label>Título</label>
<input name="hero_titulo" class="form-control" value="<?= e($page['hero_titulo']); ?>">
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
<textarea name="hero_subtitulo" class="form-control"><?= e($page['hero_subtitulo']); ?></textarea>
</div>

<div class="col-md-3 mb-3">
<label>Botón 1</label>
<input name="hero_boton1_texto" class="form-control" value="<?= e($page['hero_boton1_texto']); ?>">
</div>

<div class="col-md-3 mb-3">
<label>Link</label>
<input name="hero_boton1_link" class="form-control" value="<?= e($page['hero_boton1_link']); ?>">
</div>

<div class="col-md-3 mb-3">
<label>Botón 2</label>
<input name="hero_boton2_texto" class="form-control" value="<?= e($page['hero_boton2_texto']); ?>">
</div>

<div class="col-md-3 mb-3">
<label>Link</label>
<input name="hero_boton2_link" class="form-control" value="<?= e($page['hero_boton2_link']); ?>">
</div>

</div>

<button class="btn btn-primary">Guardar</button>
</form>

</div>
</div>

<!-- SECCIONES -->
<div class="tab-pane fade" id="tab-secciones">
<div class="card p-4">

<?php while($s=$sections->fetch_assoc()): ?>
<form method="POST" class="border p-3 mb-3">
<input type="hidden" name="accion" value="guardar_seccion">
<input type="hidden" name="id" value="<?= $s['id']; ?>">

<div class="row">

<div class="col-md-3">
<input name="nombre_seccion" class="form-control" value="<?= e($s['nombre_seccion']); ?>">
</div>

<div class="col-md-5">
<input name="titulo" class="form-control" value="<?= e($s['titulo']); ?>">
</div>

<div class="col-md-2">
<input name="orden" class="form-control" value="<?= $s['orden']; ?>">
</div>

<div class="col-md-2">
<select name="estado" class="form-control">
<option value="1" <?= $s['estado']==1?'selected':''; ?>>Activo</option>
<option value="2" <?= $s['estado']==2?'selected':''; ?>>Inactivo</option>
</select>
</div>

<div class="col-md-12 mt-2">
<textarea name="subtitulo" class="form-control"><?= e($s['subtitulo']); ?></textarea>
</div>

<div class="col-md-12 mt-2">
<textarea name="contenido" class="form-control"><?= e($s['contenido']); ?></textarea>
</div>

</div>

<button class="btn btn-primary btn-sm mt-2">Guardar</button>
</form>
<?php endwhile; ?>

</div>
</div>

<!-- ITEMS -->
<div class="tab-pane fade" id="tab-items">
<div class="card p-4">

<?php while($i=$items->fetch_assoc()): ?>
<?php $sectionsSelect->data_seek(0); ?>

<form method="POST" class="border p-3 mb-3">
<input type="hidden" name="accion" value="guardar_item">
<input type="hidden" name="id" value="<?= $i['id']; ?>">

<div class="row">

<div class="col-md-3">
<select name="section_id" class="form-control">
<?php while($ss=$sectionsSelect->fetch_assoc()): ?>
<option value="<?= $ss['id']; ?>" <?= $ss['id']==$i['section_id']?'selected':''; ?>>
<?= $ss['nombre_seccion']; ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-5">
<input name="titulo" class="form-control" value="<?= e($i['titulo']); ?>">
</div>

<div class="col-md-2">
<input name="orden" class="form-control" value="<?= $i['orden']; ?>">
</div>

<div class="col-md-2">
<select name="estado" class="form-control">
<option value="1" <?= $i['estado']==1?'selected':''; ?>>Activo</option>
<option value="2" <?= $i['estado']==2?'selected':''; ?>>Inactivo</option>
</select>
</div>

<div class="col-md-12 mt-2">
<textarea name="descripcion" class="form-control"><?= e($i['descripcion']); ?></textarea>
</div>

</div>

<button class="btn btn-primary btn-sm mt-2">Guardar</button>
</form>

<?php endwhile; ?>

</div>
</div>

</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>