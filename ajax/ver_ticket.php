<?php
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../funciones.php';

header('Content-Type: application/json; charset=utf-8');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

$venta = $conn->query("SELECT v.*, u.usuario FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id WHERE v.id = $id")->fetch_assoc();
if (!$venta) {
    echo json_encode(['success' => false, 'error' => 'Venta no encontrada']);
    exit;
}

$items = $conn->query("SELECT vd.*, a.descripcion, a.codigo_barras FROM ventas_detalle vd JOIN articulos a ON vd.producto_id = a.id WHERE vd.venta_id = $id");

$lista = [];
while ($item = $items->fetch_assoc()) {
    $lista[] = $item;
}

echo json_encode([
    'success' => true,
    'folio' => $venta['folio'],
    'fecha' => $venta['created_at'],
    'usuario' => $venta['usuario'] ?? 'N/A',
    'total' => number_format($venta['total'], 2),
    'items' => $lista
]);
