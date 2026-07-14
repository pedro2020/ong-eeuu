<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM match_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM match_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT mi.*, ms.nombre_seccion
    FROM match_items mi
    INNER JOIN match_sections ms ON ms.id = mi.section_id
    WHERE mi.estado = 1 AND ms.estado = 1
    ORDER BY ms.orden ASC, mi.orden ASC, mi.id ASC
");
if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$overviewItems  = $itemsBySection['overview'] ?? [];
$preMatchItems  = $itemsBySection['pre_match'] ?? [];
$postMatchItems = $itemsBySection['post_match'] ?? [];

$heroTitulo      = $page['hero_titulo'] ?? 'Match Process';
$heroSubtitulo   = $page['hero_subtitulo'] ?? 'Understand Pre-Match and Post-Match processes.';
$heroBoton1Texto = $page['hero_boton1_texto'] ?? 'Explore';
$heroBoton1Link  = $page['hero_boton1_link'] ?? '#overview';
$heroBoton2Texto = $page['hero_boton2_texto'] ?? 'Contact';
$heroBoton2Link  = $page['hero_boton2_link'] ?? '#';
$heroImagen      = !empty($page['hero_imagen']) ? '/' . ltrim($page['hero_imagen'], '/') : '';

function sectionValue($sections, $name, $field, $default = '')
{
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/match.css">

<!-- HERO -->
<section class="match-hero" style="background-image:url('<?php echo htmlspecialchars($heroImagen); ?>')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7 text-white">
                <span class="badge-custom">Match</span>
                <h1><?php echo htmlspecialchars($heroTitulo); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($heroSubtitulo)); ?></p>

                <div class="buttons">
                    <a href="<?php echo htmlspecialchars($heroBoton1Link); ?>" class="btn btn-primary">
                        <?php echo htmlspecialchars($heroBoton1Texto); ?>
                    </a>
                    <a href="<?php echo htmlspecialchars($heroBoton2Link); ?>" class="btn btn-outline-light">
                        <?php echo htmlspecialchars($heroBoton2Texto); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- OVERVIEW -->
<section class="section" id="overview">
    <div class="container text-center">
        <span class="chip">Overview</span>
        <h2><?php echo htmlspecialchars(sectionValue($sections, 'overview', 'titulo', 'Overview')); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(sectionValue($sections, 'overview', 'subtitulo', ''))); ?></p>

        <div class="row mt-5">
            <?php foreach($overviewItems as $item) { ?>
                <div class="col-md-3">
                    <div class="card-box">
                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- PRE MATCH -->
<section class="section bg-light" id="pre-match">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <span class="chip">Pre-Match</span>
                <h2><?php echo htmlspecialchars(sectionValue($sections, 'pre_match', 'titulo', 'Pre Match')); ?></h2>
                <p><?php echo nl2br(htmlspecialchars(sectionValue($sections, 'pre_match', 'subtitulo', ''))); ?></p>
            </div>

            <div class="col-lg-7">
                <?php $i=1; foreach($preMatchItems as $item) { ?>
                    <div class="timeline">
                        <span><?php echo $i++; ?></span>
                        <div>
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- POST MATCH -->
<section class="section" id="post-match">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <span class="chip">Post-Match</span>
                <h2><?php echo htmlspecialchars(sectionValue($sections, 'post_match', 'titulo', 'Post Match')); ?></h2>
                <p><?php echo nl2br(htmlspecialchars(sectionValue($sections, 'post_match', 'subtitulo', ''))); ?></p>
            </div>

            <div class="col-lg-7">
                <div class="row">
                    <?php foreach($postMatchItems as $item) { ?>
                        <div class="col-md-6">
                            <div class="card-box">
                                <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                                <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta text-center">
    <div class="container">
        <h2><?php echo htmlspecialchars(sectionValue($sections, 'cta', 'titulo', 'Next Step')); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(sectionValue($sections, 'cta', 'subtitulo', ''))); ?></p>
        <a href="/public/contact/" class="btn btn-warning">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>