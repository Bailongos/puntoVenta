<?php
$modulo_activo = 'inicio';

// Temporales.
// Después se reemplazan por $_SESSION al conectar login y BD.
$usuario_nombre = 'Usuario';
$usuario_rol = 'Rol';
$usuario_iniciales = 'US';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal | Punto de Venta</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="menu.css?v=2">
</head>
<body class="dashboard-body">

    <div class="dashboard-layout">

        <?php include 'navbar.php'; ?>

        <section class="dashboard-content">

            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" type="button">☰</button>

                    <div class="search-box">
                        <span>🔎</span>
                        <input type="text" placeholder="Buscar clientes, artículos o tickets...">
                    </div>
                </div>

                <div class="topbar-right">
                    <button class="notification-button" type="button">
                        🔔
                        <span class="notification-badge">0</span>
                    </button>

                    <div class="user-profile">
                        <div class="user-avatar"><?php echo $usuario_iniciales; ?></div>
                        <div>
                            <strong><?php echo $usuario_nombre; ?></strong>
                            <span><?php echo $usuario_rol; ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="dashboard-main">

                <div class="page-heading">
                    <div>
                        <span class="eyebrow">Panel principal</span>
                        <h1>Panel principal</h1>
                        <p>Resumen general del punto de venta y accesos rápidos del sistema.</p>
                    </div>

                    <a href="Punto_De_Venta/PuntoDeVenta.php" class="primary-action">
                        + Nueva venta
                    </a>
                </div>

                <section class="summary-grid">
                    <article class="summary-card">
                        <div>
                            <p>Ventas del día</p>
                            <h2>$ 0.00</h2>
                            <span class="neutral">Sin ventas registradas</span>
                        </div>
                        <div class="summary-icon green">💵</div>
                    </article>

                    <article class="summary-card">
                        <div>
                            <p>Tickets</p>
                            <h2>0</h2>
                            <span class="neutral">Sin tickets registrados</span>
                        </div>
                        <div class="summary-icon blue">🧾</div>
                    </article>

                    <article class="summary-card">
                        <div>
                            <p>Productos bajos</p>
                            <h2>0</h2>
                            <span class="neutral">Sin alertas de stock</span>
                        </div>
                        <div class="summary-icon orange">📦</div>
                    </article>

                    <article class="summary-card">
                        <div>
                            <p>Caja abierta</p>
                            <h2>$ 0.00</h2>
                            <span class="neutral">Caja sin apertura</span>
                        </div>
                        <div class="summary-icon green">🧮</div>
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
                            <a href="Punto_De_Venta/PuntoDeVenta.php">
                                <span>🛒</span>
                                <div>
                                    <strong>Nueva venta</strong>
                                    <small>Iniciar ticket de venta</small>
                                </div>
                                <b>›</b>
                            </a>

                            <a href="Clientes/clientes.php">
                                <span>👥</span>
                                <div>
                                    <strong>Nuevo cliente</strong>
                                    <small>Registrar cliente</small>
                                </div>
                                <b>›</b>
                            </a>

                            <a href="Articulos/articulos.php">
                                <span>🏷️</span>
                                <div>
                                    <strong>Buscar artículo</strong>
                                    <small>Consultar precio y stock</small>
                                </div>
                                <b>›</b>
                            </a>

                            <a href="#">
                                <span>🧮</span>
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

                        <a href="Reportes/reportes.php">Ver reportes</a>
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

            </main>

        </section>

    </div>

</body>
</html>