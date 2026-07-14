<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM interview_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM interview_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT ii.*, ins.nombre_seccion
    FROM interview_items ii
    INNER JOIN interview_sections ins ON ins.id = ii.section_id
    WHERE ii.estado = 1 AND ins.estado = 1
    ORDER BY ins.orden ASC, ii.orden ASC, ii.id ASC
");
if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$overviewItems = $itemsBySection['overview'] ?? [];
$tipsItems = $itemsBySection['tips'] ?? [];
$questionsItems = $itemsBySection['common_questions'] ?? [];
$lettersItems = $itemsBySection['letters'] ?? [];

$heroTitulo = $page['hero_titulo'] ?? 'Master Your Residency Interview';
$heroSubtitulo = $page['hero_subtitulo'] ?? 'Prepare with confidence, structure your answers, and stand out during the interview process with clarity, professionalism, and authenticity.';
$heroBoton1Texto = $page['hero_boton1_texto'] ?? 'Prepare Now';
$heroBoton1Link = $page['hero_boton1_link'] ?? '#tips';
$heroBoton2Texto = $page['hero_boton2_texto'] ?? 'Contact Us';
$heroBoton2Link = $page['hero_boton2_link'] ?? '/public/contact/';
$heroImagen = !empty($page['hero_imagen']) ? '/' . ltrim($page['hero_imagen'], '/') : '';

function interviewSectionValue($sections, $name, $field, $default = '')
{
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/interview.css">

<section class="interview-hero"<?php if($heroImagen){ ?> style="background-image: linear-gradient(135deg, rgba(10,32,78,.88), rgba(25,72,155,.82), rgba(43,116,216,.78)), url('<?php echo htmlspecialchars($heroImagen); ?>'); background-size: cover; background-position: center;"<?php } ?>>
    <div class="interview-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-7 text-white">
                <span class="interview-badge">Interview Season</span>
                <h1 class="interview-title"><?php echo htmlspecialchars($heroTitulo); ?></h1>
                <p class="interview-text"><?php echo nl2br(htmlspecialchars($heroSubtitulo)); ?></p>

                <div class="interview-buttons">
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
                <div class="hero-side-box-int">
                    <div class="hero-side-icon-int">
                        <img src="/images/icons/interview.png" alt="Interview">
                    </div>
                    <h3>Interview Essentials</h3>
                    <ul>
                        <li>Preparation</li>
                        <li>Mock Practice</li>
                        <li>Common Questions</li>
                        <li>Communication</li>
                        <li>Professionalism</li>
                        <li>Letters After Interview</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="int-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Overview</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(interviewSectionValue($sections, 'overview', 'titulo', 'Interview Preparation')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(interviewSectionValue($sections, 'overview', 'subtitulo', 'Understand what programs expect and how to present your best version.'))); ?>
            </p>
        </div>

        <div class="row g-4">
            <?php foreach($overviewItems as $item) { ?>
                <div class="col-md-4">
                    <div class="overview-card-int">
                        <div class="overview-icon-int">
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

<section class="int-section light-section" id="tips">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <span class="section-chip">Interview Tips</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(interviewSectionValue($sections, 'tips', 'titulo', 'Interview Tips')); ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(htmlspecialchars(interviewSectionValue($sections, 'tips', 'subtitulo', 'Key strategies to succeed during interviews.'))); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="tips-grid">
                    <?php foreach($tipsItems as $item) { ?>
                        <div class="tip-card">
                            <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                            <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="int-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Common Questions</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(interviewSectionValue($sections, 'common_questions', 'titulo', 'Common Questions')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(interviewSectionValue($sections, 'common_questions', 'subtitulo', 'Prepare strong answers for frequently asked questions.'))); ?>
            </p>
        </div>

        <div class="questions-wrap">
            <?php
            $qNum = 1;
            foreach($questionsItems as $item) {
            ?>
                <div class="question-item">
                    <div class="question-number"><?php echo str_pad($qNum, 2, '0', STR_PAD_LEFT); ?></div>
                    <div class="question-content">
                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                    </div>
                </div>
            <?php
                $qNum++;
            }
            ?>
        </div>
    </div>
</section>

<section class="int-section light-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <span class="section-chip">Post-Interview Letters</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(interviewSectionValue($sections, 'letters', 'titulo', 'Post-Interview Letters')); ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(htmlspecialchars(interviewSectionValue($sections, 'letters', 'subtitulo', 'Letters of interest and intent after interviews.'))); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="row g-4">
                    <?php foreach($lettersItems as $item) { ?>
                        <div class="col-md-4">
                            <div class="letter-card">
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

<section class="interview-cta">
    <div class="container text-center">
        <span class="section-chip white-chip">Final Step</span>
        <h2><?php echo htmlspecialchars(interviewSectionValue($sections, 'cta', 'titulo', 'Be ready to stand out')); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(interviewSectionValue($sections, 'cta', 'subtitulo', 'Preparation and confidence make the difference.'))); ?></p>
        <a href="/public/contact/" class="btn btn-warning btn-lg rounded-pill px-4">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>