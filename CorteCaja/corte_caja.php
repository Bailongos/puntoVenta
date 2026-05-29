<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$resumen = $conn->query("
    SELECT
        COUNT(*) AS total_ventas,
        COALESCE(SUM(total),0) AS total_ingresos
    FROM ventas
    WHERE DATE(created_at) = '$fecha' AND estatus = 'completada'
")->fetch_assoc();

$productos_vendidos = $conn->query("
    SELECT COALESCE(SUM(vd.cantidad),0) AS total
    FROM ventas_detalle vd
    JOIN ventas v ON vd.venta_id = v.id
    WHERE DATE(v.created_at) = '$fecha' AND v.estatus = 'completada'
")->fetch_assoc()['total'];

$ticket_promedio = $resumen['total_ventas'] > 0
    ? $resumen['total_ingresos'] / $resumen['total_ventas']
    : 0;

$ventas = $conn->query("
    SELECT v.id, v.folio, v.total, v.created_at, u.usuario
    FROM ventas v
    LEFT JOIN usuarios u ON v.usuario_id = u.id
    WHERE DATE(v.created_at) = '$fecha' AND v.estatus = 'completada'
    ORDER BY v.created_at DESC
");

$detalle_venta = null;
$detalle_items = null;
if (isset($_GET['ver_ticket'])) {
    $id_venta = (int)$_GET['ver_ticket'];
    $detalle_venta = $conn->query("SELECT v.*, u.usuario FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id WHERE v.id = $id_venta")->fetch_assoc();
    if ($detalle_venta) {
        $detalle_items = $conn->query("SELECT vd.*, a.descripcion, a.codigo_barras FROM ventas_detalle vd JOIN articulos a ON vd.producto_id = a.id WHERE vd.venta_id = $id_venta");
    }
}

$modulo_activo = 'corte_caja';
$page_title = 'Corte de Caja';
$page_search = 'Buscar tickets...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Operaci&oacute;n</span>
        <h1>Corte de Caja</h1>
        <p>Resumen de ventas, ingresos y transacciones del d&iacute;a.</p>
    </div>
</div>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Ventas del d&iacute;a</p>
            <h2><?php echo $resumen['total_ventas']; ?></h2>
        </div>
        <div class="summary-icon blue"><span class="material-icons">receipt</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Ingresos totales</p>
            <h2>$<?php echo number_format($resumen['total_ingresos'], 2); ?></h2>
        </div>
        <div class="summary-icon green"><span class="material-icons">payments</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Productos vendidos</p>
            <h2><?php echo $productos_vendidos; ?></h2>
        </div>
        <div class="summary-icon orange"><span class="material-icons">inventory_2</span></div>
    </article>
    <article class="summary-card">
        <div>
            <p>Ticket promedio</p>
            <h2>$<?php echo number_format($ticket_promedio, 2); ?></h2>
        </div>
        <div class="summary-icon <?php echo $ticket_promedio > 0 ? 'green' : ''; ?>"><span class="material-icons">trending_up</span></div>
    </article>
</section>

<div class="module-card">
    <h3>Filtrar por fecha</h3>
    <form method="GET" class="flex-row" style="align-items: flex-end;">
        <div class="form-group" style="min-width: 200px;">
            <label>Fecha:</label>
            <input type="date" name="fecha" class="form-control" value="<?php echo $fecha; ?>">
        </div>
        <button type="submit" class="btn btn-primary"><span class="material-icons">search</span> Consultar</button>
    </form>
</div>

<div class="module-card">
    <h3>Ventas de <?php echo htmlspecialchars($fecha); ?></h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Hora</th>
                <th>Atendi&oacute;</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($ventas->num_rows === 0): ?>
            <tr>
                <td colspan="5" class="empty-state">No hay ventas en esta fecha.</td>
            </tr>
            <?php else: ?>
            <?php while ($v = $ventas->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($v['folio']); ?></td>
                <td><?php echo date('H:i', strtotime($v['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($v['usuario'] ?? '-'); ?></td>
                <td class="text-primary fw-bold">$<?php echo number_format($v['total'], 2); ?></td>
                <td><a href="?fecha=<?php echo urlencode($fecha); ?>&ver_ticket=<?php echo $v['id']; ?>" class="btn btn-primary btn-sm"><span class="material-icons" style="font-size:16px;">print</span> Ver Ticket</a></td>
            </tr>
            <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($detalle_venta): ?>
<div class="module-card">
    <h3>Ticket: <?php echo htmlspecialchars($detalle_venta['folio']); ?></h3>
    <div class="flex-row" style="margin-bottom:12px;">
        <span><strong>Fecha:</strong> <?php echo htmlspecialchars($detalle_venta['created_at']); ?></span>
        <span><strong>Atendi&oacute;:</strong> <?php echo htmlspecialchars($detalle_venta['usuario'] ?? 'N/A'); ?></span>
        <span><strong>Total:</strong> <strong class="text-primary">$<?php echo number_format($detalle_venta['total'], 2); ?></strong></span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>C&oacute;digo</th>
                <th>Descripci&oacute;n</th>
                <th>Cantidad</th>
                <th>Precio Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($item = $detalle_items->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['codigo_barras']); ?></td>
                <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$<?php echo number_format($item['precio_unitario'], 2); ?></td>
                <td class="fw-bold">$<?php echo number_format($item['subtotal'], 2); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right fw-bold">Total:</td>
                <td class="fw-bold text-primary">$<?php echo number_format($detalle_venta['total'], 2); ?></td>
            </tr>
        </tfoot>
    </table>
    <br>
    <a href="?fecha=<?php echo urlencode($fecha); ?>" class="btn btn-secondary"><span class="material-icons">close</span> Cerrar Ticket</a>
</div>
<?php endif; ?>

<?php require '../dashboard-footer.php'; ?>
