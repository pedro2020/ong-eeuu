<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: /admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>

    <link href="../assets/css/login.css" rel="stylesheet">
</head>

<body>

    <div class="login-container">

        <div class="login-left"></div>

        <div class="login-right">
            <div class="login-box">

                <img src="/images/logo.png" alt="Logo" class="login-logo">

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        Credenciales incorrectas.
                    </div>
                <?php endif; ?>

                <form method="POST" action="/admin/login_process.php">

                    <div class="login-form-group">
                        <input
                            type="text"
                            name="usuario"
                            class="form-control"
                            placeholder="Usuario"
                            required>
                    </div>

                    <div class="login-form-group">
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Contraseña"
                            required>
                    </div>

                    <button type="submit" class="btn-login">
                        Ingresar
                    </button>

                </form>

            </div>
        </div>

    </div>

</body>

</html>