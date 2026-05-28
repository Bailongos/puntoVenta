<?php
session_start();
require_once 'conexion.php';

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
        $stmt = $conn->prepare("SELECT id, usuario, nombre_completo, password, rol, activo FROM usuarios WHERE usuario = ? LIMIT 1");
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
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['id_usuario'] = $user['id'];

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

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css?v=2">
</head>
<body class="login-body">

    <main class="login-wrapper">

        <!-- Panel decorativo -->
        <section class="login-art">
            <div class="art-logo">
                <span>🛍️</span>
            </div>

            <div class="art-shape art-shape-1"></div>
            <div class="art-shape art-shape-2"></div>
            <div class="art-shape art-shape-3"></div>
            <div class="art-shape art-shape-4"></div>

            <div class="art-center-blob">
                <div class="art-center-icon">🏪</div>
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
                    <div style="background: #b91c1c; color: #fff; padding: 12px 16px; border-radius: 999px; margin-bottom: 10px; font-size: 14px; text-align: center;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
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

                    <div class="login-options">
                        <label class="remember-option">
                            <input type="checkbox" name="recordarme">
                            <span>Recordarme</span>
                        </label>

                        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="login-button">
                        Iniciar sesión
                    </button>
                </form>

                <div class="login-footer">
                    <p>Acceso exclusivo para usuarios autorizados</p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>
