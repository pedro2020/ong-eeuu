<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$pageQuery = $conn->query("SELECT * FROM residency_page WHERE estado = 1 ORDER BY id DESC LIMIT 1");
$page = ($pageQuery && $pageQuery->num_rows > 0) ? $pageQuery->fetch_assoc() : null;

$sections = [];
$sectionsQuery = $conn->query("SELECT * FROM residency_sections WHERE estado = 1 ORDER BY orden ASC, id ASC");
if ($sectionsQuery) {
    while ($row = $sectionsQuery->fetch_assoc()) {
        $sections[$row['nombre_seccion']] = $row;
    }
}

$itemsBySection = [];
$itemsQuery = $conn->query("
    SELECT ri.*, rs.nombre_seccion 
    FROM residency_items ri
    INNER JOIN residency_sections rs ON rs.id = ri.section_id
    WHERE ri.estado = 1 AND rs.estado = 1
    ORDER BY rs.orden ASC, ri.orden ASC, ri.id ASC
");
if ($itemsQuery) {
    while ($row = $itemsQuery->fetch_assoc()) {
        $itemsBySection[$row['nombre_seccion']][] = $row;
    }
}

$overviewItems = $itemsBySection['overview'] ?? [];
$eligibilityItems = $itemsBySection['eligibility'] ?? [];
$requirementsItems = $itemsBySection['requirements'] ?? [];
$investmentItems = $itemsBySection['investment'] ?? [];
$usmleItems = $itemsBySection['usmle'] ?? [];

$heroTitulo = $page['hero_titulo'] ?? 'Your roadmap to medical residency in the United States';
$heroSubtitulo = $page['hero_subtitulo'] ?? 'Explore eligibility, requirements, investment, and preparation for Step 1, Step 2, and OET in a clear and structured path designed for future physicians.';
$heroBoton1Texto = $page['hero_boton1_texto'] ?? 'Start Here';
$heroBoton1Link = $page['hero_boton1_link'] ?? '#overview';
$heroBoton2Texto = $page['hero_boton2_texto'] ?? 'USMLE Exams';
$heroBoton2Link = $page['hero_boton2_link'] ?? '#usmle';
$heroImagen = !empty($page['hero_imagen']) ? '/' . ltrim($page['hero_imagen'], '/') : '';

function getSectionValue($sections, $name, $field, $default = '')
{
    return $sections[$name][$field] ?? $default;
}
?>

<link rel="stylesheet" href="/assets/css/residency.css">

<section class="residency-hero"<?php if($heroImagen){ ?> style="background-image: linear-gradient(135deg, rgba(11,36,84,.88), rgba(18,59,134,.82), rgba(44,116,216,.80)), url('<?php echo htmlspecialchars($heroImagen); ?>'); background-size: cover; background-position: center;"<?php } ?>>
    <div class="residency-hero-overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-7 text-white">
                <span class="residency-badge">Residency Pathway</span>
                <h1 class="residency-title"><?php echo htmlspecialchars($heroTitulo); ?></h1>
                <p class="residency-text"><?php echo nl2br(htmlspecialchars($heroSubtitulo)); ?></p>

                <div class="residency-buttons">
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
                <div class="hero-side-card">
                    <div class="hero-side-icon">
                        <img src="/images/icons/resources.png" alt="Residency">
                    </div>
                    <h3>Residency Essentials</h3>
                    <ul>
                        <li>Eligibility</li>
                        <li>Requirements</li>
                        <li>Investment</li>
                        <li>Step 1</li>
                        <li>Step 2</li>
                        <li>OET</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="res-section bg-white" id="overview">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Overview</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(getSectionValue($sections, 'overview', 'titulo', 'Medical Residency Application Process')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'overview', 'subtitulo', 'A high-level view of the pathway, from confirming eligibility to preparing for key exams and requirements.'))); ?>
            </p>
        </div>

        <div class="row g-4">
            <?php foreach($overviewItems as $item) { ?>
                <div class="col-md-3">
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

<section class="res-section light-section">
    <div class="container">
        <div class="row align-items-start g-4">
            <div class="col-lg-5">
                <span class="section-chip">Eligibility</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(getSectionValue($sections, 'eligibility', 'titulo', 'Start with the right foundation')); ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'eligibility', 'subtitulo', 'Before beginning the residency application pathway, it is important to verify institutional eligibility, gather the required documentation, and complete the registration process correctly.'))); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="row g-4">
                    <?php foreach($eligibilityItems as $item) { ?>
                        <div class="col-md-4">
                            <div class="info-card h-100">
                                <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                                <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                                <?php if(!empty($item['extra_1'])) { ?>
                                    <p class="mt-2 mb-0"><?php echo nl2br(htmlspecialchars($item['extra_1'])); ?></p>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="res-section bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">Requirements</span>
            <h2 class="section-title">
                <?php echo htmlspecialchars(getSectionValue($sections, 'requirements', 'titulo', 'Core elements of a strong application')); ?>
            </h2>
            <p class="section-subtitle">
                <?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'requirements', 'subtitulo', 'The residency process requires more than exams alone.'))); ?>
            </p>
        </div>

        <div class="requirements-wrap">
            <?php
            $reqCounter = 1;
            foreach($requirementsItems as $item) {
            ?>
                <div class="requirement-item">
                    <div class="requirement-number"><?php echo str_pad($reqCounter, 2, '0', STR_PAD_LEFT); ?></div>
                    <div class="requirement-content">
                        <h4><?php echo htmlspecialchars($item['titulo']); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($item['descripcion'])); ?></p>
                    </div>
                </div>
            <?php
                $reqCounter++;
            }
            ?>
        </div>
    </div>
