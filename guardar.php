<?php
include "conexion/bd.php";

if ($_POST) {
    $documento = $_POST['documento'];
    $nombre = $_POST['nombre'];   // usa singular
    $email = $_POST['email'];
    $fecha = $_POST['fecha_compra'];
    $codigo = $_POST['codigo'];
    $nombre_producto = $_POST['nombre_producto'];
    $marca = $_POST['marca'];
    $cantidad = $_POST['cantidad'];
    $valor_unitario = $_POST['valor_unitario'];

    try {
        // Guardar proveedor
        $stmt = $pdo->prepare("INSERT INTO proveedores (documento, nombre, email) 
                               VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE nombre=?, email=?");
        $stmt->execute([$documento, $nombre, $email, $nombre, $email]);

        // Obtener ID del proveedor
        $stmt = $pdo->prepare("SELECT id FROM proveedores WHERE documento=?");
        $stmt->execute([$documento]);
        $proveedor_id = $stmt->fetchColumn();

        // Guardar producto
        $stmt = $pdo->prepare("INSERT INTO productos (codigo, nombre, marca) 
                               VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE nombre=?, marca=?");
        $stmt->execute([$codigo, $nombre_producto, $marca, $nombre_producto, $marca]);

        // Obtener ID del producto
        $stmt = $pdo->prepare("SELECT id FROM productos WHERE codigo=?");
        $stmt->execute([$codigo]);
        $producto_id = $stmt->fetchColumn();

        // Calcular total
        $valor_total = $cantidad * $valor_unitario;

        // Guardar compra
        $stmt = $pdo->prepare("INSERT INTO compras (fecha_compra, cantidad, valor_unitario, valor_total, proveedor_id, producto_id)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fecha, $cantidad, $valor_unitario, $valor_total, $proveedor_id, $producto_id]);

        // Redirigir con mensaje
        header("Location: index.php?mensaje=ok");
        exit;

    } catch (Exception $e) {
        header("Location: index.php?mensaje=error&detalle=" . urlencode($e->getMessage()));
        exit;
    }
}
?>
