<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM application_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM application_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT ai.*, aps.nombre_seccion
    FROM application_items ai
    INNER JOIN application_sections aps ON aps.id = ai.section_id
    WHERE ai.estado = 1 AND aps.estado = 1
    ORDER BY aps.orden ASC, ai.orden ASC, ai.id ASC
");
if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$overviewItems = $itemsBySection['overview'] ?? [];
$personalItems = $itemsBySection['personal_statement'] ?? [];
$rotationItems = $itemsBySection['clinical_rotations'] ?? [];
$lettersItems = $itemsBySection['letters'] ?? [];
$researchItems = $itemsBySection['research'] ?? [];
$teachingItems = $itemsBySection['teaching'] ?? [];
$volunteerItems = $itemsBySection['volunteering'] ?? [];
$mspeItems = $itemsBySection['mspe'] ?? [];
$cvItems = $itemsBySection['cv'] ?? [];
$publicationsItems = $itemsBySection['publications'] ?? [];
$certificationsItems = $itemsBySection['certifications'] ?? [];
$membershipsItems = $itemsBySection['memberships'] ?? [];

$heroTitulo = $page['hero_titulo'] ?? 'Build a Strong Residency Application';
$heroSubtitulo = $page['hero_subtitulo'] ?? 'Create a competitive profile with the right documents, clinical experience, academic preparation, and supporting materials that strengthen your path to residency.';
$heroBoton1Texto = $page['hero_boton1_texto'] ?? 'Explore Sections';
$heroBoton1Link = $page['hero_boton1_link'] ?? '#overview';
$heroBoton2Texto = $page['hero_boton2_texto'] ?? 'Get in Touch';
$heroBoton2Link = $page['hero_boton2_link'] ?? '/public/contact/';
$heroImagen = !empty($page['hero_imagen']) ? '/' . ltrim($page['hero_imagen'], '/') : '';

