<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$script_path = trim($_SERVER['SCRIPT_NAME'], '/');
$project_folder = explode('/', $script_path)[0];
$url_base = '/' . $project_folder;
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . $url_base . '/auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/funciones.php';

$activo = isset($modulo_activo) ? $modulo_activo : '';

$usuario_nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$usuario_rol = $_SESSION['rol_nombre'] ?? $_SESSION['rol'] ?? '';
$usuario_iniciales = strtoupper(substr($usuario_nombre, 0, 2));

$menu_items = [
    [
        'id' => 'inicio',
        'label' => 'Inicio',
        'icon' => 'home',
        'url' => $url_base . '/index.php',
        'permiso' => 'ver_inicio'
    ],
    [
        'id' => 'pos',
        'label' => 'Punto de Venta',
        'icon' => 'point_of_sale',
        'url' => $url_base . '/modules/pos/pos.php',
        'permiso' => 'ver_punto_venta'
    ],
    [
        'id' => 'clientes',
        'label' => 'Clientes',
        'icon' => 'group',
        'url' => $url_base . '/modules/clientes/clientes.php',
        'permiso' => 'ver_clientes'
    ],
    [
        'id' => 'articulos',
        'label' => 'Artículos',
        'icon' => 'sell',
        'url' => $url_base . '/modules/articulos/articulos.php',
        'permiso' => 'ver_articulos'
    ],
    [
        'id' => 'inventarios',
        'label' => 'Inventario',
        'icon' => 'inventory_2',
        'url' => $url_base . '/modules/inventarios/inventarios.php',
        'permiso' => 'ver_inventario'
    ],
    [
        'id' => 'reportes',
        'label' => 'Reportes',
        'icon' => 'bar_chart',
        'url' => $url_base . '/modules/reportes/reportes.php',
        'permiso' => 'ver_reportes'
    ],
];

$admin_items = [
    [
    'id' => 'usuarios',
    'label' => 'Usuarios',
    'icon' => 'manage_accounts',
    'url' => $url_base . '/modules/usuarios/usuarios.php',
    'permiso' => 'ver_usuarios'
    ],
    [
        'id' => 'roles',
        'label' => 'Roles',
        'icon' => 'verified_user',
        'url' => $url_base . '/modules/usuarios/roles.php',
        'permiso' => 'ver_roles'
    ],
    [
        'id' => 'permisos',
        'label' => 'Permisos',
        'icon' => 'lock',
        'url' => $url_base . '/modules/usuarios/permisos.php',
        'permiso' => 'ver_permisos'
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
        <?php foreach ($menu_items as $item):
            if (!tiene_permiso($item['permiso'])) continue;
        ?>
            <a
                href="<?php echo $item['url']; ?>"
                class="sidebar-link <?php echo ($activo === $item['id']) ? 'active' : ''; ?>"
            >
                <span class="sidebar-icon"><span class="material-icons"><?php echo $item['icon']; ?></span></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>

        <?php $has_admin = false;
        foreach ($admin_items as $item):
            if (tiene_permiso($item['permiso'])) { $has_admin = true; break; }
        endforeach;
        if ($has_admin): ?>
        <div class="sidebar-section-title">Administración</div>
        <?php endif; ?>

        <?php foreach ($admin_items as $item):
            if (!tiene_permiso($item['permiso'])) continue;
        ?>
            <a
                href="<?php echo $item['url']; ?>"
                class="sidebar-link <?php echo ($activo === $item['id']) ? 'active' : ''; ?>"
            >
                <span class="sidebar-icon"><span class="material-icons"><?php echo $item['icon']; ?></span></span>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php endforeach; ?>

        <?php if (tiene_permiso('ver_corte_caja')): ?>
        <div class="sidebar-section-title">Operación</div>

        <a href="<?php echo $url_base; ?>/CorteCaja/corte_caja.php" class="sidebar-link <?php echo ($activo === 'corte_caja') ? 'active' : ''; ?>">
            <span class="sidebar-icon"><span class="material-icons">calculate</span></span>
            <span>Corte de Caja</span>
        </a>
        <?php endif; ?>
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
