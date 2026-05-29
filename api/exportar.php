<?php
require_once __DIR__ . '/../config/conexion.php';

$tipo = $_GET['tipo'] ?? 'excel';
$fecha = $_GET['fecha'] ?? date('Y-m-d');
$desde = $_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'));
$hasta = $_GET['hasta'] ?? date('Y-m-d');
$folio = trim($_GET['folio'] ?? '');
$origen = $_GET['origen'] ?? '';

if ($origen === 'corte') {
    $where = "WHERE DATE(v.created_at) = '$fecha'";
    $titulo = "Corte de Caja - $fecha";
} else {
    $where = "WHERE DATE(v.created_at) >= '$desde' AND DATE(v.created_at) <= '$hasta'";
    $titulo = "Reporte de Ventas - $desde a $hasta";
    if ($folio) {
        $where .= " AND v.folio LIKE '%" . $conn->real_escape_string($folio) . "%'";
    }
}

$where .= " AND v.estatus = 'completada'";

$ventas = $conn->query("SELECT v.folio, v.total, v.created_at, u.usuario FROM ventas v LEFT JOIN usuarios u ON v.usuario_id = u.id $where ORDER BY v.created_at DESC");

if ($tipo === 'excel') {
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $titulo . '.xls"');
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
    echo '<meta charset="UTF-8">';
    echo '<table border="1">';
    echo '<tr><th colspan="4" style="font-size:16px;font-weight:bold;">' . htmlspecialchars($titulo) . '</th></tr>';
    echo '<tr><th>Folio</th><th>Fecha</th><th>Atendi&oacute;</th><th>Total</th></tr>';
    $total_ingresos = 0;
    while ($v = $ventas->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($v['folio']) . '</td>';
        echo '<td>' . htmlspecialchars($v['created_at']) . '</td>';
        echo '<td>' . htmlspecialchars($v['usuario'] ?? '-') . '</td>';
        echo '<td style="text-align:right;">$' . number_format($v['total'], 2) . '</td>';
        echo '</tr>';
        $total_ingresos += $v['total'];
    }
    echo '<tr><td colspan="3" style="font-weight:bold;text-align:right;">Total:</td><td style="font-weight:bold;text-align:right;">$' . number_format($total_ingresos, 2) . '</td></tr>';
    echo '</table>';
} else {
    header('Content-Type: text/html; charset=utf-8');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $titulo; ?></title>
        <style>
            body { font-family: 'Inter', Arial, sans-serif; margin: 20px; color: #0b1c2f; }
            h1 { font-size: 20px; margin-bottom: 4px; }
            p { color: #64748b; font-size: 13px; margin: 0 0 20px; }
            table { width: 100%; border-collapse: collapse; font-size: 13px; }
            th { background: #f4f6f9; padding: 10px 12px; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; border-bottom: 2px solid #e2e8f0; }
            td { padding: 10px 12px; border-bottom: 1px solid #f1f4f7; color: #475569; }
            .total-row td { font-weight: 700; border-top: 2px solid #e2e8f0; }
            .text-right { text-align: right; }
            @media print { body { margin: 0; } }
        </style>
    </head>
    <body onload="window.print()">
        <h1><?php echo htmlspecialchars($titulo); ?></h1>
        <p>Reporte generado el <?php echo date('d/m/Y H:i'); ?></p>
        <table>
            <thead>
                <tr><th>Folio</th><th>Fecha</th><th>Atendi&oacute;</th><th class="text-right">Total</th></tr>
            </thead>
            <tbody>
                <?php
                $total_ingresos = 0;
                $ventas->data_seek(0);
                while ($v = $ventas->fetch_assoc()):
                    $total_ingresos += $v['total'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($v['folio']); ?></td>
                    <td><?php echo htmlspecialchars($v['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($v['usuario'] ?? '-'); ?></td>
                    <td class="text-right">$<?php echo number_format($v['total'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr class="total-row"><td colspan="3" class="text-right">Total:</td><td class="text-right">$<?php echo number_format($total_ingresos, 2); ?></td></tr>
            </tfoot>
        </table>
    </body>
    </html>
    <?php
}
