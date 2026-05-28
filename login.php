<?php
session_start();
require_once 'conexion.php';
require_once 'funciones.php';

if (isset($_SESSION['usuario'])) {
    header('Location: menu.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $stmt = $conn->prepare("SELECT id, usuario, nombre_completo, password, rol_id, activo, email FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (!$user['activo']) {
                $error = 'Esta cuenta está desactivada. Contacta al administrador.';
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['nombre_completo'] = $user['nombre_completo'];
                $_SESSION['id_usuario'] = $user['id'];
                $_SESSION['rol_id'] = $user['rol_id'];

                // Obtener nombre del rol
                $rol_stmt = $conn->prepare("SELECT nombre FROM roles WHERE id = ? LIMIT 1");
                $rol_stmt->bind_param('i', $user['rol_id']);
                $rol_stmt->execute();
                $rol_row = $rol_stmt->get_result()->fetch_assoc();
                $rol_nombre = $rol_row['nombre'] ?? 'Sin rol';
                $_SESSION['rol_nombre'] = $rol_nombre;
                $_SESSION['rol'] = $rol_nombre; // compatibilidad

                // Cargar permisos del rol
                $perm_stmt = $conn->prepare("SELECT p.nombre FROM permisos p JOIN rol_permisos rp ON p.id = rp.permiso_id WHERE rp.rol_id = ?");
                $perm_stmt->bind_param('i', $user['rol_id']);
                $perm_stmt->execute();
                $perm_result = $perm_stmt->get_result();
                $permisos = [];
                while ($perm_row = $perm_result->fetch_assoc()) {
                    $permisos[] = $perm_row['nombre'];
                }
                $_SESSION['permisos'] = $permisos;

                header('Location: menu.php');
                exit;
            } else {
                $error = 'Contraseña incorrecta.';
            }
        } else {
            $error = 'El usuario no existe.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Punto de Venta</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css?v=4">
</head>
<body class="login-body">

    <main class="login-wrapper">

        <!-- Panel decorativo -->
        <section class="login-art">
            <div class="art-logo">
                <span class="material-icons" style="font-size:30px;">storefront</span>
            </div>

            <div class="art-shape art-shape-1"></div>
            <div class="art-shape art-shape-2"></div>
            <div class="art-shape art-shape-3"></div>
            <div class="art-shape art-shape-4"></div>

            <div class="art-center-blob">
                <div class="art-center-icon"><span class="material-icons" style="font-size:52px;">point_of_sale</span></div>
            </div>
        </section>

        <!-- Card del login -->
        <section class="login-panel">
            <div class="login-card">
                <div class="login-header">
                    <h1>Login</h1>
                    <p>Accede con tus credenciales al sistema</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST" class="login-form">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            class="form-control"
                            placeholder="Ingresa tu usuario"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Ingresa tu contraseña"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-button">
                        Iniciar sesión
                    </button>
                </form>

                <div class="login-links">
                    <a href="register.php" class="login-link-item"><span class="material-icons">person_add</span> Crear cuenta</a>
                    <a href="recuperar.php" class="login-link-item"><span class="material-icons">lock_reset</span> ¿Olvidaste tu contraseña?</a>
                </div>

                <div class="login-footer">
                    <p>Acceso exclusivo para usuarios autorizados</p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>
