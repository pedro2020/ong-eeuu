<?php
session_start();

require_once __DIR__ . "/../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /admin/login.php");
    exit;
}

$usuario  = trim($_POST["usuario"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($usuario === "" || $password === "") {
    header("Location: /admin/login.php?error=1");
    exit;
}

$stmt = $conn->prepare("
    SELECT 
        id,
        usuario,
        password,
        nombre,
        rol,
        puede_crear,
        puede_editar,
        puede_eliminar,
        puede_ver_usuarios,
        estado
    FROM usuarios
    WHERE usuario = ?
    LIMIT 1
");

$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ((int)$user["estado"] === 1) {
        $passwordOk = false;

        if (password_verify($password, $user["password"])) {
            $passwordOk = true;
        }

        if ($password === $user["password"]) {
            $passwordOk = true;
        }

        if ($passwordOk) {
            session_regenerate_id(true);

            $_SESSION["admin_id"] = $user["id"];
            $_SESSION["admin_user"] = $user["usuario"];
            $_SESSION["admin_nombre"] = $user["nombre"];

            $_SESSION["rol"] = $user["rol"];
            $_SESSION["puede_crear"] = (int)$user["puede_crear"];
            $_SESSION["puede_editar"] = (int)$user["puede_editar"];
            $_SESSION["puede_eliminar"] = (int)$user["puede_eliminar"];
            $_SESSION["puede_ver_usuarios"] = (int)$user["puede_ver_usuarios"];

            header("Location: /admin/index.php");
            exit;
        }
    }
}

header("Location: /admin/login.php?error=1");
exit;