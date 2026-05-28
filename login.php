<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Punto de Venta</title>

    <!-- Estilos generales del proyecto -->
    <link rel="stylesheet" href="style.css">

    <!-- Estilos propios del login -->
    <link rel="stylesheet" href="login.css">
</head>
<body class="login-body">

    <main class="login-layout">

        <!-- Panel izquierdo informativo -->
        <section class="login-info">
            <div class="brand-card">
                <div class="brand-icon">🛒</div>
                <h1>Punto de Venta</h1>
                <p>
                    Controla ventas, clientes, inventario, usuarios y cortes de caja
                    desde un solo sistema.
                </p>
            </div>

            <div class="benefits-list">
                <div class="benefit-item">
                    <div class="benefit-icon">📈</div>
                    <div>
                        <h3>Ventas bajo control</h3>
                        <p>Consulta tickets, movimientos y actividad del negocio.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">📦</div>
                    <div>
                        <h3>Inventario actualizado</h3>
                        <p>Supervisa entradas, salidas y productos con bajo stock.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">🔐</div>
                    <div>
                        <h3>Accesos por rol</h3>
                        <p>Cada usuario entra únicamente a las áreas permitidas.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Panel derecho login -->
        <section class="login-panel">
            <div class="login-card">
                <div class="login-card-header">
                    <div class="login-logo">🔐</div>
                    <h2>Bienvenida de nuevo</h2>
                    <p>Inicia sesión para acceder al sistema</p>
                </div>

                <!-- 
                    Por ahora lo mandamos a menu.php para probar el diseño.
                    Cuando hagamos el login funcional, cambiaremos action a validar_login.php
                -->
                <form action="menu.php" method="POST" class="login-form">

                    <div class="form-group">
                        <label for="usuario">Usuario</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input 
                                type="text" 
                                id="usuario" 
                                name="usuario"
                                class="form-control" 
                                placeholder="Ingresa tu usuario"
                                autocomplete="username"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input 
                                type="password" 
                                id="password" 
                                name="password"
                                class="form-control" 
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                                required
                            >
                        </div>
                    </div>

                    <div class="login-options">
                        <label class="remember-option">
                            <input type="checkbox" name="recordarme">
                            <span>Recordarme</span>
                        </label>

                        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="login-button">
                        Iniciar sesión
                    </button>
                </form>

                <div class="login-footer">
                    <p>Acceso exclusivo para usuarios autorizados</p>
                </div>
            </div>
        </section>

    </main>

</body>
</html>