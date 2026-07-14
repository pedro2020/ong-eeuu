<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$limite = 8;
$pagina = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$inicio = ($pagina - 1) * $limite;

$totalQuery = $conn->query("SELECT COUNT(*) as total FROM team WHERE estado = 1");
$total = 0;
if ($totalQuery && $filaTotal = $totalQuery->fetch_assoc()) {
    $total = (int)$filaTotal['total'];
}
$totalPaginas = max(1, ceil($total / $limite));

$sql = "SELECT * FROM team WHERE estado = 1 ORDER BY orden ASC, id ASC LIMIT $inicio, $limite";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../../assets/css/nosotros.css">

<section class="about-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="about-chip">About Us</span>
                <h1>Leadership Team</h1>
                <p>Meet the physicians leading AAPP and supporting the community.</p>
            </div>
        </div>
    </div>
</section>

<section class="about-wrap">
    <div class="container">
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-lg-6 col-md-6 col-12">
                        <article class="doctor-card h-100">

                            <div class="doctor-card-top">
                                <div class="doctor-photo-box">
                                    <img
src="/images/<?php echo htmlspecialchars($row['foto']); ?>"
                                        alt="<?php echo htmlspecialchars($row['nombre_completo']); ?>"
                                        class="doctor-photo">
                                </div>

                                <div class="doctor-main">
                                    <h3 class="doctor-name">
                                        <?php echo htmlspecialchars($row['nombre_completo']); ?>
                                    </h3>

                                    <?php if (!empty($row['titulo'])) { ?>
                                        <div class="doctor-degree"><?php echo htmlspecialchars($row['titulo']); ?></div>
                                    <?php } ?>

                                    <?php if (!empty($row['especialidad'])) { ?>
                                        <div class="doctor-role"><?php echo htmlspecialchars($row['especialidad']); ?></div>
                                    <?php } ?>

                                    <?php if (!empty($row['email'])) { ?>
                                        <div class="doctor-email">
                                            <?php echo htmlspecialchars($row['email']); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="doctor-card-body">
                                <?php if (!empty($row['bio_1'])) { ?>
                                    <p><?php echo nl2br(htmlspecialchars($row['bio_1'])); ?></p>
                                <?php } ?>

                                <?php if (!empty($row['bio_2'])) { ?>
                                    <p><?php echo nl2br(htmlspecialchars($row['bio_2'])); ?></p>
                                <?php } ?>

                                <?php if (!empty($row['bio_3'])) { ?>
                                    <p><?php echo nl2br(htmlspecialchars($row['bio_3'])); ?></p>
                                <?php } ?>

                                <?php if (!empty($row['bio_4'])) { ?>
                                    <p><?php echo nl2br(htmlspecialchars($row['bio_4'])); ?></p>
                                <?php } ?>
                            </div>

                            <div class="doctor-card-footer">

                                <?php if (!empty($row['twitter'])) { ?>
                                    <a href="<?= htmlspecialchars($row['twitter']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn social-x">
                                        <i class="fab fa-x-twitter"></i>
                                    </a>
                                <?php } ?>

                                <?php if (!empty($row['instagram'])) { ?>
                                    <a href="<?= htmlspecialchars($row['instagram']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn social-ig">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php } ?>

                                <?php if (!empty($row['facebook'])) { ?>
                                    <a href="<?= htmlspecialchars($row['facebook']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn social-fb">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                <?php } ?>

                                <?php if (!empty($row['linkedin'])) { ?>
                                    <a href="<?= htmlspecialchars($row['linkedin']); ?>" target="_blank" rel="noopener noreferrer" class="social-btn social-in">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                <?php } ?>

                            </div>

                        </article>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="empty-box">
                        No published team members yet.
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($totalPaginas > 1) { ?>
            <div class="custom-pagination">
                <?php if ($pagina > 1) { ?>
                    <a href="?page=<?php echo $pagina - 1; ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        ← Back
                    </a>
                <?php } ?>

                <span class="pagination-text">
                    Page <?php echo $pagina; ?> of <?php echo $totalPaginas; ?>
                </span>

                <?php if ($pagina < $totalPaginas) { ?>
                    <a href="?page=<?php echo $pagina + 1; ?>" class="btn btn-primary rounded-pill px-4 py-2">
                        Next →
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>