<?php
include "../../includes/header.php";
require_once "../../config/db.php";

$success = "";
$error = "";

// ==========================================
// ANTI-SPAM / RATE LIMIT
// Cambiar minutos aquí si luego deseas modificarlo
$block_minutes = 5;
// ==========================================

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$today = date('Y-m-d H:i:s');

// Verificar IP bloqueada manualmente desde panel
$blockedIpQuery = $conn->prepare("SELECT id FROM blocked_ips WHERE ip_address = ? AND estado = 1 LIMIT 1");
$blockedIpQuery->bind_param("s", $ip);
$blockedIpQuery->execute();
$blockedIpResult = $blockedIpQuery->get_result();
if ($blockedIpResult && $blockedIpResult->num_rows > 0) {
    $error = "Your IP has been blocked. Please contact the administrator.";
}

// Verificar bloqueo temporal por envíos masivos
if (empty($error)) {
    $rateQuery = $conn->prepare("
        SELECT id, blocked_until 
        FROM contact_rate_limits 
        WHERE ip_address = ? 
        AND blocked_until IS NOT NULL 
        AND blocked_until > NOW()
        LIMIT 1
    ");
    $rateQuery->bind_param("s", $ip);
    $rateQuery->execute();
    $rateResult = $rateQuery->get_result();

    if ($rateResult && $rateResult->num_rows > 0) {
        $row = $rateResult->fetch_assoc();
        $error = "Too many attempts detected. Please wait a few minutes before trying again.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($error)) {
    $first_name   = trim($_POST['first_name'] ?? '');
    $last_name    = trim($_POST['last_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $country      = trim($_POST['country'] ?? '');
    $specialty    = trim($_POST['specialty'] ?? '');
    $inquiry_type = trim($_POST['inquiry_type'] ?? '');
    $message      = trim($_POST['message'] ?? '');
    $not_robot    = isset($_POST['not_robot']) ? 1 : 0;

    if (
        $first_name === '' ||
        $last_name === '' ||
        $email === '' ||
        $phone === '' ||
        $country === '' ||
        $specialty === '' ||
        $inquiry_type === '' ||
        $message === ''
    ) {
        $error = "Please complete all required fields before sending.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($not_robot !== 1) {
        $error = "Please confirm that you are not a robot.";
    } else {

        // revisar cantidad de intentos recientes por IP
        $attemptQuery = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM contact_messages
            WHERE ip_address = ?
            AND created_at >= (NOW() - INTERVAL 5 MINUTE)
        ");
        $attemptQuery->bind_param("s", $ip);
        $attemptQuery->execute();
        $attemptResult = $attemptQuery->get_result();
        $attemptData = $attemptResult->fetch_assoc();
        $attempts = (int)($attemptData['total'] ?? 0);

        // puedes cambiar este límite luego
        $max_attempts = 5;

        if ($attempts >= $max_attempts) {
            $blocked_until = date('Y-m-d H:i:s', strtotime("+{$block_minutes} minutes"));

            $insertRate = $conn->prepare("
                INSERT INTO contact_rate_limits (ip_address, blocked_until, estado, created_at)
                VALUES (?, ?, 1, NOW())
            ");
            $insertRate->bind_param("ss", $ip, $blocked_until);
            $insertRate->execute();

            $error = "Too many attempts detected. Please wait a few minutes before trying again.";
        } else {
            $stmt = $conn->prepare("
                INSERT INTO contact_messages
                (
                    first_name,
                    last_name,
                    email,
                    phone,
                    country,
                    specialty,
                    inquiry_type,
                    message,
                    ip_address,
                    estado,
                    created_at
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
            ");

            $stmt->bind_param(
                "sssssssss",
                $first_name,
                $last_name,
                $email,
                $phone,
                $country,
                $specialty,
                $inquiry_type,
                $message,
                $ip
            );

            if ($stmt->execute()) {
                $success = "Your message has been sent successfully.";
            } else {
                $error = "An error occurred while sending your message. Please try again.";
            }
        }
    }
}
?>

<link rel="stylesheet" href="/assets/css/contact.css">

<section class="contact-hero">
    <div class="overlay"></div>
    <div class="container position-relative">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-7 text-white">
                <span class="badge-custom">Contact</span>
                <h1>Contact Us</h1>
                <p>
                    We are here to support physicians, trainees, and applicants with guidance,
                    resources, and community connections.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="contact-card">
                    <h4>Email</h4>
                    <p>info@aapphealth.org</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <h4>Community</h4>
                    <p>Support, mentorship, and professional guidance.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <h4>Response</h4>
                    <p>We will review your message as soon as possible.</p>
                </div>
            </div>
        </div>

        <div class="contact-form-wrap">
            <div class="row g-5">
                <div class="col-lg-5">
                    <span class="chip">Get in Touch</span>
                    <h2>Send Us a Message</h2>
                    <p>
                        Fill out the form below and our team will get back to you.
                    </p>
                </div>

                <div class="col-lg-7">
                    <?php if (!empty($success)) { ?>
                        <div class="alert alert-success rounded-4">
                            <?php echo $success; ?>
                        </div>
                    <?php } ?>

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger rounded-4">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <form method="POST" id="contactForm" novalidate>
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone *</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country *</label>
                                <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Specialty *</label>
                                <input type="text" name="specialty" class="form-control" value="<?php echo htmlspecialchars($_POST['specialty'] ?? ''); ?>">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Inquiry Type *</label>
                                <select name="inquiry_type" class="form-select">
                                    <option value="">Select an option</option>
                                    <option value="Residency" <?php echo (($_POST['inquiry_type'] ?? '') === 'Residency') ? 'selected' : ''; ?>>Residency</option>
                                    <option value="Application" <?php echo (($_POST['inquiry_type'] ?? '') === 'Application') ? 'selected' : ''; ?>>Application</option>
                                    <option value="Interview" <?php echo (($_POST['inquiry_type'] ?? '') === 'Interview') ? 'selected' : ''; ?>>Interview</option>
                                    <option value="Match" <?php echo (($_POST['inquiry_type'] ?? '') === 'Match') ? 'selected' : ''; ?>>Match</option>
                                    <option value="Fellowship" <?php echo (($_POST['inquiry_type'] ?? '') === 'Fellowship') ? 'selected' : ''; ?>>Fellowship</option>
                                    <option value="Pathways" <?php echo (($_POST['inquiry_type'] ?? '') === 'Pathways') ? 'selected' : ''; ?>>Pathways</option>
                                    <option value="General" <?php echo (($_POST['inquiry_type'] ?? '') === 'General') ? 'selected' : ''; ?>>General</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Message *</label>
                                <textarea name="message" class="form-control" rows="6"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>

                            <div class="col-12">
                                <div class="robot-check">
                                    <input type="checkbox" id="not_robot" name="not_robot" value="1" <?php echo isset($_POST['not_robot']) ? 'checked' : ''; ?>>
                                    <label for="not_robot">I am not a robot</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div id="formMessage" class="small text-muted mb-3"></div>
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-send">
                                    Send Message
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../../includes/footer.php"; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("contactForm");
    const submitBtn = document.getElementById("submitBtn");
    const formMessage = document.getElementById("formMessage");

    form.addEventListener("submit", function (e) {
        const requiredFields = form.querySelectorAll("input[name='first_name'], input[name='last_name'], input[name='email'], input[name='phone'], input[name='country'], input[name='specialty'], select[name='inquiry_type'], textarea[name='message']");
        let hasEmpty = false;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                hasEmpty = true;
            }
        });

        const notRobot = document.getElementById("not_robot");

        if (hasEmpty) {
            e.preventDefault();
            formMessage.className = "small text-danger mb-3";
            formMessage.textContent = "Please complete all required fields before sending.";
            return;
        }

        if (!notRobot.checked) {
            e.preventDefault();
            formMessage.className = "small text-danger mb-3";
            formMessage.textContent = "Please confirm that you are not a robot.";
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = "Sending...";
        formMessage.className = "small text-primary mb-3";
        formMessage.textContent = "Your message is being sent. Please wait a moment.";
    });
});
</script>