<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}
$modulo_activo = 'reportes';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Reportes</title>
      <link rel="stylesheet" href="../style.css">
</head>
<body>

    <div class="app-layout">
        
         <?php include '../navbar.php'; ?>

        
        <main class="main-content">
            <h2>📊 Módulo: Reportes de Ventas y Compras</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Consulta transacciones realizadas, filtra históricos y visualiza los tickets de compra.</p>
            
            <div class="module-card">
                <h3>Filtros de Auditoría</h3>
                <!-- report-filters: Disposición horizontal alineada en la base para campos de búsqueda -->
                <form class="report-filters" style="flex-wrap: wrap;">
                    <div class="form-group" style="min-width: 150px;">
                        <label>Desde:</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group" style="min-width: 150px;">
                        <label>Hasta:</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="form-group" style="min-width: 150px;">
                        <label>Filtrar por Folio:</label>
                        <input type="text" class="form-control" placeholder="Ej. 1024">
                    </div>
                    <button type="button" class="btn btn-primary" style="height: 42px; margin-bottom: 15px;">🔍 Aplicar Filtros</button>
                </form>
            </div>

            <div class="module-card">
                <h3>Bitácora de Ventas y Compras Realizadas</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Tipo</th>
                            <th>Fecha / Hora</th>
                            <th>Cliente Relacionado</th>
                            <th>Monto Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#V-1024</td>
                            <td><span class="badge badge-active" style="background: #e0f2fe; color: #0369a1;">Venta</span></td>
                            <td>2026-05-26 12:40</td>
                            <td>Público General</td>
                            <td style="font-weight: bold; color: var(--success-color);">$50.00</td>
                            <td><button class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85em;">🖨️ Ver Ticket</button></td>
                        </tr>
                        <tr>
                            <td>#C-5011</td>
                            <td><span class="badge badge-inactive" style="background: #fef3c7; color: #b45309;">Compra (Proveedor)</span></td>
                            <td>2026-05-25 18:20</td>
                            <td>Proveedor Central</td>
                            <td style="font-weight: bold; color: var(--danger-color);">$1,200.00</td>
                            <td><button class="btn btn-primary" style="padding: 5px 10px; font-size: 0.85em;">🖨️ Ver Ticket</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>
