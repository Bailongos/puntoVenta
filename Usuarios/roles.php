<?php
require_once __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        if ($nombre) {
            $stmt = $conn->prepare("INSERT INTO roles (nombre, descripcion) VALUES (?, ?)");
            $stmt->bind_param('ss', $nombre, $descripcion);
            $stmt->execute();
        }
    } elseif ($_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $conn->query("DELETE FROM roles WHERE id = $id");
        if ($conn->affected_rows === 0) {
            header('Location: roles.php?error=1');
            exit;
        }
    }
    header('Location: roles.php');
    exit;
}

$error_msg = isset($_GET['error']);

$roles = $conn->query("SELECT r.*, (SELECT COUNT(*) FROM usuarios WHERE rol_id = r.id) AS total_usuarios FROM roles r ORDER BY r.id");

$modulo_activo = 'roles';
$page_title = 'Roles';
$page_search = 'Buscar roles...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Administración</span>
        <h1>Roles</h1>
        <p>Gestión de roles del sistema. Define los perfiles de acceso disponibles.</p>
    </div>
</div>

<?php if ($error_msg): ?>
<div class="alert alert-error">No se puede eliminar el rol porque tiene usuarios asignados.</div>
<?php endif; ?>

<div class="module-card">
    <h3>Registrar Rol</h3>
    <br>
    <form method="POST" class="flex-row" style="align-items: flex-end;">
        <input type="hidden" name="accion" value="crear">
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label>Nombre del Rol:</label>
            <input type="text" name="nombre" class="form-control" placeholder="Ej. Supervisor" required>
        </div>
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label>Descripción:</label>
            <input type="text" name="descripcion" class="form-control" placeholder="Ej. Acceso a reportes e inventario">
        </div>
        <div class="flex-row" style="margin-bottom:15px;">
            <button type="submit" class="btn btn-primary"><span class="material-icons">add</span> Crear rol</button>
        </div>
    </form>
</div>

<div class="module-card">
    <h3>Roles registrados</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Rol</th>
                <th>Descripción</th>
                <th>Usuarios asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $roles->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><span class="badge badge-active"><?php echo htmlspecialchars($row['nombre']); ?></span></td>
                <td><?php echo htmlspecialchars($row['descripcion'] ?? '-'); ?></td>
                <td><?php echo $row['total_usuarios']; ?></td>
                <td>
                    <?php if ($row['id'] !== 1): ?>
                    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este rol? Los usuarios con este rol quedarán sin rol asignado.')">
                        <input type="hidden" name="accion" value="eliminar">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><span class="material-icons">delete</span> Eliminar</button>
                    </form>
                    <?php else: ?>
                    <span style="color:var(--dash-muted);font-size:13px;">— Protegido</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
