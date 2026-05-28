<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$script_path = trim($_SERVER['SCRIPT_NAME'], '/');
$project_folder = explode('/', $script_path)[0];
$url_base = '/' . $project_folder;

$activo = isset($modulo_activo) ? $modulo_activo : '';

$usuario_nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$usuario_rol = $_SESSION['rol'] ?? '';
$usuario_iniciales = strtoupper(substr($usuario_nombre, 0, 2));

$menu_items = [
    [
        'id' => 'inicio',
        'label' => 'Inicio',
        'icon' => 'home',
        'url' => $url_base . '/menu.php'
    ],
    [
        'id' => 'pos',
        'label' => 'Punto de Venta',
        'icon' => 'point_of_sale',
        'url' => $url_base . '/Punto_De_Venta/PuntoDeVenta.php'
    ],
    [
        'id' => 'clientes',
        'label' => 'Clientes',
        'icon' => 'group',
        'url' => $url_base . '/Clientes/clientes.php'
    ],
    [
        'id' => 'articulos',
        'label' => 'Artículos',
        'icon' => 'sell',
        'url' => $url_base . '/Articulos/articulos.php'
    ],
    [
        'id' => 'inventarios',
        'label' => 'Inventario',
        'icon' => 'inventory_2',
        'url' => $url_base . '/Inventarios/inventarios.php'
    ],
    [
        'id' => 'reportes',
        'label' => 'Reportes',
        'icon' => 'bar_chart',
        'url' => $url_base . '/Reportes/reportes.php'
    ],
];

$admin_items = [
    [
    'id' => 'usuarios',
    'label' => 'Usuarios',
    'icon' => 'manage_accounts',
    'url' => $url_base . '/Usuarios/usuarios.php'
    ],
    [
        'id' => 'roles',
        'label' => 'Roles',
        'icon' => 'verified_user',
        'url' => '#'
    ],
    [
        'id' => 'permisos',
        'label' => 'Permisos',
        'icon' => 'lock',
        'url' => '#'
    ],
];
?>

<aside class="sidebar">

    <div class="sidebar-brand">
        <div class="brand-mark"><span class="material-icons">storefront</span></div>
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
                <span class="sidebar-icon"><span class="material-icons"><?php echo $item['icon']; ?></span></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>

        <div class="sidebar-section-title">Administración</div>

        <?php foreach ($admin_items as $item):
            $is_placeholder = ($item['url'] === '#');
        ?>
            <a
                href="<?php echo $item['url']; ?>"
                class="sidebar-link <?php echo ($activo === $item['id']) ? 'active' : ''; ?>"
            >
                <span class="sidebar-icon"><span class="material-icons"><?php echo $item['icon']; ?></span></span>
                <span><?php echo $item['label']; ?></span>
                <?php if ($is_placeholder): ?>
                    <span class="coming-soon">Próximo</span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>

        <div class="sidebar-section-title">Operación</div>

        <a href="#" class="sidebar-link <?php echo ($activo === 'corte_caja') ? 'active' : ''; ?>">
            <span class="sidebar-icon"><span class="material-icons">calculate</span></span>
            <span>Corte de Caja</span>
            <span class="coming-soon">Próximo</span>
        </a>
    </nav>

    <div class="sidebar-store-card">
        <div class="store-icon"><span class="material-icons">account_circle</span></div>
        <div>
            <strong><?php echo htmlspecialchars($usuario_nombre); ?></strong>
            <span style="text-transform: capitalize;"><?php echo htmlspecialchars($usuario_rol); ?></span>
            <small>● En línea</small>
        </div>
    </div>

    <a href="<?php echo $url_base; ?>/cerrar_sesion.php" class="logout-link">
        <span class="material-icons">logout</span>
        Cerrar sesión
    </a>

</aside>