</section>

<section class="res-section light-section" id="investment">
    <div class="container">
        <div class="row align-items-start g-5">
            <div class="col-lg-5">
                <span class="section-chip">Investment</span>
                <h2 class="section-title">
                    <?php echo htmlspecialchars(getSectionValue($sections, 'investment', 'titulo', 'Understand the financial side of preparation')); ?>
                </h2>
                <p class="section-text">
                    <?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'investment', 'subtitulo', 'Planning ahead helps you navigate exam costs and study resources with greater control.'))); ?>
                </p>
            </div>

            <div class="col-lg-7">
                <div class="investment-card">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Exam Step</th>
                                    <th>Estimated USD Fees</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($investmentItems as $item) { ?>
                                    <?php if(strtolower($item['titulo']) !== 'study tools') { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['titulo']); ?></td>
                                            <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="investment-tools">
                        <?php foreach($investmentItems as $item) { ?>
                            <?php if(strtolower($item['titulo']) === 'study tools') { 
                                $tools = explode(',', $item['descripcion']);
                                foreach($tools as $tool) {
                            ?>
                                <span class="tool-pill"><?php echo htmlspecialchars(trim($tool)); ?></span>
                            <?php
                                }
                            } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="res-section usmle-section" id="usmle">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-chip">USMLE & OET</span>
            <h2 class="section-title text-white">
                <?php echo htmlspecialchars(getSectionValue($sections, 'usmle', 'titulo', 'Preparation roadmap')); ?>
            </h2>
            <p class="section-subtitle text-white-50">
                <?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'usmle', 'subtitulo', 'Key information for Step 1, Step 2, and OET preparation.'))); ?>
            </p>
        </div>

        <?php
        $step1Main = null;
        $step1Topics = null;
        $step1Resources = null;
        $step2Main = null;
        $step2Areas = null;
        $oetMain = null;
        $oetComponents = null;
        $oetResources = null;

        foreach($usmleItems as $item){
            $titleLower = strtolower(trim($item['titulo']));
            if($titleLower === 'step 1') $step1Main = $item;
            if($titleLower === 'step 1 topics') $step1Topics = $item;
            if($titleLower === 'step 1 resources') $step1Resources = $item;
            if($titleLower === 'step 2') $step2Main = $item;
            if($titleLower === 'step 2 areas') $step2Areas = $item;
            if($titleLower === 'oet') $oetMain = $item;
            if($titleLower === 'oet components') $oetComponents = $item;
            if($titleLower === 'oet resources') $oetResources = $item;
        }

        function splitList($text) {
            $parts = array_filter(array_map('trim', explode(',', $text)));
            return $parts;
        }
        ?>

        <?php if($step1Main || $step1Topics || $step1Resources) { ?>
        <div class="exam-block">
            <div class="exam-head">
                <div>
                    <h3>Step 1</h3>
                    <p><?php echo htmlspecialchars($step1Main['descripcion'] ?? 'Foundational medical sciences and clinical interpretation'); ?></p>
                </div>
                <span class="exam-badge"><?php echo htmlspecialchars($step1Main['extra_1'] ?? '~8 hours / 280 questions'); ?></span>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="exam-card h-100">
                        <h4>Objective</h4>
                        <p><?php echo htmlspecialchars($step1Main['descripcion'] ?? 'Evaluates the basic sciences applied to medicine.'); ?></p>

                        <?php if(!empty($step1Main['extra_2'])) { ?>
                            <h4 class="mt-4">Where to take it</h4>
                            <p><?php echo htmlspecialchars($step1Main['extra_2']); ?></p>
                        <?php } ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="exam-card h-100">
                        <h4>Topics Included</h4>
                        <ul>
                            <?php foreach(splitList($step1Topics['descripcion'] ?? '') as $topic) { ?>
                                <li><?php echo htmlspecialchars($topic); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="exam-card h-100">
                        <h4>Top Resources</h4>
                        <ul>
                            <?php foreach(splitList($step1Resources['descripcion'] ?? '') as $resource) { ?>
                                <li><?php echo htmlspecialchars($resource); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if($step2Main || $step2Areas) { ?>
        <div class="exam-block">
            <div class="exam-head">
                <div>
                    <h3>Step 2</h3>
                    <p><?php echo htmlspecialchars($step2Main['descripcion'] ?? 'Clinical reasoning, diagnosis, and therapeutic management'); ?></p>
                </div>
                <span class="exam-badge"><?php echo htmlspecialchars($step2Main['extra_1'] ?? 'Clinical Focus'); ?></span>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="exam-card h-100">
                        <h4>Objective</h4>
                        <p><?php echo htmlspecialchars($step2Main['descripcion'] ?? 'Evaluates clinical reasoning, diagnostic management, and therapeutic decision-making.'); ?></p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="exam-card h-100">
                        <h4>Main Areas</h4>
                        <ul>
                            <?php foreach(splitList($step2Areas['descripcion'] ?? '') as $area) { ?>
                                <li><?php echo htmlspecialchars($area); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if($oetMain || $oetComponents || $oetResources) { ?>
        <div class="exam-block">
            <div class="exam-head">
                <div>
                    <h3>OET</h3>
                    <p><?php echo htmlspecialchars($oetMain['descripcion'] ?? 'English communication in a clinical context'); ?></p>
                </div>
                <span class="exam-badge"><?php echo htmlspecialchars($oetMain['extra_1'] ?? 'OET Medicine'); ?></span>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="exam-card h-100">
                        <h4>Components</h4>
                        <ul>
                            <?php foreach(splitList($oetComponents['descripcion'] ?? '') as $component) { ?>
                                <li><?php echo htmlspecialchars($component); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="exam-card h-100">
                        <h4>Recommended Resources</h4>
                        <ul>
                            <?php foreach(splitList($oetResources['descripcion'] ?? '') as $resource) { ?>
                                <li><?php echo htmlspecialchars($resource); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</section>

<section class="residency-cta">
    <div class="container text-center">
        <span class="section-chip white-chip">Next Step</span>
        <h2><?php echo htmlspecialchars(getSectionValue($sections, 'cta', 'titulo', 'Prepare with clarity and move forward with confidence')); ?></h2>
        <p><?php echo nl2br(htmlspecialchars(getSectionValue($sections, 'cta', 'subtitulo', 'Explore resources, strengthen your profile, and organize your residency journey one step at a time.'))); ?></p>
        <a href="/public/contact/" class="btn btn-warning btn-lg rounded-pill px-4">Contact Us</a>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>