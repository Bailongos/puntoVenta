<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

$conn->query("CREATE TABLE IF NOT EXISTS ventas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  folio VARCHAR(20) NOT NULL UNIQUE,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  forma_pago VARCHAR(50) DEFAULT 'efectivo',
  cliente_id INT DEFAULT NULL,
  usuario_id INT DEFAULT NULL,
  estatus ENUM('completada','cancelada') DEFAULT 'completada',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE IF NOT EXISTS ventas_detalle (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venta_id INT NOT NULL,
  producto_id INT NOT NULL,
  cantidad INT NOT NULL,
  precio_unitario DECIMAL(10,2) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
  FOREIGN KEY (producto_id) REFERENCES articulos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$fecha_desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_hasta = $_GET['hasta'] ?? date('Y-m-d');
$buscar_folio = trim($_GET['folio'] ?? '');

$where = "WHERE DATE(v.created_at) >= '$fecha_desde' AND DATE(v.created_at) <= '$fecha_hasta'";
if ($buscar_folio) {
    $where .= " AND v.folio LIKE '%" . $conn->real_escape_string($buscar_folio) . "%'";
}

$ventas = $conn->query("SELECT v.*, u.usuario FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id $where ORDER BY v.created_at DESC LIMIT 200");

$totales = $conn->query("SELECT COUNT(*) AS total_ventas, COALESCE(SUM(total),0) AS total_ingresos FROM ventas v $where AND v.estatus = 'completada'")->fetch_assoc();

$total_articulos = $conn->query("SELECT COALESCE(SUM(vd.cantidad),0) AS t FROM ventas_detalle vd JOIN ventas v ON vd.venta_id = v.id $where AND v.estatus = 'completada'")->fetch_assoc()['t'];

$total_movimientos = $conn->query("SELECT COUNT(*) AS c FROM inventario_movimientos m JOIN articulos a ON m.producto_id = a.id WHERE DATE(m.fecha_movimiento) >= '$fecha_desde' AND DATE(m.fecha_movimiento) <= '$fecha_hasta'")->fetch_assoc()['c'];



$modulo_activo = 'reportes';
$page_title = 'Reportes';
$page_search = 'Buscar tickets...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Auditoría</span>
        <h1>Reportes</h1>
        <p>Consulta transacciones realizadas, filtra históricos y visualiza los tickets de compra.</p>
    </div>
</div>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Ventas en período</p>
            <h2><?php echo $totales['total_ventas']; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">receipt</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Ingresos totales</p>
            <h2>$<?php echo number_format($totales['total_ingresos'], 2); ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">payments</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Artículos vendidos</p>
            <h2><?php echo $total_articulos; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">inventory_2</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Movimientos inventario</p>
            <h2><?php echo $total_movimientos; ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">swap_vert</span></div>
    </article>
</section>

<div class="module-card">
    <h3>Filtros de Auditoría</h3>
    <form class="report-filters flex-row" method="GET">
        <div class="form-group" style="min-width: 150px;">
            <label>Desde:</label>
            <input type="date" name="desde" class="form-control" value="<?php echo $fecha_desde; ?>">
        </div>
        <div class="form-group" style="min-width: 150px;">
            <label>Hasta:</label>
            <input type="date" name="hasta" class="form-control" value="<?php echo $fecha_hasta; ?>">
        </div>
        <div class="form-group" style="min-width: 150px;">
            <label>Filtrar por Folio:</label>
            <input type="text" name="folio" class="form-control" placeholder="Ej. VT-20260528" value="<?php echo htmlspecialchars($buscar_folio); ?>">
        </div>
        <button type="submit" class="btn btn-primary"><span class="material-icons">search</span> Aplicar Filtros</button>
    </form>
</div>

<div class="module-card">
    <h3>Bitácora de Ventas Realizadas</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha / Hora</th>
                <th>Atendió</th>
                <th>Monto Total</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ventas->num_rows === 0): ?>
            <tr>
                <td colspan="6" class="empty-state">No hay ventas en este período.</td>
            </tr>
            <?php else: ?>
            <?php while ($v = $ventas->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($v['folio']); ?></td>
                <td><?php echo htmlspecialchars($v['created_at']); ?></td>
                <td><?php echo htmlspecialchars($v['usuario'] ?? '-'); ?></td>
                <td class="text-primary fw-bold">$<?php echo number_format($v['total'], 2); ?></td>
                <td><span class="badge <?php echo $v['estatus'] === 'completada' ? 'badge-active' : 'badge-inactive'; ?>"><?php echo ucfirst($v['estatus']); ?></span></td>
                <td><button class="btn btn-primary btn-sm ver-ticket" data-id="<?php echo $v['id']; ?>"><span class="material-icons" style="font-size:16px;">print</span> Ver Ticket</button></td>
            </tr>
            <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
