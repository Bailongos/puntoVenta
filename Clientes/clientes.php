<?php
$modulo_activo = 'clientes';
$page_title = 'Clientes';
$page_search = 'Buscar clientes...';
$root_path = '../';
require '../dashboard-header.php';
?>

<div class="page-heading">
    <div>
        <span class="eyebrow">Directorio</span>
        <h1>Clientes</h1>
        <p>Registro oficial de clientes frecuentes o comerciales.</p>
    </div>
</div>

<div class="module-card">
    <h3>Formulario de Registro</h3>
    <br>
    <form class="flex-row" style="align-items: flex-end;">
        <div class="form-group" style="flex: 2; min-width: 250px;">
            <label>Nombre del Cliente:</label>
            <input type="text" class="form-control" placeholder="Nombre completo o Razón Social">
        </div>
        <div class="form-group" style="flex: 1; min-width: 180px;">
            <label>Teléfono de Contacto:</label>
            <input type="text" class="form-control" placeholder="Ej. 8717000000">
        </div>
        <button type="button" class="btn btn-primary"><span class="material-icons">person_add</span> Registrar Cliente</button>
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
                    <button class="btn btn-secondary btn-sm">Editar</button>
                    <button class="btn btn-danger btn-sm">Dar de Baja</button>
                </td>
            </tr>
            <tr>
                <td>CLI-002</td>
                <td>María Elena López</td>
                <td>8719876543</td>
                <td><span class="badge badge-inactive">Inactivo</span></td>
                <td>
                    <button class="btn btn-success btn-sm">Reactivar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php require '../dashboard-footer.php'; ?>
