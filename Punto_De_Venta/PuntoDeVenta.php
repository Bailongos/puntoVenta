<?php
$modulo_activo = 'pos';
$page_title = 'Punto de Venta';
$page_search = 'Buscar productos...';
$root_path = '../';
$body_class = 'pos-mode';
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
            <div class="product-img-placeholder"><span class="material-icons">shopping_bag</span></div>
            <h4>Coca-Cola 600ml</h4>
            <p>$18.00</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

        <div class="product-card">
            <div class="product-img-placeholder"><span class="material-icons">bakery_dining</span></div>
            <h4>Pan Blanco Bimbo</h4>
            <p>$32.50</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

        <div class="product-card">
            <div class="product-img-placeholder"><span class="material-icons">water_drop</span></div>
            <h4>Agua Natural 1L</h4>
            <p>$14.00</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

        <div class="product-card">
            <div class="product-img-placeholder"><span class="material-icons">egg</span></div>
            <h4>Huevo Blanco 12pz</h4>
            <p>$38.00</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

        <div class="product-card">
            <div class="product-img-placeholder"><span class="material-icons">local_pizza</span></div>
            <h4>Pizza Congelada</h4>
            <p>$89.00</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

        <div class="product-card">
            <div class="product-img-placeholder"><span class="material-icons">coffee</span></div>
            <h4>Café Molido 250g</h4>
            <p>$55.00</p>
            <button class="btn btn-success btn-block">+ Agregar</button>
        </div>

    </div>

    <div class="pos-ticket-sidebar">
        <h3><span class="material-icons">receipt</span> Ticket Actual</h3>
        <p class="ticket-meta">Cliente: Público General</p>

        <div class="ticket-items">
            <table class="ticket-table">
                <thead>
                    <tr>
                        <th>Artículo</th>
                        <th class="text-right">Subtotal</th>
                        <th class="td-actions"></th>
                    </tr>
                </thead>
                <tbody id="ticket-body">
                    <tr>
                        <td colspan="3" class="empty-state">Agrega productos al ticket</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="ticket-summary">
            <span class="ticket-count">Artículos: <strong id="ticket-count">0</strong></span>
            <div class="ticket-total" id="ticket-total">$0.00</div>
        </div>

        <div class="ticket-actions">
            <button class="btn btn-success" id="cobrar-btn"><span class="material-icons">payments</span> Cobrar Venta</button>
            <button class="btn btn-info" id="corte-btn"><span class="material-icons">content_cut</span> Corte de Caja</button>
            <button class="btn btn-danger" id="cancelar-btn"><span class="material-icons">cancel</span> Cancelar</button>
        </div>
    </div>

</div>

<?php require '../dashboard-footer.php'; ?>
