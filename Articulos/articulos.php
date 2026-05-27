<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Catálogo de Artículos</title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>

    <div class="app-layout">
        
 <?php include 'navbar.php'; ?>
        
        <main class="main-content">
            <h2>🏷️ Módulo: Catálogo de Artículos (ABC)</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Administración de productos del sistema (Altas, Bajas lógicas y Cambios).</p>
            
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                
                <!-- Columna Izquierda: Formulario ABC -->
                <div class="module-card" style="flex: 1; min-width: 300px;">
                    <h3>Registrar / Modificar Artículo</h3>
                    <br>
                    <form>
                        <div class="form-group">
                            <label>Código de Barras:</label>
                            <input type="text" class="form-control" placeholder="Ej. 750123456789">
                        </div>
                        <div class="form-group">
                            <label>Descripción / Nombre:</label>
                            <input type="text" class="form-control" placeholder="Ej. Detergente Líquido 1L">
                        </div>
                        <div class="form-group">
                            <label>Subir Imagen de Producto:</label>
                            <input type="file" class="form-control">
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 20px;">
                            <button type="button" class="btn btn-primary">Guardar</button>
                            <button type="reset" class="btn" style="background: #94a3b8; color: white;">Limpiar</button>
                        </div>
                    </form>
                </div>
                
                <!-- Columna Derecha: Vista de Artículos Activos / Dados de baja -->
                <div class="module-card" style="flex: 2; min-width: 400px;">
                    <h3>Listado General de Artículos</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Imagen de muestra</th>
                                <th>Descripción</th>
                                <th>Estatus</th>
                                <th>Acción (Baja Lógica)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>123456789</td>
                                <td><div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 4px;"></div></td>
                                <td>Producto Activo Ejemplo</td>
                                <td><span class="badge badge-active">Alta</span></td>
                                <td><button class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8em;">Dar de Baja</button></td>
                            </tr>
                            <tr>
                                <td>987654321</td>
                                <td><div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 4px;"></div></td>
                                <td>Producto Desactivado Ejemplo</td>
                                <td><span class="badge badge-inactive">Baja</span></td>
                                <td><button class="btn btn-success" style="padding: 4px 8px; font-size: 0.8em;">Reactivar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </main>
    </div>

</body>
</html>
