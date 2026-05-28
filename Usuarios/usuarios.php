<?php
$modulo_activo = 'usuarios';
$page_title = 'Usuarios';
$page_search = 'Buscar usuarios, roles o estados...';
$root_path = '../';
require '../dashboard-header.php';

$total_usuarios = 0;
$usuarios_activos = 0;
$usuarios_inactivos = 0;
$total_administradores = 0;
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Administración</span>
        <h1>Usuarios</h1>
        <p>Administra las cuentas, roles y accesos del sistema.</p>
    </div>
    <a href="#formulario-usuario" class="btn btn-primary" style="min-height:46px;">+ Nuevo usuario</a>
</div>

<section class="summary-grid">
    <article class="summary-card">
        <div>
            <p>Total de usuarios</p>
            <h2><?php echo $total_usuarios; ?></h2>
            <span class="neutral">Sin usuarios registrados</span>
        </div>
        <div class="summary-icon green">👥</div>
    </article>
    <article class="summary-card">
        <div>
            <p>Usuarios activos</p>
            <h2><?php echo $usuarios_activos; ?></h2>
            <span class="neutral">Sin usuarios activos</span>
        </div>
        <div class="summary-icon blue">✅</div>
    </article>
    <article class="summary-card">
        <div>
            <p>Usuarios inactivos</p>
            <h2><?php echo $usuarios_inactivos; ?></h2>
            <span class="neutral">Sin usuarios inactivos</span>
        </div>
        <div class="summary-icon orange">⏸️</div>
    </article>
    <article class="summary-card">
        <div>
            <p>Administradores</p>
            <h2><?php echo $total_administradores; ?></h2>
            <span class="neutral">Sin administradores registrados</span>
        </div>
        <div class="summary-icon green">🛡️</div>
    </article>
</section>

<div style="display: flex; gap: 20px;">

    <div class="module-card" style="flex: 1.6;">
        <h3>Listado de usuarios</h3>
        <p style="color: var(--dash-muted); font-size: 13px; margin: 0 0 15px;">Consulta, edita, activa o desactiva cuentas del sistema.</p>

        <div style="display: flex; gap: 10px; margin-bottom: 15px;">
            <select class="form-control" style="width: auto; min-width: 160px;">
                <option>Todos los roles</option>
                <option>Administrador</option>
                <option>Gerente</option>
                <option>Cajero</option>
                <option>Almacén</option>
            </select>
            <select class="form-control" style="width: auto; min-width: 160px;">
                <option>Todos los estados</option>
                <option>Activos</option>
                <option>Inactivos</option>
            </select>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="empty-state">
                        No hay usuarios registrados por el momento.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="module-card" style="flex: 1;" id="formulario-usuario">
        <h3>Nuevo usuario</h3>
        <p style="color: var(--dash-muted); font-size: 13px; margin: 0 0 15px;">Formulario visual. Después se conectará con la base de datos.</p>

        <form action="#" method="POST">
            <div class="form-group">
                <label for="nombre_completo">Nombre completo</label>
                <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" placeholder="Nombre completo del usuario">
            </div>
            <div class="form-group">
                <label for="username">Nombre de usuario</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Ej. cajero01">
            </div>
            <div class="form-group">
                <label for="password">Contraseña temporal</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Contraseña temporal">
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" class="form-control">
                    <option value="">Selecciona un rol</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Gerente">Gerente</option>
                    <option value="Cajero">Cajero</option>
                    <option value="Almacen">Almacén</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado" class="form-control">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div style="margin: 20px 0; padding: 15px; background: var(--dash-bg); border-radius: 12px;">
                <h4 style="margin: 0 0 10px;">Permisos del rol</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Inicio</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Punto de venta</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Clientes</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Artículos</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Inventario</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Reportes</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Usuarios</span>
                    <span class="badge badge-active" style="background: var(--dash-primary-soft); color: var(--dash-primary-dark);">Corte de caja</span>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="reset" class="btn" style="background: var(--dash-border); color: var(--dash-text);">Limpiar</button>
                <button type="submit" class="btn btn-primary">Guardar usuario</button>
            </div>
        </form>
    </div>

</div>

<div class="module-card">
    <h3>Permisos por rol</h3>
    <p style="color: var(--dash-muted); font-size: 13px; margin: 5px 0 0;">Vista general de los accesos principales que tendrá cada tipo de usuario.</p>
    <table class="data-table">
        <thead>
            <tr>
                <th>Módulo</th>
                <th>Admin</th>
                <th>Gerente</th>
                <th>Cajero</th>
                <th>Almacén</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Inicio</td><td>✓</td><td>✓</td><td>✓</td><td>✓</td></tr>
            <tr><td>Punto de Venta</td><td>✓</td><td>✓</td><td>✓</td><td>—</td></tr>
            <tr><td>Clientes</td><td>✓</td><td>✓</td><td>✓</td><td>—</td></tr>
            <tr><td>Artículos</td><td>✓</td><td>✓</td><td>—</td><td>✓</td></tr>
            <tr><td>Inventario</td><td>✓</td><td>✓</td><td>—</td><td>✓</td></tr>
            <tr><td>Reportes</td><td>✓</td><td>✓</td><td>—</td><td>—</td></tr>
            <tr><td>Usuarios</td><td>✓</td><td>—</td><td>—</td><td>—</td></tr>
            <tr><td>Corte de Caja</td><td>✓</td><td>✓</td><td>✓</td><td>—</td></tr>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
