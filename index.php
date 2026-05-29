<?php
$modulo_activo = 'inicio';
$page_title = 'Menú Principal';
$page_search = 'Buscar clientes, artículos o tickets...';
$root_path = '';
require 'includes/dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Panel principal</span>
        <h1>Panel principal</h1>
        <p>Resumen general del punto de venta y accesos rápidos del sistema.</p>
    </div>

    <a href="modules/pos/pos.php" class="btn btn-primary" style="min-height: 42px;">
        <span class="material-icons">add</span> Nueva venta
    </a>
</div>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Ventas del día</p>
            <h2>$ 0.00</h2>
            <span class="neutral">Sin ventas registradas</span>
        </div>
        <div class="summary-icon green"><span class="material-icons">payments</span></div>
    </article>

    <article class="summary-card">
        <div>
            <p>Tickets</p>
            <h2>0</h2>
            <span class="neutral">Sin tickets registrados</span>
        </div>
        <div class="summary-icon blue"><span class="material-icons">receipt_long</span></div>
    </article>

    <article class="summary-card">
        <div>
            <p>Productos bajos</p>
            <h2>0</h2>
            <span class="neutral">Sin alertas de stock</span>
        </div>
        <div class="summary-icon orange"><span class="material-icons">inventory</span></div>
    </article>

    <article class="summary-card">
        <div>
            <p>Caja abierta</p>
            <h2>$ 0.00</h2>
            <span class="neutral">Caja sin apertura</span>
        </div>
        <div class="summary-icon green"><span class="material-icons">point_of_sale</span></div>
    </article>
</section>

<section class="dashboard-grid">

    <article class="panel-card chart-card">
        <div class="panel-header">
            <div>
                <h3>Ventas de los últimos 7 días</h3>
                <p>Cuando existan ventas registradas, aquí se mostrará el comportamiento reciente.</p>
            </div>

            <select>
                <option>Últimos 7 días</option>
                <option>Este mes</option>
                <option>Este año</option>
            </select>
        </div>

        <div class="fake-chart empty-chart">
            <p>No hay información suficiente para mostrar la gráfica.</p>
        </div>
    </article>

    <article class="panel-card quick-actions">
        <div class="panel-header">
            <div>
                <h3>Acciones rápidas</h3>
                <p>Atajos para operaciones frecuentes.</p>
            </div>
        </div>

        <div class="action-list">
            <a href="modules/pos/pos.php">
                <span><span class="material-icons">shopping_cart</span></span>
                <div>
                    <strong>Nueva venta</strong>
                    <small>Iniciar ticket de venta</small>
                </div>
                <b>›</b>
            </a>

            <a href="modules/clientes/clientes.php">
                <span><span class="material-icons">person_add</span></span>
                <div>
                    <strong>Nuevo cliente</strong>
                    <small>Registrar cliente</small>
                </div>
                <b>›</b>
            </a>

            <a href="modules/articulos/articulos.php">
                <span><span class="material-icons">search</span></span>
                <div>
                    <strong>Buscar artículo</strong>
                    <small>Consultar precio y stock</small>
                </div>
                <b>›</b>
            </a>

            <a href="#">
                <span><span class="material-icons">calculate</span></span>
                <div>
                    <strong>Corte de caja</strong>
                    <small>Cerrar caja y revisar ventas</small>
                </div>
                <b>›</b>
            </a>
        </div>
    </article>

</section>

<section class="panel-card activity-card">
    <div class="panel-header">
        <div>
            <h3>Actividad reciente</h3>
            <p>Últimos movimientos registrados en el sistema.</p>
        </div>

        <a href="modules/reportes/reportes.php">Ver reportes</a>
    </div>

    <div class="activity-table">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="empty-state">
                        No hay movimientos registrados por el momento.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php require 'includes/dashboard-footer.php'; ?>
