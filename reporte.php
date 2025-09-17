<?php
include "conexion/bd.php";

// Consulta uniendo compras con proveedores y productos
$sql = "SELECT c.id_compra, c.fecha_compra, c.cantidad, c.valor_unitario, c.valor_total,
               p.documento, p.nombres AS nombre_proveedor, p.email,
               pr.codigo, pr.nombre AS nombre_producto, pr.marca
        FROM compras c
        INNER JOIN proveedores p ON c.proveedor_id = p.id_proveedor
        INNER JOIN productos pr ON c.producto_id = pr.id_producto
        ORDER BY c.fecha_compra DESC";
$stmt = $pdo->query($sql);
$compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center text-white p-3 bg-dark rounded">Reporte de Compras</h2>

    <div class="mb-3 text-end">
        <a href="index.php" class="btn btn-primary">Nueva Compra / CSV</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered shadow">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Documento</th>
                    <th>Proveedor</th>
                    <th>Email</th>
                    <th>Fecha Compra</th>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Marca</th>
                    <th>Cantidad</th>
                    <th>Valor Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($compras) > 0): ?>
                    <?php foreach ($compras as $fila): ?>
                        <tr>
                            <td><?= $fila['id_compra'] ?></td>
                            <td><?= $fila['documento'] ?></td>
                            <td><?= $fila['nombre_proveedor'] ?></td>
                            <td><?= $fila['email'] ?></td>
                            <td><?= $fila['fecha_compra'] ?></td>
                            <td><?= $fila['codigo'] ?></td>
                            <td><?= $fila['nombre_producto'] ?></td>
                            <td><?= $fila['marca'] ?></td>
                            <td><?= $fila['cantidad'] ?></td>
                            <td>$<?= number_format($fila['valor_unitario'], 0, ',', '.') ?></td>
                            <td><b>$<?= number_format($fila['valor_total'], 0, ',', '.') ?></b></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="11" class="text-center">No hay compras registradas todavía.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
