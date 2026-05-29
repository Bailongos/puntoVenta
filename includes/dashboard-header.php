<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . ($root_path ?: '') . 'auth/login.php');
    exit;
}

require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../config/funciones.php';

$usuario_nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$usuario_rol = $_SESSION['rol_nombre'] ?? $_SESSION['rol'] ?? '';
$usuario_iniciales = strtoupper(substr($usuario_nombre, 0, 2));

$page_title = $page_title ?? 'Panel';
$page_search = $page_search ?? 'Buscar...';

$notificaciones = [];
$tabla_articulos = $conn->query("SHOW TABLES LIKE 'articulos'");
if ($tabla_articulos->num_rows > 0) {
    $bajo_stock = $conn->query("SELECT id, descripcion, stock_actual FROM articulos WHERE stock_actual <= 5 AND (estatus IS NULL OR estatus != 'baja') ORDER BY stock_actual ASC LIMIT 10");
    while ($ns = $bajo_stock->fetch_assoc()) {
        $notificaciones[] = [
            'icono' => $ns['stock_actual'] == 0 ? 'block' : 'inventory_2',
            'mensaje' => $ns['descripcion'] . ' — Stock: ' . $ns['stock_actual'],
            'url' => ($root_path ?: '') . 'modules/inventarios/inventarios.php',
            'critico' => $ns['stock_actual'] == 0
        ];
    }
}
$notif_count = count($notificaciones);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Punto de Venta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $root_path; ?>assets/css/menu.css?v=5">
</head>
<body class="dashboard-body <?php echo $body_class ?? ''; ?>">

    <div class="dashboard-layout">

        <?php include __DIR__ . '/navbar.php'; ?>

        <section class="dashboard-content">

            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <div class="search-box">
                        <span class="material-icons">search</span>
                        <input type="text" placeholder="<?php echo $page_search; ?>">
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="notification-wrapper">
                        <button class="notification-button" type="button" id="notif-btn">
                            <span class="material-icons">notifications</span>
                            <span class="notification-badge <?php echo $notif_count > 0 ? 'has-alerts' : ''; ?>"><?php echo $notif_count; ?></span>
                        </button>
                        <div class="notif-dropdown" id="notif-dropdown">
                            <div class="notif-header">Notificaciones</div>
                            <?php if ($notif_count === 0): ?>
                            <div class="notif-empty">Sin notificaciones</div>
                            <?php else: ?>
                            <?php foreach ($notificaciones as $n): ?>
                            <a href="<?php echo $n['url']; ?>" class="notif-item <?php echo $n['critico'] ? 'critico' : ''; ?>">
                                <span class="material-icons notif-icon"><?php echo $n['icono']; ?></span>
                                <span><?php echo htmlspecialchars($n['mensaje']); ?></span>
                            </a>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <main class="dashboard-main">
