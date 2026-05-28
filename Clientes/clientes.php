<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

$conn->query("CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_cliente VARCHAR(20) NOT NULL UNIQUE,
  nombre VARCHAR(255) NOT NULL,
  telefono VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  direccion TEXT DEFAULT NULL,
  estatus ENUM('activo','inactivo') DEFAULT 'activo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');

        if ($nombre) {
            $next_id = $conn->query("SELECT COALESCE(MAX(id),0)+1 AS n FROM clientes")->fetch_assoc()['n'];
            $codigo = 'CLI-' . str_pad($next_id, 3, '0', STR_PAD_LEFT);

            $stmt = $conn->prepare("INSERT INTO clientes (codigo_cliente, nombre, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('sssss', $codigo, $nombre, $telefono, $email, $direccion);
            if ($stmt->execute()) {
                $mensaje = 'Cliente registrado correctamente.';
            } else {
                $mensaje = 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = 'El nombre del cliente es obligatorio.';
        }
    }
}

if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE clientes SET estatus = IF(estatus='activo', 'inactivo', 'activo') WHERE id = $id");
    header('Location: clientes.php');
    exit;
}

$clientes = $conn->query("SELECT * FROM clientes ORDER BY id DESC");
$total = $clientes->num_rows;
$activos = $conn->query("SELECT COUNT(*) AS c FROM clientes WHERE estatus='activo'")->fetch_assoc()['c'];
$inactivos = $conn->query("SELECT COUNT(*) AS c FROM clientes WHERE estatus='inactivo'")->fetch_assoc()['c'];

$modulo_activo = 'clientes';
$page_title = 'Clientes';
$page_search = 'Buscar clientes...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Directorio</span>
        <h1>Clientes</h1>
        <p>Registro oficial de clientes frecuentes o comerciales.</p>
    </div>
</div>

<?php if ($mensaje): ?>
<div class="alert <?php echo strpos($mensaje, 'Error') === 0 ? 'alert-error' : 'alert-success'; ?>"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Total de clientes</p>
            <h2><?php echo $total; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">group</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Clientes activos</p>
            <h2><?php echo $activos; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">check_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Clientes inactivos</p>
            <h2><?php echo $inactivos; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">pause_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Con email registrado</p>
            <h2><?php echo $conn->query("SELECT COUNT(*) AS c FROM clientes WHERE email IS NOT NULL AND email != ''")->fetch_assoc()['c']; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">mail</span></div>
    </article>
</section>

<div class="module-card">
    <h3>Formulario de Registro</h3>
    <br>
    <form method="POST" class="flex-row" style="align-items: flex-end;">
        <input type="hidden" name="accion" value="crear">
        <div class="form-group" style="flex: 2; min-width: 250px;">
            <label>Nombre del Cliente:</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre completo o Razón Social" required>
        </div>
        <div class="form-group" style="flex: 1; min-width: 180px;">
            <label>Teléfono de Contacto:</label>
            <input type="text" name="telefono" class="form-control" placeholder="Ej. 8717000000">
        </div>
        <div class="form-group" style="flex: 1; min-width: 200px;">
            <label>Correo electrónico:</label>
            <input type="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
        </div>
        <div class="flex-row" style="margin-bottom:15px;">
            <button type="submit" class="btn btn-primary"><span class="material-icons">person_add</span> Registrar Cliente</button>
        </div>
    </form>
</div>

<div class="module-card">
    <h3>Clientes Registrados</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre Completo</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total === 0): ?>
            <tr>
                <td colspan="6" class="empty-state">No hay clientes registrados.</td>
            </tr>
            <?php else: ?>
            <?php $clientes->data_seek(0); while ($c = $clientes->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($c['codigo_cliente']); ?></td>
                <td><?php echo htmlspecialchars($c['nombre']); ?></td>
                <td><?php echo htmlspecialchars($c['telefono'] ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($c['email'] ?? '-'); ?></td>
                <td><span class="badge <?php echo $c['estatus'] === 'activo' ? 'badge-active' : 'badge-inactive'; ?>"><?php echo ucfirst($c['estatus']); ?></span></td>
                <td>
                    <a href="?toggle=1&id=<?php echo $c['id']; ?>" class="btn btn-sm <?php echo $c['estatus'] === 'activo' ? 'btn-danger' : 'btn-success'; ?>"><?php echo $c['estatus'] === 'activo' ? 'Dar de Baja' : 'Reactivar'; ?></a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
