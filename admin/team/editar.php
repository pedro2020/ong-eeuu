<?php
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../../config/db.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = $conn->prepare("SELECT * FROM team WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$team = $result->fetch_assoc();

if (!$team) {
    die("Miembro no encontrado");
}

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

    $foto = $team["foto"];

    if (!empty($_FILES["foto"]["name"])) {
        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $permitidos = ["jpg", "jpeg", "png"];

        if (in_array($ext, $permitidos)) {
            $foto = "team_" . time() . "." . $ext;
            move_uploaded_file($_FILES["foto"]["tmp_name"], __DIR__ . "/../../images/" . $foto);
        }
    }

    $stmt = $conn->prepare("
        UPDATE team SET
            nombre_completo = ?,
            titulo = ?,
            especialidad = ?,
            grupo = ?,
            email = ?,
            bio_1 = ?,
            bio_2 = ?,
            bio_3 = ?,
            bio_4 = ?,
            twitter = ?,
            instagram = ?,
            facebook = ?,
            linkedin = ?,
            foto = ?,
            orden = ?,
            estado = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssssssssssssssiii",
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
        $estado,
        $id
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
    <h4 class="mb-0">Editar Miembro</h4>
</div>

<form method="POST" enctype="multipart/form-data">

    <div class="card p-4">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($team["nombre_completo"]); ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($team["titulo"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Especialidad</label>
                <input type="text" name="especialidad" class="form-control" value="<?= htmlspecialchars($team["especialidad"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Grupo</label>

                <select name="grupo" class="form-control">

                    <option value="leadership" <?= ($team["grupo"] ?? "") == "leadership" ? "selected" : ""; ?>>
                        Leadership Team
                    </option>

                    <option value="step1" <?= ($team["grupo"] ?? "") == "step1" ? "selected" : ""; ?>>
                        Step 1 Team
                    </option>

                    <option value="step2" <?= ($team["grupo"] ?? "") == "step2" ? "selected" : ""; ?>>
                        Step 2 Team
                    </option>

                    <option value="step3" <?= ($team["grupo"] ?? "") == "step3" ? "selected" : ""; ?>>
                        Step 3 Team
                    </option>

                    <option value="socialmedia" <?= ($team["grupo"] ?? "") == "socialmedia" ? "selected" : ""; ?>>
                        Social Media Team
                    </option>

                    <option value="pediatric" <?= ($team["grupo"] ?? "") == "pediatric" ? "selected" : ""; ?>>
                        Pediatric Team
                    </option>

                    <option value="internalmedicine" <?= ($team["grupo"] ?? "") == "internalmedicine" ? "selected" : ""; ?>>
                        Internal Medicine Team
                    </option>

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($team["email"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Reemplazar foto</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">

                <?php if (!empty($team["foto"])): ?>
                    <div class="mt-2">
                        <img src="/images/<?= htmlspecialchars($team["foto"]); ?>" width="90" height="90" style="object-fit:cover;border-radius:10px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-3 mb-3">
                <label>Orden</label>
                <input type="number" name="orden" class="form-control" value="<?= (int)$team["orden"]; ?>">
            </div>

            <div class="col-md-3 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" <?= (int)$team["estado"] === 1 ? "selected" : ""; ?>>Activo</option>
                    <option value="2" <?= (int)$team["estado"] === 2 ? "selected" : ""; ?>>Inactivo</option>
                </select>
            </div>

        </div>

        <hr>

        <h6>Biografía</h6>

        <div class="mb-3">
            <label>Bio 1</label>
            <textarea name="bio_1" class="form-control" rows="3"><?= htmlspecialchars($team["bio_1"]); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 2</label>
            <textarea name="bio_2" class="form-control" rows="3"><?= htmlspecialchars($team["bio_2"]); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 3</label>
            <textarea name="bio_3" class="form-control" rows="3"><?= htmlspecialchars($team["bio_3"]); ?></textarea>
        </div>

        <div class="mb-3">
            <label>Bio 4</label>
            <textarea name="bio_4" class="form-control" rows="3"><?= htmlspecialchars($team["bio_4"]); ?></textarea>
        </div>

        <hr>

        <h6>Redes sociales</h6>

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Twitter</label>
                <input type="text" name="twitter" class="form-control" value="<?= htmlspecialchars($team["twitter"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Instagram</label>
                <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($team["instagram"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Facebook</label>
                <input type="text" name="facebook" class="form-control" value="<?= htmlspecialchars($team["facebook"]); ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>LinkedIn</label>
                <input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($team["linkedin"]); ?>">
            </div>

        </div>

        <button class="btn btn-primary">
            Actualizar
        </button>

    </div>

</form>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>