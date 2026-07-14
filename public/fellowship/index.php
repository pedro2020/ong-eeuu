<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM fellowship_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM fellowship_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT fi.*, fs.nombre_seccion
    FROM fellowship_items fi
    INNER JOIN fellowship_sections fs ON fs.id = fi.section_id
    WHERE fi.estado = 1 AND fs.estado = 1
    ORDER BY fs.orden ASC, fi.orden ASC
");

if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$overviewItems = $itemsBySection['overview'] ?? [];
$requirementsItems = $itemsBySection['requirements'] ?? [];
$processItems = $itemsBySection['process'] ?? [];

function sectionValue($sections, $name, $field, $default = '') {
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/fellowship.css">

<!-- HERO -->
<section class="fellowship-hero" style="background-image:url('/<?php echo $page['hero_imagen'] ?? ''; ?>')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7 text-white">
                <span class="badge-custom">Fellowship</span>
                <h1><?php echo $page['hero_titulo'] ?? 'Fellowship Opportunities'; ?></h1>
                <p><?php echo $page['hero_subtitulo'] ?? ''; ?></p>

                <div class="buttons">
                    <a href="<?php echo $page['hero_btn1_link'] ?? '#'; ?>" class="btn btn-primary">
                        <?php echo $page['hero_btn1_text'] ?? 'Explore'; ?>
                    </a>
                    <a href="<?php echo $page['hero_btn2_link'] ?? '#'; ?>" class="btn btn-outline-light">
                        <?php echo $page['hero_btn2_text'] ?? 'Contact'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OVERVIEW -->
<section class="section">
    <div class="container text-center">
        <span class="chip">Overview</span>
        <h2><?php echo sectionValue($sections,'overview','titulo','Fellowship Overview'); ?></h2>
        <p><?php echo sectionValue($sections,'overview','subtitulo',''); ?></p>

        <div class="row mt-5">
            <?php foreach($overviewItems as $item){ ?>
            <div class="col-md-4">
                <div class="card-box">
                    <h4><?php echo $item['titulo']; ?></h4>
                    <p><?php echo $item['descripcion']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- REQUIREMENTS -->
<section class="section bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <span class="chip">Requirements</span>
                <h2><?php echo sectionValue($sections,'requirements','titulo','Requirements'); ?></h2>
                <p><?php echo sectionValue($sections,'requirements','subtitulo',''); ?></p>
            </div>

            <div class="col-lg-7">
                <?php foreach($requirementsItems as $item){ ?>
                <div class="timeline">
                    <span>✓</span>
                    <div>
                        <h4><?php echo $item['titulo']; ?></h4>
                        <p><?php echo $item['descripcion']; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- PROCESS -->
<section class="section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <span class="chip">Process</span>
                <h2><?php echo sectionValue($sections,'process','titulo','Application Process'); ?></h2>
                <p><?php echo sectionValue($sections,'process','subtitulo',''); ?></p>
            </div>

            <div class="col-lg-7">
                <?php $i=1; foreach($processItems as $item){ ?>
                <div class="timeline">
                    <span><?php echo $i++; ?></span>
                    <div>
                        <h4><?php echo $item['titulo']; ?></h4>
                        <p><?php echo $item['descripcion']; ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta text-center">
    <div class="container">
        <h2><?php echo sectionValue($sections,'cta','titulo','Start your Fellowship journey'); ?></h2>
        <p><?php echo sectionValue($sections,'cta','subtitulo',''); ?></p>
        <a href="/public/contact/" class="btn btn-warning">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>