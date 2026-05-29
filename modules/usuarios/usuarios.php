<?php
require_once __DIR__ . '/../../config/conexion.php';
require_once __DIR__ . '/../../config/funciones.php';

// ── Crear usuario ──
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre = trim($_POST['nombre_completo'] ?? '');
    $username = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol_id = (int)($_POST['rol_id'] ?? 0);
    $email = trim($_POST['email'] ?? '');
    $activo = (int)($_POST['activo'] ?? 1);

    if ($nombre && $username && $password && $rol_id) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, usuario, password, email, rol_id, activo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssii', $nombre, $username, $hash, $email, $rol_id, $activo);
        if ($stmt->execute()) {
            $mensaje = 'Usuario creado correctamente.';
        } else {
            $mensaje = 'Error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $mensaje = 'Todos los campos obligatorios deben llenarse.';
    }
}

// ── Alternar estado (baja/reactivar) ──
if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE usuarios SET activo = IF(activo=1, 0, 1) WHERE id = $id");
    header('Location: usuarios.php');
    exit;
}

// ── Datos para la vista ──
$usuarios = $conn->query("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u LEFT JOIN roles r ON u.rol_id = r.id ORDER BY u.id");
$roles = $conn->query("SELECT * FROM roles ORDER BY id");

$total_usuarios = $usuarios->num_rows;
$usuarios_activos = $conn->query("SELECT COUNT(*) AS c FROM usuarios WHERE activo = 1")->fetch_assoc()['c'];
$usuarios_inactivos = $conn->query("SELECT COUNT(*) AS c FROM usuarios WHERE activo = 0")->fetch_assoc()['c'];
$total_administradores = $conn->query("SELECT COUNT(*) AS c FROM usuarios WHERE rol_id = 1")->fetch_assoc()['c'];

$modulo_activo = 'usuarios';
$page_title = 'Usuarios';
$page_search = 'Buscar usuarios, roles o estados...';
$root_path = '../../';
require '../../includes/dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Administración</span>
        <h1>Usuarios</h1>
        <p>Administra las cuentas y accesos al sistema.</p>
    </div>
    <a href="#formulario-usuario" class="btn btn-primary" style="min-height:46px;"><span class="material-icons">person_add</span> Nuevo usuario</a>
</div>

<?php if ($mensaje): ?>
<div class="alert <?php echo strpos($mensaje, 'Error') === 0 ? 'alert-error' : 'alert-success'; ?>"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Total de usuarios</p>
            <h2><?php echo $total_usuarios; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">group</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Usuarios activos</p>
            <h2><?php echo $usuarios_activos; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">check_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Usuarios inactivos</p>
            <h2><?php echo $usuarios_inactivos; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">pause_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Administradores</p>
            <h2><?php echo $total_administradores; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">admin_panel_settings</span></div>
    </article>
</section>

<div class="flex-row">

    <div class="module-card" style="flex: 1.6;">
        <h3>Listado de usuarios</h3>
        <p style="color: var(--dash-muted); font-size: 13px; margin: 0 0 15px;">Consulta, edita, activa o desactiva cuentas del sistema.</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_usuarios === 0): ?>
                <tr>
                    <td colspan="6" class="empty-state">No hay usuarios registrados por el momento.</td>
                </tr>
                <?php else: ?>
                <?php $usuarios->data_seek(0); while ($u = $usuarios->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($u['usuario']); ?></strong></td>
                    <td><?php echo htmlspecialchars($u['nombre_completo']); ?></td>
                    <td><?php echo htmlspecialchars($u['email'] ?? '-'); ?></td>
                    <td><span class="badge badge-active"><?php echo htmlspecialchars($u['rol_nombre'] ?? 'Sin rol'); ?></span></td>
                    <td><span class="badge <?php echo $u['activo'] ? 'badge-active' : 'badge-inactive'; ?>"><?php echo $u['activo'] ? 'Activo' : 'Inactivo'; ?></span></td>
                    <td>
                        <a href="?toggle=1&id=<?php echo $u['id']; ?>" class="btn btn-sm <?php echo $u['activo'] ? 'btn-danger' : 'btn-success'; ?>"><?php echo $u['activo'] ? 'Dar de Baja' : 'Reactivar'; ?></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="module-card" style="flex: 1;" id="formulario-usuario">
        <h3>Nuevo usuario</h3>
        <p style="color: var(--dash-muted); font-size: 13px; margin: 0 0 15px;">Completa los campos para registrar un nuevo usuario.</p>

        <form method="POST">
            <input type="hidden" name="accion" value="crear">
            <div class="form-group">
                <label>Nombre completo</label>
                <input type="text" name="nombre_completo" class="form-control" placeholder="Nombre completo del usuario" required>
            </div>
            <div class="form-group">
                <label>Nombre de usuario</label>
                <input type="text" name="usuario" class="form-control" placeholder="Ej. cajero01" required>
            </div>
            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="usuario@ejemplo.com">
            </div>
            <div class="form-group">
                <label>Contraseña temporal</label>
                <input type="password" name="password" class="form-control" placeholder="Contraseña temporal" required>
            </div>
            <div class="form-group">
                <label>Rol</label>
                <select name="rol_id" class="form-control" required>
                    <option value="">Selecciona un rol</option>
                    <?php $roles->data_seek(0); while ($r = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="activo" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="flex-row mt-2">
                <button type="reset" class="btn btn-secondary"><span class="material-icons">clear</span> Limpiar</button>
                <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Guardar usuario</button>
            </div>
        </form>
    </div>

</div>

<?php require '../../includes/dashboard-footer.php'; ?>
