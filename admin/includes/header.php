<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm topbar">
        <div class="container-fluid px-3">

            <button id="toggleSidebar" class="btn d-lg-none border-0 me-2" type="button" aria-label="Abrir menú">
                <i class="bi bi-list fs-2"></i>
            </button>

            <a class="navbar-brand d-flex align-items-center m-0" href="/admin/index.php">
                <img src="/images/logo.png" class="topbar-logo" alt="Logo">
            </a>

            <div class="ms-auto d-flex align-items-center">
                <span class="topbar-user">
                    <?= htmlspecialchars($_SESSION["admin_user"] ?? "Administrador"); ?>
                </span>
            </div>

        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">