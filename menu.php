<?php 
// 1. Definimos una variable para el módulo activo. 
// Como estamos en el inicio, podemos ponerle 'inicio' para que no resalte ningún botón por ahora.
$modulo_activo = 'inicio'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal - Punto de Venta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="app-layout">
        
        <?php include 'navbar.php'; ?>
        
        <main class="main-content" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
            
            <h1 style="font-size: 5rem; color: var(--secondary-color); text-transform: uppercase; letter-spacing: 2px;">
                Bienvenido
            </h1>
            
        </main>
        
    </div>

</body>
</html>