<?php
$modulo_activo = 'usuarios';

// Temporales.
// Después se reemplazan por $_SESSION al conectar login y BD.
$usuario_nombre = 'Usuario';
$usuario_rol = 'Rol';
$usuario_iniciales = 'US';

// Datos iniciales vacíos.
// Después vendrán desde la tabla usuarios.
$total_usuarios = 0;
$usuarios_activos = 0;
$usuarios_inactivos = 0;
$total_administradores = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Punto de Venta</title>

    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../menu.css?v=2">
    <link rel="stylesheet" href="usuarios.css?v=1">
</head>
<body class="dashboard-body">

    <div class="dashboard-layout">

        <?php include '../navbar.php'; ?>

        <section class="dashboard-content">

            <header class="dashboard-topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" type="button">☰</button>

                    <div class="search-box">
                        <span>🔎</span>
                        <input type="text" placeholder="Buscar usuarios, roles o estados...">
                    </div>
                </div>

                <div class="topbar-right">
                    <button class="notification-button" type="button">
                        🔔
                        <span class="notification-badge">0</span>
                    </button>

                    <div class="user-profile">
                        <div class="user-avatar"><?php echo $usuario_iniciales; ?></div>
                        <div>
                            <strong><?php echo $usuario_nombre; ?></strong>
                            <span><?php echo $usuario_rol; ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="dashboard-main">

                <div class="page-heading">
                    <div>
                        <span class="eyebrow">Administración</span>
                        <h1>Usuarios</h1>
                        <p>Administra las cuentas, roles y accesos del sistema.</p>
                    </div>

                    <a href="#formulario-usuario" class="primary-action">
                        + Nuevo usuario
                    </a>
                </div>

                <section class="summary-grid users-summary-grid">
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

                <section class="users-grid">

                    <article class="panel-card users-table-card">
                        <div class="panel-header">
                            <div>
                                <h3>Listado de usuarios</h3>
                                <p>Consulta, edita, activa o desactiva cuentas del sistema.</p>
                            </div>

                            <div class="table-tools">
                                <select>
                                    <option>Todos los roles</option>
                                    <option>Administrador</option>
                                    <option>Gerente</option>
                                    <option>Cajero</option>
                                    <option>Almacén</option>
                                </select>

                                <select>
                                    <option>Todos los estados</option>
                                    <option>Activos</option>
                                    <option>Inactivos</option>
                                </select>
                            </div>
                        </div>

                        <div class="users-table-wrapper">
                            <table class="users-table">
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
                    </article>

                    <aside class="panel-card user-form-card" id="formulario-usuario">
                        <div class="panel-header">
                            <div>
                                <h3>Nuevo usuario</h3>
                                <p>Formulario visual. Después se conectará con la base de datos.</p>
                            </div>
                        </div>

                        <form action="#" method="POST" class="user-form">
                            <div class="form-row">
                                <label for="nombre_completo">Nombre completo</label>
                                <input 
                                    type="text" 
                                    id="nombre_completo" 
                                    name="nombre_completo" 
                                    placeholder="Nombre completo del usuario"
                                >
                            </div>

                            <div class="form-row">
                                <label for="username">Nombre de usuario</label>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username" 
                                    placeholder="Ej. cajero01"
                                >
                            </div>

                            <div class="form-row">
                                <label for="password">Contraseña temporal</label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Contraseña temporal"
                                >
                            </div>

                            <div class="form-row">
                                <label for="rol">Rol</label>
                                <select id="rol" name="rol">
                                    <option value="">Selecciona un rol</option>
                                    <option value="Administrador">Administrador</option>
                                    <option value="Gerente">Gerente</option>
                                    <option value="Cajero">Cajero</option>
                                    <option value="Almacen">Almacén</option>
                                </select>
                            </div>

                            <div class="form-row">
                                <label for="estado">Estado</label>
                                <select id="estado" name="estado">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>

                            <div class="permissions-preview">
                                <h4>Permisos del rol</h4>

                                <div class="permission-list">
                                    <span>Inicio</span>
                                    <span>Punto de venta</span>
                                    <span>Clientes</span>
                                    <span>Artículos</span>
                                    <span>Inventario</span>
                                    <span>Reportes</span>
                                    <span>Usuarios</span>
                                    <span>Corte de caja</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="reset" class="secondary-button">
                                    Limpiar
                                </button>

                                <button type="submit" class="save-button">
                                    Guardar usuario
                                </button>
                            </div>
                        </form>
                    </aside>

                </section>

                <section class="panel-card roles-card">
                    <div class="panel-header">
                        <div>
                            <h3>Permisos por rol</h3>
                            <p>Vista general de los accesos principales que tendrá cada tipo de usuario.</p>
                        </div>
                    </div>

                    <div class="roles-table-wrapper">
                        <table class="roles-table">
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
                                <tr>
                                    <td>Inicio</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                </tr>
                                <tr>
                                    <td>Punto de Venta</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                </tr>
                                <tr>
                                    <td>Clientes</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                </tr>
                                <tr>
                                    <td>Artículos</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                    <td>✓</td>
                                </tr>
                                <tr>
                                    <td>Inventario</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                    <td>✓</td>
                                </tr>
                                <tr>
                                    <td>Reportes</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                    <td>—</td>
                                </tr>
                                <tr>
                                    <td>Usuarios</td>
                                    <td>✓</td>
                                    <td>—</td>
                                    <td>—</td>
                                    <td>—</td>
                                </tr>
                                <tr>
                                    <td>Corte de Caja</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>✓</td>
                                    <td>—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

            </main>

        </section>

    </div>

</body>
</html>