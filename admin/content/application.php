<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function rutaImagen($img){
    if(empty($img)) return "";
    if(strpos($img,"/")===0) return $img;
    if(strpos($img,"images/")===0) return "/".$img;
    return "/images/".$img;
}

function subir($campo,$actual=""){
    if(empty($_FILES[$campo]["name"])) return $actual;

    $ext = strtolower(pathinfo($_FILES[$campo]["name"], PATHINFO_EXTENSION));
    if(!in_array($ext,["jpg","jpeg","png","webp"])) return $actual;

    $dir = __DIR__ . "/../../images/application/";
    if(!is_dir($dir)) mkdir($dir,0777,true);

    $name = "app_".time()."_".rand(1000,9999).".".$ext;

    if(move_uploaded_file($_FILES[$campo]["tmp_name"], $dir.$name)){
        return "images/application/".$name;
    }

    return $actual;
}

/* ================= GUARDAR ================= */

if($_SERVER["REQUEST_METHOD"]==="POST"){

    $accion = $_POST["accion"] ?? "";

    if($accion==="page"){
        $stmt=$conn->prepare("
        UPDATE application_page SET
        hero_titulo=?,hero_subtitulo=?,
        hero_boton1_texto=?,hero_boton1_link=?,
        hero_boton2_texto=?,hero_boton2_link=?
        WHERE id=1");

        $stmt->bind_param("ssssss",
            $_POST["hero_titulo"],
            $_POST["hero_subtitulo"],
            $_POST["hero_boton1_texto"],
            $_POST["hero_boton1_link"],
            $_POST["hero_boton2_texto"],
            $_POST["hero_boton2_link"]
        );
        $stmt->execute();
    }

    if($accion==="section"){
        $img = subir("imagen",$_POST["imagen_actual"]);

        $stmt=$conn->prepare("
        UPDATE application_sections SET
        nombre_seccion=?,titulo=?,subtitulo=?,contenido=?,
        imagen=?,boton_texto=?,boton_link=?,orden=?,estado=?
        WHERE id=?");

        $stmt->bind_param("sssssssiii",
            $_POST["nombre_seccion"],
            $_POST["titulo"],
            $_POST["subtitulo"],
            $_POST["contenido"],
            $img,
            $_POST["boton_texto"],
            $_POST["boton_link"],
            $_POST["orden"],
            $_POST["estado"],
            $_POST["id"]
        );
        $stmt->execute();
    }

    if($accion==="item"){
        $img = subir("imagen",$_POST["imagen_actual"]);

        $stmt=$conn->prepare("
        UPDATE application_items SET
        titulo=?,descripcion=?,imagen=?,boton_texto=?,boton_link=?,orden=?,estado=?
        WHERE id=?");

        $stmt->bind_param("sssssiii",
            $_POST["titulo"],
            $_POST["descripcion"],
            $img,
            $_POST["boton_texto"],
            $_POST["boton_link"],
            $_POST["orden"],
            $_POST["estado"],
            $_POST["id"]
        );
        $stmt->execute();
    }

    header("Location: application.php?ok=1");
    exit;
}

/* ================= DATA ================= */

$page = $conn->query("SELECT * FROM application_page LIMIT 1")->fetch_assoc();
$sections = $conn->query("SELECT * FROM application_sections ORDER BY orden ASC");

require_once __DIR__."/../includes/header.php";
require_once __DIR__."/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="/admin/index.php" class="btn btn-light">
        ←
    </a>
    <h4 class="mb-0">Application Builder</h4>
</div>

<?php if(isset($_GET["ok"])): ?>
<div class="alert alert-success">Guardado correctamente</div>
<?php endif; ?>

<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#hero">
            Hero
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sections">
            Secciones
        </button>
    </li>
</ul>

<div class="tab-content">

<!-- HERO -->
<div class="tab-pane fade show active" id="hero">
<div class="card p-4">

<form method="POST">
<input type="hidden" name="accion" value="page">

<div class="row">

<div class="col-md-6">
<label>Título</label>
<input type="text" name="hero_titulo" class="form-control" value="<?= e($page["hero_titulo"]) ?>">
</div>

<div class="col-md-6">
<label>Subtítulo</label>
<input type="text" name="hero_subtitulo" class="form-control" value="<?= e($page["hero_subtitulo"]) ?>">
</div>

<div class="col-md-3">
<label>Botón 1</label>
<input type="text" name="hero_boton1_texto" class="form-control" value="<?= e($page["hero_boton1_texto"]) ?>">
</div>

<div class="col-md-3">
<label>Link</label>
<input type="text" name="hero_boton1_link" class="form-control" value="<?= e($page["hero_boton1_link"]) ?>">
</div>

<div class="col-md-3">
<label>Botón 2</label>
<input type="text" name="hero_boton2_texto" class="form-control" value="<?= e($page["hero_boton2_texto"]) ?>">
</div>

<div class="col-md-3">
<label>Link</label>
<input type="text" name="hero_boton2_link" class="form-control" value="<?= e($page["hero_boton2_link"]) ?>">
</div>

</div>

<button class="btn btn-primary mt-3">Guardar</button>

</form>

</div>
</div>

<!-- SECTIONS -->
<div class="tab-pane fade" id="sections">
<div class="card p-4">

<?php while($sec=$sections->fetch_assoc()): ?>

<div class="card mb-4 p-3">

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="accion" value="section">
<input type="hidden" name="id" value="<?= $sec["id"] ?>">
<input type="hidden" name="imagen_actual" value="<?= e($sec["imagen"]) ?>">

<h5><?= e($sec["nombre_seccion"]) ?></h5>

<div class="row">

<div class="col-md-4">
<input type="text" name="titulo" class="form-control" value="<?= e($sec["titulo"]) ?>">
</div>

<div class="col-md-4">
<input type="text" name="subtitulo" class="form-control" value="<?= e($sec["subtitulo"]) ?>">
</div>

<div class="col-md-2">
<input type="number" name="orden" class="form-control" value="<?= $sec["orden"] ?>">
</div>

<div class="col-md-2">
<select name="estado" class="form-control">
<option value="1" <?= $sec["estado"]==1?"selected":"" ?>>Activo</option>
<option value="0" <?= $sec["estado"]==0?"selected":"" ?>>Inactivo</option>
</select>
</div>

<div class="col-md-6 mt-2">
<textarea name="contenido" class="form-control"><?= e($sec["contenido"]) ?></textarea>
</div>

<div class="col-md-6 mt-2">
<input type="file" name="imagen" class="form-control">
<?php if($sec["imagen"]): ?>
<img src="<?= rutaImagen($sec["imagen"]) ?>" width="120">
<?php endif; ?>
</div>

</div>

<button class="btn btn-success btn-sm mt-2">Guardar sección</button>

</form>

</div>

<?php endwhile; ?>

</div>
</div>

</div>

<?php require_once __DIR__."/../includes/footer.php"; ?>