<?php
$modulo_activo = 'pos';
$page_title = 'Punto de Venta';
$page_search = 'Buscar productos...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Caja</span>
        <h1>Punto de Venta</h1>
        <p>Interfaz principal del cajero para procesar compras y realizar cortes.</p>
    </div>
</div>

<div class="pos-layout">

    <div class="pos-products-grid">

        <div class="product-card">
            <div style="background: #e2e8f0; height: 100px; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9em; color: #64748b;">📸 Imagen</div>
            <h4>Producto de Muestra A</h4>
            <p style="font-weight: bold; color: var(--dash-primary); margin: 5px 0;">$25.00</p>
            <button class="btn btn-success" style="padding: 5px 10px; font-size: 0.85em; width: 100%;">+ Agregar</button>
        </div>

        <div class="product-card">
            <div style="background: #e2e8f0; height: 100px; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9em; color: #64748b;">📸 Imagen</div>
            <h4>Producto de Muestra B</h4>
            <p style="font-weight: bold; color: var(--dash-primary); margin: 5px 0;">$14.50</p>
            <button class="btn btn-success" style="padding: 5px 10px; font-size: 0.85em; width: 100%;">+ Agregar</button>
        </div>

    </div>

    <div class="pos-ticket-sidebar">
        <h3>🧾 Ticket Actual</h3>
        <p style="font-size: 0.85em; color: var(--dash-muted);">Cliente: Público General</p>

        <div class="ticket-items">
            <table style="width: 100%; margin-top: 10px; font-size: 0.9em; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px dashed var(--dash-border);">
                        <th style="text-align:left;padding:6px 0;">Artículo</th>
                        <th style="text-align:right;padding:6px 0;">Subtotal</th>
                        <th style="width:30px;"></th>
                    </tr>
                </thead>
                <tbody id="ticket-body">
                    <tr>
                        <td colspan="3" class="empty-state" style="text-align:center;padding:24px;color:#94a3b8;">Agrega productos al ticket</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <span style="color:var(--dash-muted);font-size:0.9em;">Artículos: <strong id="ticket-count">0</strong></span>
            <div class="ticket-total" id="ticket-total" style="margin:0;">$0.00</div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <button class="btn btn-success" id="cobrar-btn">💵 Cobrar Venta</button>
            <button class="btn btn-primary" style="background-color: #0284c7;">✂️ Realizar Corte de Caja</button>
            <button class="btn btn-danger" id="cancelar-btn">🚫 Cancelar</button>
        </div>
    </div>

</div>

<?php require '../dashboard-footer.php'; ?>
