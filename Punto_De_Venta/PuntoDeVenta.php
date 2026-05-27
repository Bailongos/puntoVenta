<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maqueta - Punto de Venta</title>
      <link rel="stylesheet" href="../style.css">
</head>
<body>

    <!-- app-layout: Divide la pantalla entre el menú lateral y el contenido principal -->
    <div class="app-layout">
        <?php include '../navbar.php'; ?>

        
        <!-- main-content: Área donde se despliega el módulo actual -->
        <main class="main-content">
            <h2>🛒 Módulo: Punto de Venta (Caja)</h2>
            <p style="margin-bottom: 20px; color: var(--secondary-color);">Interfaz principal del cajero para procesar compras y realizar cortes.</p>
            
            <!-- pos-layout: Estructura de dos columnas (Productos | Ticket) -->
            <div class="pos-layout">
                
                <!-- pos-products-grid: Cuadrícula automática para los productos disponibles -->
                <div class="pos-products-grid">
                    
                    <!-- product-card: Tarjeta individual para cada artículo con efecto hover -->
                    <div class="product-card">
                        <div style="background: #e2e8f0; height: 100px; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9em; color: #64748b;">📸 Imagen</div>
                        <h4>Producto de Muestra A</h4>
                        <p style="font-weight: bold; color: var(--primary-color); margin: 5px 0;">$25.00</p>
                        <button class="btn btn-success" style="padding: 5px 10px; font-size: 0.85em; width: 100%;">+ Agregar</button>
                    </div>

                    <div class="product-card">
                        <div style="background: #e2e8f0; height: 100px; border-radius: 4px; margin-bottom: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9em; color: #64748b;">📸 Imagen</div>
                        <h4>Producto de Muestra B</h4>
                        <p style="font-weight: bold; color: var(--primary-color); margin: 5px 0;">$14.50</p>
                        <button class="btn btn-success" style="padding: 5px 10px; font-size: 0.85em; width: 100%;">+ Agregar</button>
                    </div>
                    
                </div>

                <!-- pos-ticket-sidebar: Panel derecho que simula el ticket de compra física -->
                <div class="pos-ticket-sidebar">
                    <h3>🧾 Ticket Actual</h3>
                    <p style="font-size: 0.85em; color: var(--secondary-color);">Cliente: Público General</p>
                    
                    <!-- ticket-items: Contenedor con scroll para los artículos agregados -->
                    <div class="ticket-items">
                        <table style="width: 100%; margin-top: 10px; font-size: 0.9em; border-collapse: collapse;">
                            <tr style="border-bottom: 1px dashed var(--border-color);">
                                <td style="padding: 5px 0;">2 x Producto A</td>
                                <td style="text-align: right; padding: 5px 0;">$50.00</td>
                            </tr>
                        </table>
                    </div>
                    
                    <!-- ticket-total: Destaca el monto total a pagar -->
                    <div class="ticket-total">Total: $50.00</div>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button class="btn btn-success">💵 Cobrar Venta</button>
                        <button class="btn btn-primary" style="background-color: #0284c7;">✂️ Realizar Corte de Caja</button>
                        <button class="btn btn-danger">🚫 Cancelar</button>
                    </div>
                </div>
                
            </div>
        </main>
    </div>

</body>
</html>
