<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- login-wrapper: Centra todo el contenido en la pantalla completa -->
    <div class="login-wrapper">
        
        <!-- login-box: Tarjeta blanca con sombra para el formulario -->
        <div class="login-box">
            <h2>🔐 Iniciar Sesión</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Introduce tus credenciales de acceso</p>
            
            <form action="pos.html" method="GET">
                <!-- form-group: Separa cada campo con su respectiva etiqueta -->
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <!-- form-control: Estiliza el input de forma limpia con bordes suaves -->
                    <input type="text" id="usuario" class="form-control" placeholder="Ej. administrador" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <!-- btn y btn-primary: Botón azul con transiciones al pasar el mouse -->
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
                    Ingresar al Sistema
                </button>
            </form>
        </div>
    </div>

</body>
</html>
