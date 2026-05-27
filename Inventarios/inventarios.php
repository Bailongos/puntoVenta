<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Inventarios</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <div class="app-layout">
        
  <?php include '../navbar.php'; ?>

        
        <main class="main-content">
            <h2>📦 Módulo: Gestión de Inventarios</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Control de entradas (altas por factura) y salidas de stock.</p>
            
            <!-- module-card: Bloque blanco para agrupar el formulario de ajuste -->
            <div class="module-card">
                <h3>Ajuste Manual de Stock</h3>
                <br>
                <form style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 2; min-width: 200px;">
                        <label>Seleccionar Producto por Código:</label>
                        <input type="text" class="form-control" placeholder="Ingresa código de barras...">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 100px;">
                        <label>Cantidad:</label>
                        <input type="number" class="form-control" placeholder="Cantidad">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 150px;">
                        <label>Fecha de Movimiento:</label>
                        <input type="date" class="form-control" value="2026-05-26">
                    </div>
                    <div style="margin-bottom: 15px; display: flex; gap: 10px;">
                        <button type="button" class="btn btn-success">📥 Registrar Entrada</button>
                        <button type="button" class="btn btn-danger">📤 Registrar Salida</button>
                    </div>
                </form>
            </div>

            <!-- Otra module-card para la tabla de historial de movimientos -->
            <div class="module-card">
                <h3>Historial de Movimientos Recientes</h3>
                <!-- data-table: Tabla limpia con líneas divisorias y efecto hover en filas -->
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Tipo de Movimiento</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2026-05-26 14:30</td>
                            <td>987654321</td>
                            <td>Producto de Muestra A</td>
                            <!-- badge-active: Etiqueta verde suave para indicar entrada positiva -->
                            <td><span class="badge badge-active">Alta por Factura</span></td>
                            <td style="font-weight: bold; color: var(--success-color);">+50 unidades</td>
                        </tr>
                        <tr>
                            <td>2026-05-26 15:10</td>
                            <td>123456789</td>
                            <td>Producto de Muestra B</td>
                            <!-- badge-inactive: Etiqueta roja suave para indicar salida/baja -->
                            <td><span class="badge badge-inactive">Baja por Venta</span></td>
                            <td style="font-weight: bold; color: var(--danger-color);">-2 unidades</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>
