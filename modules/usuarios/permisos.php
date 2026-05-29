<?php
require_once __DIR__ . '/../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM rol_permisos");
    $roles_post = $_POST['permisos'] ?? [];
    foreach ($roles_post as $rol_id => $permisos_array) {
        $rol_id = (int)$rol_id;
        foreach ($permisos_array as $permiso_id) {
            $permiso_id = (int)$permiso_id;
            $conn->query("INSERT INTO rol_permisos (rol_id, permiso_id) VALUES ($rol_id, $permiso_id)");
        }
    }
    header('Location: permisos.php?saved=1');
    exit;
}

$roles = $conn->query("SELECT * FROM roles ORDER BY id");
$permisos = $conn->query("SELECT * FROM permisos ORDER BY id");

$rp_result = $conn->query("SELECT rol_id, permiso_id FROM rol_permisos");
$permisos_por_rol = [];
while ($row = $rp_result->fetch_assoc()) {
    $permisos_por_rol[$row['rol_id']][] = $row['permiso_id'];
}

$modulo_activo = 'permisos';
$page_title = 'Permisos';
$page_search = 'Buscar permisos...';
$root_path = '../';
require '../dashboard-header.php';

$saved = isset($_GET['saved']);
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Administración</span>
        <h1>Permisos</h1>
        <p>Matriz de accesos. Define qué módulos puede ver cada rol del sistema.</p>
    </div>
</div>

<?php if ($saved): ?>
<div class="alert alert-success">Permisos guardados correctamente.</div>
<?php endif; ?>

<div class="module-card">
    <h3>Asignación de permisos por rol</h3>
    <p style="color: var(--dash-muted); font-size: 13px; margin: -5px 0 15px;">Marca los módulos a los que cada rol tendrá acceso.</p>

    <form method="POST">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Módulo</th>
                    <?php
                    $roles->data_seek(0);
                    while ($rol = $roles->fetch_assoc()):
                    ?>
                    <th style="text-align:center;"><?php echo htmlspecialchars($rol['nombre']); ?></th>
                    <?php endwhile; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $roles->data_seek(0);
                $roles_array = [];
                while ($rol = $roles->fetch_assoc()) {
                    $roles_array[] = $rol;
                }
                $permisos->data_seek(0);
                while ($perm = $permisos->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($perm['descripcion'] ?: $perm['nombre']); ?></td>
                    <?php foreach ($roles_array as $rol): 
                        $checked = in_array($perm['id'], $permisos_por_rol[$rol['id']] ?? []);
                    ?>
                    <td style="text-align:center;">
                        <input type="checkbox" name="permisos[<?php echo $rol['id']; ?>][]" value="<?php echo $perm['id']; ?>" <?php echo $checked ? 'checked' : ''; ?>>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="flex-row mt-2">
            <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Guardar cambios</button>
        </div>
    </form>
</div>

<?php require '../dashboard-footer.php'; ?>
