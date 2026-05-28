<?php
$script_path = trim($_SERVER['SCRIPT_NAME'], '/');
$project_folder = explode('/', $script_path)[0];
$url_base = '/' . $project_folder;

$activo = isset($modulo_activo) ? $modulo_activo : '';

$menu_items = [
    [
        'id' => 'inicio',
        'label' => 'Inicio',
        'icon' => '⌂',
        'url' => $url_base . '/menu.php'
    ],
    [
        'id' => 'pos',
        'label' => 'Punto de Venta',
        'icon' => '🛒',
        'url' => $url_base . '/Punto_De_Venta/PuntoDeVenta.php'
    ],
    [
        'id' => 'clientes',
        'label' => 'Clientes',
        'icon' => '👥',
        'url' => $url_base . '/Clientes/clientes.php'
    ],
    [
        'id' => 'articulos',
        'label' => 'Artículos',
        'icon' => '🏷️',
        'url' => $url_base . '/Articulos/articulos.php'
    ],
    [
        'id' => 'inventarios',
        'label' => 'Inventario',
        'icon' => '📦',
        'url' => $url_base . '/Inventarios/inventarios.php'
    ],
    [
        'id' => 'reportes',
        'label' => 'Reportes',
        'icon' => '📊',
        'url' => $url_base . '/Reportes/reportes.php'
    ],
];

$admin_items = [
    [
    'id' => 'usuarios',
    'label' => 'Usuarios',
    'icon' => '👤',
    'url' => $url_base . '/Usuarios/usuarios.php'
    ],
    [
        'id' => 'roles',
        'label' => 'Roles',
        'icon' => '🛡️',
        'url' => '#'
    ],
    [
        'id' => 'permisos',
        'label' => 'Permisos',
        'icon' => '🔐',
        'url' => '#'
    ],
];
?>

<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand-mark">🛍️</div>
        <div>
            <strong>Punto de Venta</strong>
            <span>Panel administrativo</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php foreach ($menu_items as $item): ?>
            <a
                href="<?php echo $item['url']; ?>"
                class="sidebar-link <?php echo ($activo === $item['id']) ? 'active' : ''; ?>"
            >
                <span class="sidebar-icon"><?php echo $item['icon']; ?></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>

        <div class="sidebar-section-title">Administración</div>

        <?php foreach ($admin_items as $item): ?>
            <a
                href="<?php echo $item['url']; ?>"
                class="sidebar-link <?php echo ($activo === $item['id']) ? 'active' : ''; ?>"
            >
                <span class="sidebar-icon"><?php echo $item['icon']; ?></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>

        <div class="sidebar-section-title">Operación</div>

        <a href="#" class="sidebar-link <?php echo ($activo === 'corte_caja') ? 'active' : ''; ?>">
            <span class="sidebar-icon">🧮</span>
            <span>Corte de Caja</span>
        </a>
    </nav>

    <div class="sidebar-store-card">
        <div class="store-icon">🏪</div>
        <div>
            <strong>Sucursal Centro</strong>
            <span>Caja 01</span>
            <small>● En línea</small>
        </div>
    </div>

    <a href="<?php echo $url_base; ?>/login.php" class="logout-link">
        <span>↩</span>
        Cerrar sesión
    </a>

</aside>