<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM pathways_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM pathways_sections WHERE estado = 1 ORDER BY orden ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT pi.*, ps.nombre_seccion
    FROM pathways_items pi
    INNER JOIN pathways_sections ps ON ps.id = pi.section_id
    WHERE pi.estado = 1 AND ps.estado = 1
    ORDER BY ps.orden ASC, pi.orden ASC
");

if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$pathwayItems = $itemsBySection['pathways'] ?? [];

function sectionValue($sections, $name, $field, $default = ''){
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/pathways.css">

<!-- HERO -->
<section class="pathways-hero" style="background-image:url('/<?php echo $page['hero_imagen'] ?? ''; ?>')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7 text-white">
                <span class="badge-custom">Pathways</span>
                <h1><?php echo $page['hero_titulo'] ?? 'Pathways to Practice in the US'; ?></h1>
                <p><?php echo $page['hero_subtitulo'] ?? ''; ?></p>

                <div class="buttons">
                    <a href="<?php echo $page['hero_btn1_link'] ?? '#'; ?>" class="btn btn-primary">
                        <?php echo $page['hero_btn1_text'] ?? 'Explore'; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PATHWAYS -->
<section class="section">
    <div class="container text-center">
        <span class="chip">Options</span>
        <h2><?php echo sectionValue($sections,'pathways','titulo','Career Pathways'); ?></h2>
        <p><?php echo sectionValue($sections,'pathways','subtitulo',''); ?></p>

        <div class="row mt-5">
            <?php foreach($pathwayItems as $item){ ?>
            <div class="col-md-3">
                <div class="card-box">
                    <h4><?php echo $item['titulo']; ?></h4>
                    <p><?php echo $item['descripcion']; ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta text-center">
    <div class="container">
        <h2><?php echo sectionValue($sections,'cta','titulo','Explore your career options'); ?></h2>
        <p><?php echo sectionValue($sections,'cta','subtitulo',''); ?></p>
        <a href="/public/contact/" class="btn btn-warning">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>