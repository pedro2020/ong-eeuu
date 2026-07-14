<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre_completo = trim($_POST["nombre_completo"]);
    $titulo = trim($_POST["titulo"]);
    $especialidad = trim($_POST["especialidad"]);
    $grupo = trim($_POST["grupo"]);
    $email = trim($_POST["email"]);

    $bio_1 = trim($_POST["bio_1"]);
    $bio_2 = trim($_POST["bio_2"]);
    $bio_3 = trim($_POST["bio_3"]);
    $bio_4 = trim($_POST["bio_4"]);

    $twitter = trim($_POST["twitter"]);
    $instagram = trim($_POST["instagram"]);
    $facebook = trim($_POST["facebook"]);
    $linkedin = trim($_POST["linkedin"]);

    $orden = (int)$_POST["orden"];
    $estado = (int)$_POST["estado"];

    $foto = "";

    if (!empty($_FILES["foto"]["name"])) {
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $permitidos = ["jpg", "jpeg", "png"];

        if (in_array($ext, $permitidos)) {
            $foto = "team_" . time() . "." . $ext;
            move_uploaded_file($_FILES["foto"]["tmp_name"], __DIR__ . "/../../images/" . $foto);
        }
    }

    $stmt = $conn->prepare("
        INSERT INTO team (
            nombre_completo, titulo, especialidad, grupo, email,
            bio_1, bio_2, bio_3, bio_4,
            twitter, instagram, facebook, linkedin,
            foto, orden, estado
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
     ");

    $stmt->bind_param(
        "ssssssssssssssii",
        $nombre_completo,
        $titulo,
        $especialidad,
        $grupo,
        $email,
        $bio_1,
        $bio_2,
        $bio_3,
        $bio_4,
        $twitter,
        $instagram,
        $facebook,
        $linkedin,
        $foto,
        $orden,
        $estado
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/sidebar.php";
?>

<div class="d-flex align-items-center mb-4 gap-2">
    <a href="index.php" class="btn btn-light">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="mb-0">Nuevo Miembro</h4>
</div>

<form method="POST" enctype="multipart/form-data">

    <div class="card p-4">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Especialidad</label>
                <input type="text" name="especialidad" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Grupo</label>

                <select name="grupo" class="form-control" required>

                    <option value="leadership">Leadership Team</option>
                    <option value="step1">Step 1 Team</option>
                    <option value="step2">Step 2 Team</option>
                    <option value="step3">Step 3 Team</option>
                    <option value="socialmedia">Social Media Team</option>
                    <option value="pediatric">Pediatric Team</option>
                    <option value="internalmedicine">Internal Medicine Team</option>

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
            </div>

            <div class="col-md-3 mb-3">
                <label>Orden</label>
                <input type="number" name="orden" class="form-control" value="0">
            </div>

            <div class="col-md-3 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </select>
            </div>

        </div>

        <hr>

        <h6>Biografía</h6>

        <div class="mb-3">
            <label>Bio 1</label>
            <textarea name="bio_1" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 2</label>
            <textarea name="bio_2" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 3</label>
            <textarea name="bio_3" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 4</label>
            <textarea name="bio_4" class="form-control" rows="3"></textarea>
        </div>

        <hr>

        <h6>Redes sociales</h6>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Twitter</label>
                <input type="text" name="twitter" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Instagram</label>
                <input type="text" name="instagram" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Facebook</label>
                <input type="text" name="facebook" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>LinkedIn</label>
                <input type="text" name="linkedin" class="form-control">
            </div>

        </div>

        <button class="btn btn-primary">
            Guardar
        </button>

    </div>

</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>