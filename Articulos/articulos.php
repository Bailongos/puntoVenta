<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

$conn->query("CREATE TABLE IF NOT EXISTS articulos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo_barras VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NOT NULL,
  precio_venta DECIMAL(10,2) DEFAULT 0,
  precio_compra DECIMAL(10,2) DEFAULT 0,
  stock_actual INT DEFAULT 0,
  imagen VARCHAR(255) DEFAULT NULL,
  estatus ENUM('alta','baja') DEFAULT 'alta',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $codigo = trim($_POST['codigo_barras'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio_venta = floatval($_POST['precio_venta'] ?? 0);
        $precio_compra = floatval($_POST['precio_compra'] ?? 0);
        $stock = intval($_POST['stock_actual'] ?? 0);

        if ($codigo && $descripcion) {
            $stmt = $conn->prepare("INSERT INTO articulos (codigo_barras, descripcion, precio_venta, precio_compra, stock_actual) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssddi', $codigo, $descripcion, $precio_venta, $precio_compra, $stock);
            if ($stmt->execute()) {
                $mensaje = 'Artículo creado correctamente.';
            } else {
                $mensaje = 'Error: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $mensaje = 'Código de barras y descripción son obligatorios.';
        }
    }
}

if (isset($_GET['toggle']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("UPDATE articulos SET estatus = IF(estatus='alta', 'baja', 'alta') WHERE id = $id");
    header('Location: articulos.php');
    exit;
}

$articulos = $conn->query("SELECT * FROM articulos ORDER BY id DESC");
$total = $articulos->num_rows;
$activos = $conn->query("SELECT COUNT(*) AS c FROM articulos WHERE estatus='alta'")->fetch_assoc()['c'];
$inactivos = $conn->query("SELECT COUNT(*) AS c FROM articulos WHERE estatus='baja'")->fetch_assoc()['c'];

$modulo_activo = 'articulos';
$page_title = 'Artículos';
$page_search = 'Buscar artículos...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Catálogo</span>
        <h1>Artículos</h1>
        <p>Administración de productos del sistema (Altas, Bajas lógicas y Cambios).</p>
    </div>
</div>

<?php if ($mensaje): ?>
<div class="alert <?php echo strpos($mensaje, 'Error') === 0 ? 'alert-error' : 'alert-success'; ?>"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Total de artículos</p>
            <h2><?php echo $total; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">inventory_2</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Artículos activos</p>
            <h2><?php echo $activos; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">check_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Artículos inactivos</p>
            <h2><?php echo $inactivos; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">pause_circle</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>En existencia</p>
            <h2><?php echo $conn->query("SELECT COALESCE(SUM(stock_actual),0) AS c FROM articulos WHERE estatus='alta'")->fetch_assoc()['c']; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">package_2</span></div>
    </article>
</section>

<div class="flex-row">

    <div class="module-card" style="flex: 1; min-width: 300px;">
        <h3>Registrar Artículo</h3>
        <br>
        <form method="POST">
            <input type="hidden" name="accion" value="crear">
            <div class="form-group">
                <label>Código de Barras:</label>
                <input type="text" name="codigo_barras" class="form-control" placeholder="Ej. 750123456789" required>
            </div>
            <div class="form-group">
                <label>Descripción / Nombre:</label>
                <input type="text" name="descripcion" class="form-control" placeholder="Ej. Detergente Líquido 1L" required>
            </div>
            <div class="flex-row" style="gap:12px;">
                <div class="form-group" style="flex:1;">
                    <label>Precio Venta:</label>
                    <input type="number" step="0.01" name="precio_venta" class="form-control" placeholder="0.00">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Precio Compra:</label>
                    <input type="number" step="0.01" name="precio_compra" class="form-control" placeholder="0.00">
                </div>
            </div>
            <div class="form-group">
                <label>Stock Inicial:</label>
                <input type="number" name="stock_actual" class="form-control" placeholder="0">
            </div>
            <div class="flex-row mt-2">
                <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Guardar</button>
                <button type="reset" class="btn btn-secondary"><span class="material-icons">clear</span> Limpiar</button>
            </div>
        </form>
    </div>

    <div class="module-card" style="flex: 2; min-width: 400px;">
        <h3>Listado General de Artículos</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Precio Venta</th>
                    <th>Stock</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total === 0): ?>
                <tr>
                    <td colspan="6" class="empty-state">No hay artículos registrados.</td>
                </tr>
                <?php else: ?>
                <?php $articulos->data_seek(0); while ($a = $articulos->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($a['codigo_barras']); ?></td>
                    <td><?php echo htmlspecialchars($a['descripcion']); ?></td>
                    <td class="text-primary fw-bold">$<?php echo number_format($a['precio_venta'], 2); ?></td>
                    <td><?php echo $a['stock_actual']; ?></td>
                    <td><span class="badge <?php echo $a['estatus'] === 'alta' ? 'badge-active' : 'badge-inactive'; ?>"><?php echo ucfirst($a['estatus']); ?></span></td>
                    <td>
                        <a href="?toggle=1&id=<?php echo $a['id']; ?>" class="btn btn-sm <?php echo $a['estatus'] === 'alta' ? 'btn-danger' : 'btn-success'; ?>"><?php echo $a['estatus'] === 'alta' ? 'Dar de Baja' : 'Reactivar'; ?></a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require '../dashboard-footer.php'; ?>
