<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function tiene_permiso($permiso_nombre) {
    $permisos = $_SESSION['permisos'] ?? [];
    return in_array($permiso_nombre, $permisos);
}
