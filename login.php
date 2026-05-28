<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Punto de Venta</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css?v=2">
</head>
<body class="login-body">

    <main class="login-wrapper">

        <!-- Panel decorativo -->
        <section class="login-art">
            <div class="art-logo">
                <span>🛍️</span>
            </div>

            <div class="art-shape art-shape-1"></div>
            <div class="art-shape art-shape-2"></div>
            <div class="art-shape art-shape-3"></div>
            <div class="art-shape art-shape-4"></div>

            <div class="art-center-blob">
                <div class="art-center-icon">🏪</div>
            </div>
        </section>

        <!-- Card del login -->
        <section class="login-panel">
            <div class="login-card">
                <div class="login-header">
                    <h1>Login</h1>
                    <p>Accede con tus credenciales al sistema</p>
                </div>

                <!-- Después cambia action a validar_login.php -->
                <form action="menu.php" method="POST" class="login-form">
                    <div class="form-group">
                        <label for="usuario">Usuario</label>
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

                    <div class="form-group">
                        <label for="password">Contraseña</label>
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