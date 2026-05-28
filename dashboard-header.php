<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: ' . ($root_path ?: '') . 'login.php');
    exit;
}

require_once __DIR__ . '/funciones.php';

$usuario_nombre = $_SESSION['nombre_completo'] ?? 'Usuario';
$usuario_rol = $_SESSION['rol_nombre'] ?? $_SESSION['rol'] ?? '';
$usuario_iniciales = strtoupper(substr($usuario_nombre, 0, 2));

$page_title = $page_title ?? 'Panel';
$page_search = $page_search ?? 'Buscar...';
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
    <link rel="stylesheet" href="<?php echo $root_path; ?>menu.css?v=5">
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
                    <button class="notification-button" type="button">
                        <span class="material-icons">notifications</span>
                        <span class="notification-badge">0</span>
                    </button>
                </div>
            </header>

            <main class="dashboard-main">