function appSectionValue($sections, $name, $field, $default = '')
{
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/application.css">

<section class="application-hero"<?php if($heroImagen){ ?> style="background-image: linear-gradient(135deg, rgba(10,35,77,.88), rgba(16,60,133,.82), rgba(35,111,213,.78)), url('<?php echo htmlspecialchars($heroImagen); ?>'); background-size: cover; background-position: center;"<?php } ?>>
    <div class="application-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-7 text-white">
                <span class="application-badge">Residency Application</span>
                <h1 class="application-title"><?php echo htmlspecialchars($heroTitulo); ?></h1>
                <p class="application-text"><?php echo nl2br(htmlspecialchars($heroSubtitulo)); ?></p>

                <div class="application-buttons">
                    <?php if(!empty($heroBoton1Texto)) { ?>
                        <a href="<?php echo htmlspecialchars($heroBoton1Link); ?>" class="btn btn-primary btn-lg rounded-pill px-4">
                            <?php echo htmlspecialchars($heroBoton1Texto); ?>
                        </a>
                    <?php } ?>

                    <?php if(!empty($heroBoton2Texto)) { ?>
                        <a href="<?php echo htmlspecialchars($heroBoton2Link); ?>" class="btn btn-outline-light btn-lg rounded-pill px-4">
                            <?php echo htmlspecialchars($heroBoton2Texto); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-side-box">
                    <div class="hero-side-icon">
                        <img src="/images/icons/application.png" alt="Application">
                    </div>
                    <h3>Application Essentials</h3>
                    <ul>
                        <li>Personal Statement</li>
                        <li>Clinical Rotations</li>
                        <li>Letters of Recommendation</li>
                        <li>Research Experience</li>
                        <li>Curriculum Vitae</li>
                        <li>Certifications & Memberships</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="app-section bg-white" id="overview">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Overview</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(appSectionValue($sections, 'overview', 'titulo', 'Build Your Application')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(appSectionValue($sections, 'overview', 'subtitulo', 'Understand the key components that shape a strong and competitive residency application.'))); ?>
            </p>
        </div>

        <div class="row g-4">
            <?php foreach($overviewItems as $item) { ?>
                <div class="col-md-6 col-lg-4">
                    <div class="overview-card">
                        <div class="overview-icon">
                            <?php if(!empty($item['icono'])) { ?>
                                <img src="/<?php echo htmlspecialchars($item['icono']); ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>">
                            <?php } ?>
                        </div>
                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="app-section light-section">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <span class="section-chip">Personal Statement</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(appSectionValue($sections, 'personal_statement', 'titulo', 'Personal Statement')); ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(htmlspecialchars(appSectionValue($sections, 'personal_statement', 'subtitulo', 'Your story, motivation, and professional goals matter.'))); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="row g-4">
                    <?php foreach($personalItems as $item) { ?>
                        <div class="col-md-4">
                            <div class="mini-card h-100">
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

<section class="app-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Clinical Experience</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(appSectionValue($sections, 'clinical_rotations', 'titulo', 'Clinical Rotations')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(appSectionValue($sections, 'clinical_rotations', 'subtitulo', 'Hands-on clinical experience strengthens your profile.'))); ?>
            </p>
        </div>

        <div class="row g-4">
            <?php foreach($rotationItems as $item) { ?>
                <div class="col-md-4">
                    <div class="feature-card-app">
                        <div class="feature-icon-app">
                            <?php if(!empty($item['icono'])) { ?>
                                <img src="/<?php echo htmlspecialchars($item['icono']); ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>">
                            <?php } ?>
                        </div>
                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="app-section light-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <span class="section-chip">Letters of Recommendation</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(appSectionValue($sections, 'letters', 'titulo', 'Letters of Recommendation')); ?>
                </h2>

                <div class="list-stack">
                    <?php foreach($lettersItems as $item) { ?>
                        <div class="list-card">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-6">
                <span class="section-chip">Research Experience</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(appSectionValue($sections, 'research', 'titulo', 'Research Experience')); ?>
                </h2>

                <div class="list-stack">
                    <?php foreach($researchItems as $item) { ?>
                        <div class="list-card">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="app-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Teaching & Service</span>
            <h2 class="section-title">Leadership, mentorship, and social commitment</h2>
            <p class="section-subtitle">
                Teaching and volunteering help programs see your communication skills, initiative, and dedication beyond academics.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="dual-box">
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'teaching', 'titulo', 'Teaching Experience')); ?></h3>
                    <?php foreach($teachingItems as $item) { ?>
                        <div class="dual-item">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dual-box">
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'volunteering', 'titulo', 'Volunteering')); ?></h3>
                    <?php foreach($volunteerItems as $item) { ?>
                        <div class="dual-item">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="app-section light-section">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="small-panel">
                    <span class="section-chip sm-chip">MSPE</span>
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'mspe', 'titulo', 'Medical Student Performance Evaluation')); ?></h3>
                    <?php foreach($mspeItems as $item) { ?>
                        <p><strong><?php echo htmlspecialchars($item['titulo']); ?>:</strong> <?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="small-panel">
                    <span class="section-chip sm-chip">CV</span>
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'cv', 'titulo', 'Curriculum Vitae')); ?></h3>
                    <?php foreach($cvItems as $item) { ?>
                        <p><strong><?php echo htmlspecialchars($item['titulo']); ?>:</strong> <?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="small-panel">
                    <span class="section-chip sm-chip">Publications</span>
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'publications', 'titulo', 'Publications')); ?></h3>
                    <?php foreach($publicationsItems as $item) { ?>
                        <p><strong><?php echo htmlspecialchars($item['titulo']); ?>:</strong> <?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="small-panel">
                    <span class="section-chip sm-chip">Certifications</span>
                    <h3><?php echo htmlspecialchars(appSectionValue($sections, 'certifications', 'titulo', 'Certifications')); ?></h3>
                    <?php foreach($certificationsItems as $item) { ?>
                        <p><strong><?php echo htmlspecialchars($item['titulo']); ?>:</strong> <?php echo htmlspecialchars($item['descripcion']); ?></p>
                    <?php } ?>

                    <?php if(!empty($membershipsItems)) { ?>
                        <hr>
                        <h3 class="mt-3"><?php echo htmlspecialchars(appSectionValue($sections, 'memberships', 'titulo', 'Memberships')); ?></h3>
                        <?php foreach($membershipsItems as $item) { ?>
                            <p><strong><?php echo htmlspecialchars($item['titulo']); ?>:</strong> <?php echo htmlspecialchars($item['descripcion']); ?></p>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="application-cta">
    <div class="container text-center">
        <span class="section-chip white-chip">Next Step</span>
        <h2><?php echo htmlspecialchars(appSectionValue($sections, 'cta', 'titulo', 'Strengthen your profile step by step')); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(appSectionValue($sections, 'cta', 'subtitulo', 'Organize every part of your application and move forward with more confidence.'))); ?></p>
        <a href="/public/contact/" class="btn btn-warning btn-lg rounded-pill px-4">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>