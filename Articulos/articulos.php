<?php
$modulo_activo = 'articulos';
$page_title = 'Artículos';
$page_search = 'Buscar artículos...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Catálogo</span>
        <h1>Artículos</h1>
        <p>Administración de productos del sistema (Altas, Bajas lógicas y Cambios).</p>
    </div>
</div>

<div class="module-card">
    <h3>Registrar / Modificar Artículo</h3>
    <br>
    <form class="flex-row" style="align-items: flex-end;">
        <div class="form-group" style="flex: 2; min-width: 200px;">
            <label>Código de Barras:</label>
            <input type="text" class="form-control" placeholder="Ej. 750123456789">
        </div>
        <div class="form-group" style="flex: 2; min-width: 250px;">
            <label>Descripción / Nombre:</label>
            <input type="text" class="form-control" placeholder="Ej. Detergente Líquido 1L">
        </div>
        <div class="form-group" style="flex: 1; min-width: 180px;">
            <label>Subir Imagen:</label>
            <input type="file" class="form-control">
        </div>
        <div class="flex-row" style="margin-bottom:15px;">
            <button type="submit" class="btn btn-primary"><span class="material-icons">save</span> Guardar</button>
            <button type="reset" class="btn btn-secondary"><span class="material-icons">clear</span> Limpiar</button>
        </div>
    </form>
</div>

<div class="module-card">
    <h3>Listado General de Artículos</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>123456789</td>
                <td><div class="product-img-placeholder" style="width:44px;height:44px;"><span class="material-icons" style="font-size:18px;">image</span></div></td>
                <td>Producto Activo Ejemplo</td>
                <td><span class="badge badge-active">Alta</span></td>
                <td><button class="btn btn-danger btn-sm">Dar de Baja</button></td>
            </tr>
            <tr>
                <td>987654321</td>
                <td><div class="product-img-placeholder" style="width:44px;height:44px;"><span class="material-icons" style="font-size:18px;">image</span></div></td>
                <td>Producto Desactivado Ejemplo</td>
                <td><span class="badge badge-inactive">Baja</span></td>
                <td><button class="btn btn-success btn-sm">Reactivar</button></td>
            </tr>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
