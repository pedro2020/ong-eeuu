<?php
$current = $_SERVER['PHP_SELF'];

function active($path, $current)
{
    return strpos($current, $path) !== false ? 'active' : '';
}
?>

<nav id="sidebarMenu" class="col-lg-2 sidebar">

    <div class="sidebar-wrapper">

        <!-- PERFIL -->
        <div class="sidebar-user-box">
            <div class="sidebar-user-role">
                Panel Administrativo
            </div>
        </div>

        <!-- MENÚ -->
        <ul class="nav flex-column sidebar-menu">

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/index.php', $current); ?>" href="/admin/index.php">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/usuarios/', $current); ?>" href="/admin/usuarios/index.php">
                    <i class="bi bi-person-gear"></i>
                    Usuarios
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/team/', $current); ?>" href="/admin/team/index.php">
                    <i class="bi bi-people"></i>
                    Team
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/contact/', $current); ?>" href="/admin/contact/index.php">
                    <i class="bi bi-envelope"></i>
                    Contact
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/blocked-ips/', $current); ?>" href="/admin/blocked-ips/index.php">
                    <i class="bi bi-shield-exclamation"></i>
                    IPs bloqueadas
                </a>
            </li>

            <!-- CONTENIDO -->
            <li class="nav-item mt-3 px-3 text-secondary small">
                CONTENIDO
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/home.php', $current); ?>" href="/admin/content/home.php">
                    <i class="bi bi-house"></i>
                    Home
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/application.php', $current); ?>" href="/admin/content/application.php">
                    <i class="bi bi-file-text"></i>
                    Application
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/match.php', $current); ?>" href="/admin/content/match.php">
                    <i class="bi bi-diagram-3"></i>
                    Match
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/fellowship.php', $current); ?>" href="/admin/content/fellowship.php">
                    <i class="bi bi-mortarboard"></i>
                    Fellowship
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/interview.php', $current); ?>" href="/admin/content/interview.php">
                    <i class="bi bi-chat-dots"></i>
                    Interview
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/content/pathways.php', $current); ?>" href="/admin/content/pathways.php">
                    <i class="bi bi-signpost"></i>
                    Pathways
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= active('/admin/residency/index.php', $current); ?>" href="/admin/content/residency.php">
                    <i class="bi bi-hospital"></i>
                    Residency
                </a>
            </li>

        </ul>

        <!-- FOOTER SIDEBAR -->
        <div class="sidebar-footer">

            <div class="dropdown">
                <button class="btn sidebar-user-dropdown dropdown-toggle w-100" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?>
                </button>

                <ul class="dropdown-menu dropdown-menu-dark w-100">
                    <li>
                        <a class="dropdown-item" href="/admin/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>

        </div>

    </div>

</nav>

<!-- CONTENIDO -->
<main class="col-lg-10 px-md-4 py-4">