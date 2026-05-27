<?php
// navbar.php
// Este archivo es el componente de navegación lateral reutilizable.

// Recibimos la variable desde la página que lo invoca, si no existe, la dejamos vacía
$activo = isset($modulo_activo) ? $modulo_activo : '';
?>

<nav style="width: 260px; background-color: #1e293b; padding: 20px; display: flex; flex-direction: column; gap: 10px; border-right: 1px solid var(--border-color); min-height: 100vh;">
    
    <h2 style="color: #38bdf8; text-align: center; margin-bottom: 20px;">🏪 Mi POS</h2>
    
    <a href="pos.php" 
       class="btn <?php echo ($activo == 'pos') ? 'btn-primary' : ''; ?>" 
       style="text-decoration: none; text-align: left; color: white;">
       🛒 Punto de Venta
    </a>
    
    <a href="inventarios.php" 
       class="btn <?php echo ($activo == 'inventarios') ? 'btn-primary' : ''; ?>" 
       style="text-decoration: none; text-align: left; color: white;">
       📦 Inventarios
    </a>
    
    <a href="articulos.php" 
       class="btn <?php echo ($activo == 'articulos') ? 'btn-primary' : ''; ?>" 
       style="text-decoration: none; text-align: left; color: white;">
       🏷️ Artículos (ABC)
    </a>
    
    <a href="clientes.php" 
       class="btn <?php echo ($activo == 'clientes') ? 'btn-primary' : ''; ?>" 
       style="text-decoration: none; text-align: left; color: white;">
       👥 Clientes (ABC)
    </a>
    
    <a href="reportes.php" 
       class="btn <?php echo ($activo == 'reportes') ? 'btn-primary' : ''; ?>" 
       style="text-decoration: none; text-align: left; color: white;">
       📊 Reportes
    </a>
    
    <a href="login.php" 
       class="btn btn-danger" 
       style="text-decoration: none; text-align: center; margin-top: auto;">
       Cerrar Sesión
    </a>
    
</nav>