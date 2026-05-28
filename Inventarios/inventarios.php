<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

$conn->query("CREATE TABLE IF NOT EXISTS inventario_movimientos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  tipo_movimiento ENUM('entrada','salida') NOT NULL,
  concepto VARCHAR(100) DEFAULT NULL,
  cantidad INT NOT NULL,
  stock_anterior INT NOT NULL,
  stock_posterior INT NOT NULL,
  usuario_id INT DEFAULT NULL,
  observaciones TEXT DEFAULT NULL,
  fecha_movimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (producto_id) REFERENCES articulos(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $producto_id = (int)($_POST['producto_id'] ?? 0);
    $cantidad = (int)($_POST['cantidad'] ?? 0);
    $concepto = trim($_POST['concepto'] ?? '');
    $fecha = $_POST['fecha_movimiento'] ?? '';
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    if ($producto_id <= 0 || $cantidad <= 0) {
        $mensaje = 'Selecciona un producto y cantidad válida.';
    } else {
        $prod = $conn->query("SELECT id, descripcion, stock_actual FROM articulos WHERE id = $producto_id")->fetch_assoc();
        if (!$prod) {
            $mensaje = 'Producto no encontrado.';
        } else {
            $stock_anterior = (int)$prod['stock_actual'];
            if ($accion === 'entrada') {
                $stock_posterior = $stock_anterior + $cantidad;
            } else {
                if ($cantidad > $stock_anterior) {
                    $mensaje = 'Stock insuficiente. Disponible: ' . $stock_anterior;
                } else {
                    $stock_posterior = $stock_anterior - $cantidad;
                }
            }

            if (!$mensaje) {
                $fecha_dt = $fecha ? date('Y-m-d H:i:s', strtotime($fecha)) : date('Y-m-d H:i:s');
                $conn->begin_transaction();
                try {
                    $stmt = $conn->prepare("INSERT INTO inventario_movimientos (producto_id, tipo_movimiento, concepto, cantidad, stock_anterior, stock_posterior, usuario_id, fecha_movimiento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('issiiiss', $producto_id, $accion, $concepto, $cantidad, $stock_anterior, $stock_posterior, $usuario_id, $fecha_dt);
                    $stmt->execute();
                    $stmt->close();

                    $conn->query("UPDATE articulos SET stock_actual = $stock_posterior WHERE id = $producto_id");
                    $conn->commit();
                    $mensaje = ucfirst($accion) . ' registrada: ' . $cantidad . ' unidades de ' . $prod['descripcion'] . '. Stock actual: ' . $stock_posterior;
                } catch (Exception $e) {
                    $conn->rollback();
                    $mensaje = 'Error: ' . $e->getMessage();
                }
            }
        }
    }
}

$productos = $conn->query("SELECT id, codigo_barras, descripcion, stock_actual FROM articulos WHERE estatus != 'baja' OR estatus IS NULL ORDER BY descripcion");
$movimientos = $conn->query("SELECT m.*, a.codigo_barras, a.descripcion FROM inventario_movimientos m JOIN articulos a ON m.producto_id = a.id ORDER BY m.fecha_movimiento DESC LIMIT 50");

$total_mov = $conn->query("SELECT COUNT(*) AS c FROM inventario_movimientos")->fetch_assoc()['c'];
$entradas = $conn->query("SELECT COALESCE(SUM(cantidad),0) AS t FROM inventario_movimientos WHERE tipo_movimiento='entrada'")->fetch_assoc()['t'];
$salidas = $conn->query("SELECT COALESCE(SUM(cantidad),0) AS t FROM inventario_movimientos WHERE tipo_movimiento='salida'")->fetch_assoc()['t'];

$modulo_activo = 'inventarios';
$page_title = 'Inventarios';
$page_search = 'Buscar en inventario...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Stock</span>
        <h1>Inventarios</h1>
        <p>Control de entradas y salidas de stock.</p>
    </div>
</div>

<?php if ($mensaje): ?>
<div class="alert <?php echo strpos($mensaje, 'Error') === 0 ? 'alert-error' : 'alert-success'; ?>"><?php echo htmlspecialchars($mensaje); ?></div>
<?php endif; ?>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Total movimientos</p>
            <h2><?php echo $total_mov; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">swap_vert</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Unidades entrada</p>
            <h2><?php echo $entradas; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">download</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Unidades salida</p>
            <h2><?php echo $salidas; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">upload</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Diferencia neta</p>
            <h2><?php echo $entradas - $salidas; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">trending_up</span></div>
    </article>
</section>

<div class="module-card">
    <h3>Ajuste Manual de Stock</h3>
    <br>
    <form method="POST" class="flex-row" style="align-items: flex-end;">
        <div class="form-group" style="flex: 2; min-width: 200px;">
            <label>Producto:</label>
            <select name="producto_id" class="form-control" required>
                <option value="">-- Seleccionar producto --</option>
                <?php $productos->data_seek(0); while ($p = $productos->fetch_assoc()): ?>
                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['codigo_barras']) . ' — ' . htmlspecialchars($p['descripcion']) . ' (stock: ' . $p['stock_actual'] . ')'; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group" style="flex: 1; min-width: 100px;">
            <label>Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" min="1" placeholder="Cantidad" required>
        </div>
        <div class="form-group" style="flex: 2; min-width: 200px;">
            <label>Concepto / Motivo:</label>
            <input type="text" name="concepto" class="form-control" placeholder="Ej. Factura proveedor, venta, ajuste...">
        </div>
        <div class="form-group" style="flex: 1; min-width: 150px;">
            <label>Fecha de Movimiento:</label>
            <input type="date" name="fecha_movimiento" class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="flex-row">
            <button type="submit" name="accion" value="entrada" class="btn btn-success"><span class="material-icons">download</span> Registrar Entrada</button>
            <button type="submit" name="accion" value="salida" class="btn btn-danger"><span class="material-icons">upload</span> Registrar Salida</button>
        </div>
    </form>
</div>

<div class="module-card">
    <h3>Historial de Movimientos Recientes</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Concepto</th>
                <th>Tipo</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_mov === 0): ?>
            <tr>
                <td colspan="6" class="empty-state">No hay movimientos registrados.</td>
            </tr>
            <?php else: ?>
            <?php while ($m = $movimientos->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($m['fecha_movimiento']); ?></td>
                <td><?php echo htmlspecialchars($m['codigo_barras']); ?></td>
                <td><?php echo htmlspecialchars($m['descripcion']); ?></td>
                <td><?php echo htmlspecialchars($m['concepto'] ?? '-'); ?></td>
                <td><span class="badge <?php echo $m['tipo_movimiento'] === 'entrada' ? 'badge-active' : 'badge-inactive'; ?>"><?php echo ucfirst($m['tipo_movimiento']); ?></span></td>
                <td class="fw-bold <?php echo $m['tipo_movimiento'] === 'entrada' ? 'text-primary' : 'text-danger'; ?>">
                    <?php echo $m['tipo_movimiento'] === 'entrada' ? '+' : '-'; ?><?php echo $m['cantidad']; ?> unidades
                </td>
            </tr>
            <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
