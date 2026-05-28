<?php
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
        <p>Control de entradas (altas por factura) y salidas de stock.</p>
    </div>
</div>

<div class="module-card">
    <h3>Ajuste Manual de Stock</h3>
    <br>
    <form style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
        <div class="form-group" style="flex: 2; min-width: 200px;">
            <label>Seleccionar Producto por Código:</label>
            <input type="text" class="form-control" placeholder="Ingresa código de barras...">
        </div>
        <div class="form-group" style="flex: 1; min-width: 100px;">
            <label>Cantidad:</label>
            <input type="number" class="form-control" placeholder="Cantidad">
        </div>
        <div class="form-group" style="flex: 1; min-width: 150px;">
            <label>Fecha de Movimiento:</label>
            <input type="date" class="form-control" value="2026-05-26">
        </div>
        <div style="margin-bottom: 15px; display: flex; gap: 10px;">
            <button type="button" class="btn btn-success">📥 Registrar Entrada</button>
            <button type="button" class="btn btn-danger">📤 Registrar Salida</button>
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
                <th>Tipo de Movimiento</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2026-05-26 14:30</td>
                <td>987654321</td>
                <td>Producto de Muestra A</td>
                <td><span class="badge badge-active">Alta por Factura</span></td>
                <td style="font-weight: bold; color: var(--dash-primary);">+50 unidades</td>
            </tr>
            <tr>
                <td>2026-05-26 15:10</td>
                <td>123456789</td>
                <td>Producto de Muestra B</td>
                <td><span class="badge badge-inactive">Baja por Venta</span></td>
                <td style="font-weight: bold; color: #ef4444;">-2 unidades</td>
            </tr>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
