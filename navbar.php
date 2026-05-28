<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
$url_base = '/punto-de-venta-ruben';
$activo = isset($modulo_activo) ? $modulo_activo : '';
?>

<nav style="width: 260px; background-color: #1e293b; padding: 20px; display: flex; flex-direction: column; gap: 10px; border-right: 1px solid var(--border-color); min-height: 100vh;">

    <h2 style="color: #38bdf8; text-align: center; margin-bottom: 10px;">🏪 Mi POS</h2>

    <div style="color: #94a3b8; text-align: center; font-size: 0.85em; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #334155;">
        👤 <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?><br>
        <span style="text-transform: capitalize;"><?php echo htmlspecialchars($_SESSION['rol']); ?></span>
    </div>

   <a href="<?php echo $url_base; ?>/Punto_De_Venta/PuntoDeVenta.php"
       class="btn <?php echo ($activo == 'pos') ? 'btn-primary' : ''; ?>"
       style="text-decoration: none; text-align: left; color: white;">
       🛒 Punto de Venta
    </a>

     <a href="<?php echo $url_base; ?>/Inventarios/inventarios.php"
       class="btn <?php echo ($activo == 'inventarios') ? 'btn-primary' : ''; ?>"
       style="text-decoration: none; text-align: left; color: white;">
       📦 Inventarios
    </a>

   <a href="<?php echo $url_base; ?>/Articulos/articulos.php"
       class="btn <?php echo ($activo == 'articulos') ? 'btn-primary' : ''; ?>"
       style="text-decoration: none; text-align: left; color: white;">
       🏷️ Artículos (ABC)
    </a>

     <a href="<?php echo $url_base; ?>/Clientes/clientes.php"
       class="btn <?php echo ($activo == 'clientes') ? 'btn-primary' : ''; ?>"
       style="text-decoration: none; text-align: left; color: white;">
       👥 Clientes (ABC)
    </a>

     <a href="<?php echo $url_base; ?>/Reportes/reportes.php"
       class="btn <?php echo ($activo == 'reportes') ? 'btn-primary' : ''; ?>"
       style="text-decoration: none; text-align: left; color: white;">
       📊 Reportes
    </a>

    <a href="<?php echo $url_base; ?>/cerrar_sesion.php"
       class="btn btn-danger"
       style="text-decoration: none; text-align: center; margin-top: auto;">
       Cerrar Sesión
    </a>

</nav>
