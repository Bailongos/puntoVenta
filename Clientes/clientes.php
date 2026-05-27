<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Directorio de Clientes</title>
      <link rel="stylesheet" href="/style.css">
</head>
<body>

    <div class="app-layout">
        
        <nav style="width: 260px; background-color: #1e293b; padding: 20px; display: flex; flex-direction: column; gap: 10px; border-right: 1px solid var(--border-color); min-height: 100vh;">
            <h2 style="color: #38bdf8; text-align: center; margin-bottom: 20px;">🏪 Mi POS</h2>
            <a href="pos.html" class="btn btn-secondary" style="text-decoration: none; text-align: left; color: white;">🛒 Punto de Venta</a>
            <a href="inventarios.html" class="btn btn-secondary" style="text-decoration: none; text-align: left; color: white;">📦 Inventarios</a>
            <a href="articulos.html" class="btn btn-secondary" style="text-decoration: none; text-align: left; color: white;">🏷️ Artículos</a>
            <a href="clientes.html" class="btn btn-primary" style="text-decoration: none; text-align: left; color: white;">👥 Clientes</a>
            <a href="reportes.html" class="btn btn-secondary" style="text-decoration: none; text-align: left; color: white;">📊 Reportes</a>
            <a href="login.html" class="btn btn-danger" style="text-decoration: none; text-align: center; margin-top: auto;">Cerrar Sesión</a>
        </nav>

        
        <main class="main-content">
            <h2>👥 Módulo: Directorio de Clientes (ABC)</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Registro oficial de clientes frecuentes o comerciales.</p>
            
            <div class="module-card">
                <h3>Formulario de Registro (Información Estricta)</h3>
                <br>
                <form style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    <div class="form-group" style="flex: 2; min-width: 250px;">
                        <label>Nombre del Cliente:</label>
                        <input type="text" class="form-control" placeholder="Nombre completo o Razón Social">
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 180px;">
                        <label>Teléfono de Contacto:</label>
                        <input type="text" class="form-control" placeholder="Ej. 8717000000">
                    </div>
                    <button type="button" class="btn btn-primary" style="margin-bottom: 15px; height: 42px;">Registrar Cliente</button>
                </form>
            </div>

            <div class="module-card">
                <h3>Clientes Registrados</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID Cliente</th>
                            <th>Nombre Completo</th>
                            <th>Teléfono / Contacto</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CLI-001</td>
                            <td>Juan Pérez Gómez</td>
                            <td>8711234567</td>
                            <td><span class="badge badge-active">Activo</span></td>
                            <td>
                                <button class="btn" style="background: #e2e8f0; color: var(--text-color); padding: 4px 8px; font-size: 0.8em;">Editar</button>
                                <button class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8em;">Dar de Baja</button>
                            </td>
                        </tr>
                        <tr>
                            <td>CLI-002</td>
                            <td>María Elena López</td>
                            <td>8719876543</td>
                            <td><span class="badge badge-inactive">Inactivo</span></td>
                            <td>
                                <button class="btn btn-success" style="padding: 4px 8px; font-size: 0.8em;">Reactivar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>
</html>
