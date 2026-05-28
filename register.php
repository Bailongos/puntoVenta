<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: menu.php');
    exit;
}

require_once 'conexion.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_completo'] ?? '');
    $username = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar = $_POST['confirmar_password'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $pregunta = trim($_POST['pregunta_segura'] ?? '');
    $respuesta = trim($_POST['respuesta_segura'] ?? '');

    if (!$nombre || !$username || !$email || !$password || !$confirmar || !$pregunta || !$respuesta) {
        $error = 'Todos los campos son obligatorios.';
    } elseif ($password !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo electrónico inválido.';
    } else {
        $check = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ? LIMIT 1");
        $check->bind_param('ss', $username, $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'El nombre de usuario o correo ya está registrado.';
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $hash_resp = password_hash(strtolower(trim($respuesta)), PASSWORD_DEFAULT);
            $rol_id = 3; // Cajero por defecto
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, usuario, email, password, telefono, pregunta_segura, respuesta_segura, rol_id, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
            $stmt->bind_param('sssssssi', $nombre, $username, $email, $hash_pass, $telefono, $pregunta, $hash_resp, $rol_id);
            if ($stmt->execute()) {
                $success = 'Cuenta creada correctamente. Ahora puedes iniciar sesión.';
            } else {
                $error = 'Error al crear la cuenta: ' . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Punto de Venta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css?v=4">
</head>
<body class="login-body">

    <main class="login-wrapper" style="grid-template-columns: 1fr;">

        <section class="login-panel" style="max-width:520px;margin:0 auto;">
            <div class="login-card">

                <div class="login-header">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,0.12);display:grid;place-items:center;">
                            <span class="material-icons" style="font-size:24px;color:#7dc6c7;">person_add</span>
                        </div>
                        <div>
                            <h1 style="font-size:28px;margin:0;">Crear cuenta</h1>
                            <p style="margin:2px 0 0;">Regístrate para acceder al sistema</p>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert-success" style="background:rgba(34,197,94,0.15);color:#86efac;padding:12px 16px;border-radius:999px;margin-bottom:10px;font-size:14px;text-align:center;"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form method="POST" class="login-form">

                    <div class="form-group">
                        <label>Nombre completo</label>
                        <input type="text" name="nombre_completo" class="form-control" placeholder="Tu nombre completo" required>
                    </div>

                    <div class="flex-row" style="gap:12px;">
                        <div class="form-group" style="flex:1;">
                            <label>Usuario</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Ej. cajero01" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Correo electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>

                    <div class="flex-row" style="gap:12px;">
                        <div class="form-group" style="flex:1;">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Confirmar contraseña</label>
                            <input type="password" name="confirmar_password" class="form-control" placeholder="Repite la contraseña" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Teléfono (opcional)</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej. 8717000000">
                    </div>

                    <hr style="border:none;border-top:1px solid rgba(255,255,255,0.08);margin:8px 0;">

                    <p style="color:var(--login-muted);font-size:13px;margin:0 0 8px;">Pregunta de seguridad (para recuperar tu contraseña)</p>

                    <div class="flex-row" style="gap:12px;">
                        <div class="form-group" style="flex:1;">
                            <label>Pregunta secreta</label>
                            <select name="pregunta_segura" class="form-control" required>
                                <option value="">Selecciona una pregunta</option>
                                <option value="¿Cuál es el nombre de tu mascota?">¿Cuál es el nombre de tu mascota?</option>
                                <option value="¿Cuál es tu ciudad favorita?">¿Cuál es tu ciudad favorita?</option>
                                <option value="¿Cuál es el nombre de tu mejor amigo?">¿Cuál es el nombre de tu mejor amigo?</option>
                                <option value="¿Cuál fue tu materia favorita en la escuela?">¿Cuál fue tu materia favorita en la escuela?</option>
                                <option value="¿Cuál es tu película favorita?">¿Cuál es tu película favorita?</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex:1;">
                            <label>Respuesta</label>
                            <input type="text" name="respuesta_segura" class="form-control" placeholder="Tu respuesta" required>
                        </div>
                    </div>

                    <button type="submit" class="login-button">Crear cuenta</button>
                </form>

                <div class="login-footer">
                    <p>¿Ya tienes cuenta? <a href="login.php" style="color:#9ad9da;text-decoration:none;font-weight:600;">Inicia sesión</a></p>
                </div>

            </div>
        </section>

    </main>

</body>
</html>
