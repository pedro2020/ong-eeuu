<?php include "includes/header.php"; ?>
<?php require_once "config/db.php"; ?>

<link rel="stylesheet" href="/assets/css/home.css">

<?php
function e($v)
{
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

function rutaImagenHome($img)
{
    $img = trim((string)$img);

    if ($img === "") return "";

    if (strpos($img, "http://") === 0 || strpos($img, "https://") === 0) {
        return $img;
    }

    if (strpos($img, "/") === 0) {
        return $img;
    }

    if (strpos($img, "images/") === 0) {
        return "/" . $img;
    }

    if (strpos($img, "home/") === 0) {
        return "/images/" . $img;
    }

    return "/images/home/" . $img;
}

/* ================= SLIDER ================= */

$slides = $conn->query("SELECT * FROM slider_home WHERE estado = 1 ORDER BY orden ASC, id ASC");
$slidesData = [];

if ($slides) {
    while ($row = $slides->fetch_assoc()) {
        $slidesData[] = $row;
    }
}

/* ================= HOME SECTIONS ================= */

$sections = [];

$resSec = $conn->query("SELECT * FROM home_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");

if ($resSec) {
    while ($sec = $resSec->fetch_assoc()) {

        $stmt = $conn->prepare("
            SELECT *
            FROM home_items
            WHERE section_id = ? AND estado = 1
            ORDER BY orden ASC, id ASC
        ");

        $stmt->bind_param("i", $sec["id"]);
        $stmt->execute();

        $items = $stmt->get_result();
        $sec["items"] = [];

        while ($item = $items->fetch_assoc()) {
            $sec["items"][] = $item;
        }

        $sections[$sec["nombre_seccion"]] = $sec;
    }
}
?>

<!-- ================= HERO ================= -->

<section class="home-hero p-0">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

        <?php if (count($slidesData) > 1): ?>
            <div class="carousel-indicators">
                <?php foreach ($slidesData as $i => $slide): ?>
                    <button type="button"
                        data-bs-target="#heroCarousel"
                        data-bs-slide-to="<?= $i; ?>"
                        class="<?= $i === 0 ? 'active' : ''; ?>"
                        <?= $i === 0 ? 'aria-current="true"' : ''; ?>
                        aria-label="Slide <?= $i + 1; ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner">
            <?php foreach ($slidesData as $i => $row): ?>
                <div class="carousel-item <?= $i === 0 ? 'active' : ''; ?>">
                    <div class="hero-slide" style="background-image: url('<?= e(rutaImagenHome($row["imagen"] ?? "")); ?>');">
                        <div class="hero-overlay"></div>

                        <div class="container position-relative">
                            <div class="row align-items-center min-vh-100">
                                <div class="col-lg-7 text-white">

                                    <h1 class="hero-title">
                                        <?= e($row["titulo"] ?? ""); ?>
                                    </h1>

                                    <p class="hero-text">
                                        <?= e($row["subtitulo"] ?? ""); ?>
                                    </p>

                                    <div class="hero-buttons mt-4">
                                        <?php if (!empty($row["boton1_texto"])): ?>
                                            <a href="<?= e($row["boton1_link"] ?? "#"); ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                                                <?= e($row["boton1_texto"]); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty($row["boton2_texto"])): ?>
                                            <a href="<?= e($row["boton2_link"] ?? "#"); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                                                <?= e($row["boton2_texto"]); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($slidesData) > 1): ?>
            <button class="carousel-control-prev custom-carousel-control" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="custom-arrow" aria-hidden="true">‹</span>
                <span class="visually-hidden">Previous</span>
            </button>

            <button class="carousel-control-next custom-carousel-control" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="custom-arrow" aria-hidden="true">›</span>
                <span class="visually-hidden">Next</span>
            </button>
        <?php endif; ?>

    </div>
</section>

<!-- ================= FEATURES ================= -->

<?php if (isset($sections["features"])): ?>
    <section class="feature-section">
        <div class="container">
            <div class="row g-4">

                <?php foreach ($sections["features"]["items"] as $item): ?>
                    <div class="col-md-4">
                        <div class="feature-card">

                            <?php if (!empty($item["imagen"])): ?>
                                <div class="feature-icon">
                                    <img src="<?= e(rutaImagenHome($item["imagen"])); ?>" alt="<?= e($item["titulo"] ?? ""); ?>">
                                </div>
                            <?php endif; ?>

                            <h3><?= e($item["titulo"] ?? ""); ?></h3>

                            <p><?= e($item["descripcion"] ?? ""); ?></p>

                            <?php if (!empty($item["boton_texto"])): ?>
                                <a href="<?= e($item["boton_link"] ?? "#"); ?>" class="btn btn-primary rounded-pill px-4 mt-2">
                                    <?= e($item["boton_texto"]); ?>
                                </a>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ================= ABOUT ================= -->

<?php if (isset($sections["about"])): ?>
    <?php $about = $sections["about"]; ?>

    <section class="home-about py-5">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <span class="section-tag"><?= e($about["subtitulo"] ?? ""); ?></span>

                    <h2 class="section-title">
                        <?= e($about["titulo"] ?? ""); ?>
                    </h2>

                    <p class="section-text">
                        <?= nl2br(e($about["contenido"] ?? "")); ?>
                    </p>

                    <?php if (!empty($about["boton_texto"])): ?>
                        <a href="<?= e($about["boton_link"] ?? "#"); ?>" class="btn btn-primary rounded-pill px-4">
                            <?= e($about["boton_texto"]); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="col-lg-6">
                    <?php if (!empty($about["imagen"])): ?>
                        <img src="<?= e(rutaImagenHome($about["imagen"])); ?>" class="img-fluid rounded-4 shadow" alt="<?= e($about["titulo"] ?? "About"); ?>">
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ================= COMMUNITY ================= -->

<?php if (isset($sections["community"])): ?>
    <?php $community = $sections["community"]; ?>

    <section class="community-section py-5">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-5">
                    <?php if (!empty($community["imagen"])): ?>
                        <img src="<?= e(rutaImagenHome($community["imagen"])); ?>" class="img-fluid rounded-4 shadow" alt="<?= e($community["titulo"] ?? "Community"); ?>">
                    <?php endif; ?>
                </div>

                <div class="col-lg-7">
                    <span class="section-tag"><?= e($community["subtitulo"] ?? ""); ?></span>

                    <h2 class="section-title">
                        <?= e($community["titulo"] ?? ""); ?>
                    </h2>

                    <p class="section-text">
                        <?= nl2br(e($community["contenido"] ?? "")); ?>
                    </p>

                    <div class="mt-3">
                        <?php foreach ($community["items"] as $item): ?>
                            <div>
                                ✔ <?= e($item["titulo"] ?? ""); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (!empty($community["boton_texto"])): ?>
                        <a href="<?= e($community["boton_link"] ?? "#"); ?>" class="btn btn-primary rounded-pill px-4 mt-4">
                            <?= e($community["boton_texto"]); ?>
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ================= CTA ================= -->

<?php if (isset($sections["cta"])): ?>
    <?php $cta = $sections["cta"]; ?>

    <section class="home-cta text-center" style="<?php if (!empty($cta["imagen"])): ?>background-image: url('<?= e(rutaImagenHome($cta["imagen"])); ?>');<?php endif; ?>">
        <div class="cta-overlay"></div>

        <div class="container position-relative">
            <h2><?= e($cta["titulo"] ?? ""); ?></h2>

            <p><?= e($cta["subtitulo"] ?? ""); ?></p>

            <?php
            $ctaTexto = $cta["boton_texto"] ?? "";
            $ctaLink = $cta["boton_link"] ?? "";

            if (empty($ctaTexto) && !empty($cta["items"][0]["titulo"])) {
                $ctaTexto = $cta["items"][0]["titulo"];
            }

            if (empty($ctaLink) && !empty($cta["items"][0]["descripcion"])) {
                $ctaLink = $cta["items"][0]["descripcion"];
            }
            ?>

            <?php if (!empty($ctaTexto)): ?>
                <a href="<?= e($ctaLink ?: "#"); ?>" class="btn btn-warning btn-lg rounded-pill px-4">
                    <?= e($ctaTexto); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php include "includes/footer.php"; ?>