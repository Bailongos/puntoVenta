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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $input = json_decode(file_get_contents('php://input'), true);
    $accion = $input['accion'] ?? $_POST['accion'] ?? '';

    if ($accion === 'cobrar') {
        $items = $input['items'] ?? [];
        if (empty($items)) {
            echo json_encode(['success' => false, 'error' => 'El ticket está vacío.']);
            exit;
        }

        $usuario_id = $_SESSION['usuario_id'] ?? null;
        $total = 0;
        $detalles = [];

        $conn->begin_transaction();
        try {
            foreach ($items as $item) {
                $prod_id = (int)($item['producto_id'] ?? 0);
                $cantidad = (int)($item['cantidad'] ?? 1);
                if ($prod_id <= 0 || $cantidad <= 0) continue;

                $prod = $conn->query("SELECT id, descripcion, precio_venta, stock_actual FROM articulos WHERE id = $prod_id AND (estatus IS NULL OR estatus != 'baja')")->fetch_assoc();
                if (!$prod) {
                    throw new Exception('Producto no encontrado: ID ' . $prod_id);
                }
                if ($prod['stock_actual'] < $cantidad) {
                    throw new Exception('Stock insuficiente para ' . $prod['descripcion'] . '. Disponible: ' . $prod['stock_actual']);
                }

                $precio = (float)$prod['precio_venta'];
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $detalles[] = [
                    'producto_id' => $prod_id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal
                ];
            }

            if ($total <= 0) {
                throw new Exception('Total de venta inválido.');
            }

            $folio = 'VT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $stmt = $conn->prepare("INSERT INTO ventas (folio, total, usuario_id) VALUES (?, ?, ?)");
            $stmt->bind_param('sdi', $folio, $total, $usuario_id);
            $stmt->execute();
            $venta_id = $stmt->insert_id;
            $stmt->close();

            $stmt_det = $conn->prepare("INSERT INTO ventas_detalle (venta_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($detalles as $det) {
                $stmt_det->bind_param('iiidd', $venta_id, $det['producto_id'], $det['cantidad'], $det['precio_unitario'], $det['subtotal']);
                $stmt_det->execute();

                $conn->query("UPDATE articulos SET stock_actual = stock_actual - {$det['cantidad']} WHERE id = {$det['producto_id']}");

                $conn->query("INSERT INTO inventario_movimientos (producto_id, tipo_movimiento, concepto, cantidad, stock_anterior, stock_posterior, usuario_id)
                    VALUES ({$det['producto_id']}, 'salida', 'Venta folio $folio', {$det['cantidad']}, (SELECT stock_actual + {$det['cantidad']} FROM articulos WHERE id = {$det['producto_id']}), (SELECT stock_actual FROM articulos WHERE id = {$det['producto_id']}), " . ($usuario_id ?: 'NULL') . ")");
            }
            $stmt_det->close();

            $conn->commit();
            echo json_encode(['success' => true, 'folio' => $folio, 'total' => number_format($total, 2)]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    if ($accion === 'corte') {
        $fecha_sql = $conn->query("SELECT CURDATE() AS d")->fetch_assoc()['d'];
        $fecha = $input['fecha'] ?? $fecha_sql;
        $ventas = $conn->query("SELECT COUNT(*) AS total_ventas, COALESCE(SUM(total),0) AS total_ingresos FROM ventas WHERE DATE(created_at) = '$fecha' AND estatus = 'completada'")->fetch_assoc();
        $productos_vendidos = $conn->query("SELECT COALESCE(SUM(vd.cantidad),0) AS total FROM ventas_detalle vd JOIN ventas v ON vd.venta_id = v.id WHERE DATE(v.created_at) = '$fecha' AND v.estatus = 'completada'")->fetch_assoc();
        $detalle = $conn->query("SELECT v.id, v.folio, v.total, v.created_at, u.usuario FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id WHERE DATE(v.created_at) = '$fecha' AND v.estatus = 'completada' ORDER BY v.created_at DESC");
        $lista = [];
        while ($d = $detalle->fetch_assoc()) {
            $lista[] = $d;
        }
        echo json_encode([
            'success' => true,
            'fecha' => $fecha,
            'total_ventas' => $ventas['total_ventas'],
            'total_ingresos' => number_format($ventas['total_ingresos'], 2),
            'productos_vendidos' => $productos_vendidos['total'],
            'ventas' => $lista
        ]);
        exit;
    }
}

$productos = $conn->query("SELECT id, codigo_barras, descripcion, precio_venta, stock_actual FROM articulos WHERE (estatus IS NULL OR estatus != 'baja') AND stock_actual > 0 ORDER BY descripcion");

$hoy_ventas = $conn->query("SELECT COUNT(*) AS c, COALESCE(SUM(total),0) AS t FROM ventas WHERE DATE(created_at) = CURDATE() AND estatus = 'completada'")->fetch_assoc();

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
        <p>Ventas hoy: <strong>$<?php echo number_format($hoy_ventas['t'], 2); ?></strong> (<?php echo $hoy_ventas['c']; ?> tickets)</p>
    </div>
</div>

<div class="pos-layout">
    <div class="pos-products-grid">
        <?php if ($productos->num_rows === 0): ?>
        <div class="empty-state" style="grid-column:1/-1;">No hay productos disponibles con stock. <a href="../Articulos/articulos.php">Registrar artículos</a></div>
        <?php else: ?>
        <?php while ($p = $productos->fetch_assoc()): ?>
        <div class="product-card" data-id="<?php echo $p['id']; ?>" data-nombre="<?php echo htmlspecialchars($p['descripcion']); ?>" data-precio="<?php echo $p['precio_venta']; ?>" data-stock-original="<?php echo $p['stock_actual']; ?>">
            <div class="product-img-placeholder"><span class="material-icons">inventory_2</span></div>
            <h4><?php echo htmlspecialchars($p['descripcion']); ?></h4>
            <p>$<?php echo number_format($p['precio_venta'], 2); ?></p>
            <span class="stock-label" style="font-size:11px;color:var(--muted);">Stock: <?php echo $p['stock_actual']; ?></span>
        </div>
        <?php endwhile; ?>
        <?php endif; ?>
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

<div class="modal-overlay" id="corte-modal">
    <div class="modal-panel">
        <div class="modal-heading">
            <h2>Corte de Caja</h2>
            <button class="modal-close" id="corte-cerrar">&times;</button>
        </div>
        <div id="corte-body">
            <div class="corte-resumen" id="corte-resumen">
                <div><span>Fecha</span><strong id="c-fecha">-</strong></div>
                <div><span>Ventas</span><strong id="c-ventas">0</strong></div>
                <div><span>Ingresos</span><strong id="c-ingresos" style="color:var(--primary);font-size:1.2em;">$0.00</strong></div>
                <div><span>Productos</span><strong id="c-productos">0</strong></div>
            </div>
            <div id="corte-lista" style="margin-top:16px;font-weight:600;font-size:14px;">Ventas del d&iacute;a</div>
            <table class="data-table">
                <thead><tr><th>Folio</th><th>Hora</th><th>Atendi&oacute;</th><th>Total</th></tr></thead>
                <tbody id="corte-tabla-body"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function() {
    const ticketBody = document.getElementById('ticket-body');
    const ticketTotal = document.getElementById('ticket-total');
    const ticketCount = document.getElementById('ticket-count');
    const cobrarBtn = document.getElementById('cobrar-btn');
    const cancelarBtn = document.getElementById('cancelar-btn');
    const corteBtn = document.getElementById('corte-btn');
    const productCards = document.querySelectorAll('.product-card');

    let ticketItems = [];

    function showToast(message, type) {
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();
        const toast = document.createElement('div');
        toast.className = 'toast-notification ' + (type || 'info');
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 300);
        }, 3000);
    }

    function stockEnTicket(producto_id) {
        var total = 0;
        ticketItems.forEach(function(item) {
            if (item.producto_id === producto_id) total += item.cantidad;
        });
        return total;
    }

    function actualizarStocks() {
        productCards.forEach(function(card) {
            var pid = parseInt(card.getAttribute('data-id')) || 0;
            var original = parseInt(card.getAttribute('data-stock-original')) || 0;
            var enTicket = stockEnTicket(pid);
            var disponible = original - enTicket;
            var label = card.querySelector('.stock-label');
            if (label) {
                label.textContent = 'Stock: ' + disponible;
                label.style.color = disponible <= 0 ? 'var(--danger)' : (disponible <= 5 ? '#d97706' : 'var(--muted)');
            }
            card.style.opacity = disponible <= 0 ? '0.5' : '1';
            card.style.cursor = disponible <= 0 ? 'default' : 'pointer';
        });
    }

    function actualizarTicket() {
        if (!ticketBody) return;
        ticketBody.innerHTML = '';
        if (ticketItems.length === 0) {
            ticketBody.innerHTML = '<tr><td colspan="3" class="empty-state">Agrega productos al ticket</td></tr>';
            if (ticketTotal) ticketTotal.textContent = '$0.00';
            if (ticketCount) ticketCount.textContent = '0';
            actualizarStocks();
            return;
        }
        var total = 0;
        ticketItems.forEach(function(item, index) {
            var subtotal = item.precio * item.cantidad;
            total += subtotal;
            var row = document.createElement('tr');
            row.innerHTML =
                '<td style="white-space:nowrap;">' +
                '<button class="btn-qty" data-index="' + index + '" data-dir="-1">−</button> ' +
                item.cantidad +
                ' <button class="btn-qty" data-index="' + index + '" data-dir="1">+</button> x ' +
                item.nombre +
                '</td><td class="text-right">$' + subtotal.toFixed(2) +
                '</td><td class="td-actions"><button class="btn-remove-item" data-index="' + index + '">×</button></td>';
            ticketBody.appendChild(row);
        });
        if (ticketTotal) ticketTotal.textContent = '$' + total.toFixed(2);
        if (ticketCount) ticketCount.textContent = ticketItems.length;

        document.querySelectorAll('.btn-remove-item').forEach(function(btn) {
            btn.addEventListener('click', function() {
                ticketItems.splice(parseInt(this.getAttribute('data-index')), 1);
                actualizarTicket();
            });
        });

        document.querySelectorAll('.btn-qty').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                var idx = parseInt(this.getAttribute('data-index'));
                var dir = parseInt(this.getAttribute('data-dir'));
                var item = ticketItems[idx];
                if (!item) return;
                var nuevo = item.cantidad + dir;
                if (nuevo <= 0) {
                    ticketItems.splice(idx, 1);
                } else {
                    var card = document.querySelector('.product-card[data-id="' + item.producto_id + '"]');
                    var original = parseInt(card ? card.getAttribute('data-stock-original') : '0') || 0;
                    var enTicket = stockEnTicket(item.producto_id) - item.cantidad;
                    if (nuevo > original - enTicket) {
                        showToast('Stock insuficiente. Disponible: ' + (original - enTicket), 'error');
                        return;
                    }
                    item.cantidad = nuevo;
                }
                actualizarTicket();
            });
        });

        actualizarStocks();
    }

    function agregarAlTicket(nombre, precio, producto_id) {
        var card = document.querySelector('.product-card[data-id="' + producto_id + '"]');
        var original = parseInt(card ? card.getAttribute('data-stock-original') : '0') || 0;
        var enTicket = stockEnTicket(producto_id);
        if (enTicket >= original) {
            showToast('Stock insuficiente para ' + nombre + '. Disponible: 0', 'error');
            return;
        }
        var found = ticketItems.filter(function(i) { return i.producto_id === producto_id; });
        if (found.length > 0) {
            found[0].cantidad += 1;
        } else {
            ticketItems.push({ nombre: nombre, precio: precio, cantidad: 1, producto_id: producto_id });
        }
        actualizarTicket();
        showToast(nombre + ' agregado al ticket', 'success');
    }

    productCards.forEach(function(card) {
        var nombre = card.getAttribute('data-nombre');
        var precio = parseFloat(card.getAttribute('data-precio')) || 0;
        var producto_id = parseInt(card.getAttribute('data-id')) || 0;
        card.addEventListener('click', function() {
            var original = parseInt(card.getAttribute('data-stock-original')) || 0;
            if (original <= 0 || stockEnTicket(producto_id) >= original) {
                showToast('Producto sin stock disponible', 'error');
                return;
            }
            agregarAlTicket(nombre, precio, producto_id);
        });
    });

    if (cobrarBtn) {
        cobrarBtn.addEventListener('click', function() {
            if (ticketItems.length === 0) {
                showToast('El ticket está vacío. Agrega productos primero.', 'error');
                return;
            }
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'cobrar', items: ticketItems })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    productCards.forEach(function(card) {
                        var pid = parseInt(card.getAttribute('data-id')) || 0;
                        var original = parseInt(card.getAttribute('data-stock-original')) || 0;
                        var enTicket = stockEnTicket(pid);
                        card.setAttribute('data-stock-original', original - enTicket);
                    });
                    showToast('Venta #' + data.folio + ' cobrada: $' + data.total, 'success');
                    ticketItems = [];
                    actualizarTicket();
                } else {
                    showToast('Error: ' + data.error, 'error');
                }
            })
            .catch(function(err) {
                showToast('Error de conexión: ' + err.message, 'error');
            });
        });
    }

    if (cancelarBtn) {
        cancelarBtn.addEventListener('click', function() {
            if (ticketItems.length === 0) {
                showToast('El ticket ya está vacío.', 'info');
                return;
            }
            ticketItems = [];
            actualizarTicket();
            showToast('Ticket cancelado.', 'info');
        });
    }

    if (corteBtn) {
        var corteModal = document.getElementById('corte-modal');
        var corteCerrar = document.getElementById('corte-cerrar');

        function abrirCorte(data) {
            document.getElementById('c-fecha').textContent = data.fecha;
            document.getElementById('c-ventas').textContent = data.total_ventas;
            document.getElementById('c-ingresos').textContent = '$' + data.total_ingresos;
            document.getElementById('c-productos').textContent = data.productos_vendidos;
            var tbody = document.getElementById('corte-tabla-body');
            tbody.innerHTML = '';
            if (data.ventas.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="empty-state">Sin ventas hoy</td></tr>';
            } else {
                data.ventas.forEach(function(v) {
                    var tr = document.createElement('tr');
                    tr.innerHTML = '<td>' + v.folio + '</td><td>' + v.created_at + '</td><td>' + (v.usuario || '-') + '</td><td class="text-primary fw-bold">$' + parseFloat(v.total).toFixed(2) + '</td>';
                    tbody.appendChild(tr);
                });
            }
            corteModal.classList.add('abierto');
        }

        if (corteCerrar) {
            corteCerrar.addEventListener('click', function() {
                corteModal.classList.remove('abierto');
            });
        }
        if (corteModal) {
            corteModal.addEventListener('click', function(e) {
                if (e.target === corteModal) corteModal.classList.remove('abierto');
            });
        }
        corteBtn.addEventListener('click', function() {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: 'corte' })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    abrirCorte(data);
                } else {
                    showToast('Error al obtener corte', 'error');
                }
            })
            .catch(function(err) {
                showToast('Error de conexion: ' + err.message, 'error');
            });
        });
    }
})();
</script>

<?php require '../dashboard-footer.php'; ?>
