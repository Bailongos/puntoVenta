<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: menu.php');
    exit;
}

require_once 'conexion.php';

$paso = 1;
$usuario = '';
$pregunta = '';
$error = '';
$success = '';

// ── Paso 3: Cambiar contraseña ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'cambiar') {
    $username = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';

    if (!$password || !$confirmar) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($password !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuario = ?");
        $stmt->bind_param('ss', $hash, $username);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $success = 'Contraseña actualizada correctamente.';
            $paso = 0;
        } else {
            $error = 'Error al actualizar la contraseña.';
        }
        $stmt->close();
    }
    $usuario = $username;
}

// ── Paso 2: Verificar respuesta ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'verificar') {
    $username = trim($_POST['usuario'] ?? '');
    $respuesta = trim($_POST['respuesta'] ?? '');

    $stmt = $conn->prepare("SELECT respuesta_segura, pregunta_segura FROM usuarios WHERE usuario = ? AND activo = 1 LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['respuesta_segura'] && password_verify(strtolower(trim($respuesta)), $row['respuesta_segura'])) {
            $paso = 3;
            $usuario = $username;
            $pregunta = $row['pregunta_segura'];
        } else {
            $error = 'Respuesta incorrecta. Intenta de nuevo.';
            $paso = 2;
            $usuario = $username;
            $pregunta = $row['pregunta_segura'];
        }
    } else {
        $error = 'Usuario no encontrado.';
    }
    $stmt->close();
}

// ── Paso 1: Buscar usuario ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $username = trim($_POST['usuario'] ?? '');
    $stmt = $conn->prepare("SELECT pregunta_segura FROM usuarios WHERE usuario = ? AND activo = 1 LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['pregunta_segura']) {
            $paso = 2;
            $usuario = $username;
            $pregunta = $row['pregunta_segura'];
        } else {
            $error = 'Este usuario no tiene pregunta de seguridad configurada. Contacta al administrador.';
        }
    } else {
        $error = 'No se encontró un usuario activo con ese nombre.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Punto de Venta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css?v=4">
</head>
<body class="login-body">

    <main class="login-wrapper" style="grid-template-columns: 1fr;">

        <section class="login-panel" style="max-width:480px;margin:0 auto;">
            <div class="login-card">

                <div class="login-header">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,0.12);display:grid;place-items:center;">
                            <span class="material-icons" style="font-size:24px;color:#7dc6c7;">lock_reset</span>
                        </div>
                        <div>
                            <h1 style="font-size:28px;margin:0;">Recuperar contraseña</h1>
                            <p style="margin:2px 0 0;">
                                <?php if ($paso === 1): ?>Ingresa tu usuario para comenzar.
                                <?php elseif ($paso === 2): ?>Responde tu pregunta de seguridad.
                                <?php elseif ($paso === 3): ?>Elige una nueva contraseña.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert-success" style="background:rgba(34,197,94,0.15);color:#86efac;padding:12px 16px;border-radius:999px;margin-bottom:10px;font-size:14px;text-align:center;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($paso === 1): ?>
                <form method="POST" class="login-form">
                    <input type="hidden" name="accion" value="buscar">
                    <div class="form-group">
                        <label>Nombre de usuario</label>
                        <input type="text" name="usuario" class="form-control" placeholder="Ingresa tu usuario" required autofocus>
                    </div>
                    <button type="submit" class="login-button">Buscar cuenta</button>
                </form>

                <?php elseif ($paso === 2): ?>
                <form method="POST" class="login-form">
                    <input type="hidden" name="accion" value="verificar">
                    <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">
                    <div style="background:rgba(255,255,255,0.06);padding:16px;border-radius:16px;text-align:center;">
                        <p style="color:var(--login-muted);font-size:13px;margin:0 0 6px;">Pregunta de seguridad</p>
                        <p style="font-size:18px;font-weight:600;margin:0;"><?php echo htmlspecialchars($pregunta); ?></p>
                    </div>
                    <div class="form-group">
                        <label>Tu respuesta</label>
                        <input type="text" name="respuesta" class="form-control" placeholder="Escribe tu respuesta" required autofocus>
                    </div>
                    <button type="submit" class="login-button">Verificar respuesta</button>
                </form>

                <?php elseif ($paso === 3): ?>
                <form method="POST" class="login-form">
                    <input type="hidden" name="accion" value="cambiar">
                    <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">
                    <div class="form-group">
                        <label>Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="confirmar_password" class="form-control" placeholder="Repite la contraseña" required>
                    </div>
                    <button type="submit" class="login-button">Cambiar contraseña</button>
                </form>
                <?php endif; ?>

                <div class="login-footer">
                    <p><a href="login.php" style="color:#9ad9da;text-decoration:none;font-weight:600;">← Volver al inicio de sesión</a></p>
                </div>

            </div>
        </section>

    </main>

</body>
</html>
