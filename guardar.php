<?php
include "conexion/bd.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Datos del proveedor
        $documento = $_POST['documento'];
        $nombres   = $_POST['nombres'];
        $email     = $_POST['email'];

        // Datos del producto
        $codigo          = $_POST['codigo'];
        $nombre_producto = $_POST['nombre_producto'];
        $marca           = $_POST['marca'];

        // Datos de la compra
        $fecha_compra   = $_POST['fecha_compra'];
        $cantidad       = $_POST['cantidad'];
        $valor_unitario = $_POST['valor_unitario'];
        $valor_total    = $cantidad * $valor_unitario;

        // 1. Insertar o actualizar proveedor
        $stmt = $pdo->prepare("INSERT INTO proveedores (documento, nombres, email)
                               VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE nombres=?, email=?");
        $stmt->execute([$documento, $nombres, $email, $nombres, $email]);

        // Obtener id del proveedor
        $proveedor_id = $pdo->lastInsertId();
        if ($proveedor_id == 0) {
            $q = $pdo->prepare("SELECT id FROM proveedores WHERE documento=?");
            $q->execute([$documento]);
            $proveedor_id = $q->fetchColumn();
        }

        // 2. Insertar o actualizar producto
        $stmt = $pdo->prepare("INSERT INTO productos (codigo, nombre, marca)
                               VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE nombre=?, marca=?");
        $stmt->execute([$codigo, $nombre_producto, $marca, $nombre_producto, $marca]);

        // Obtener id del producto
        $producto_id = $pdo->lastInsertId();
        if ($producto_id == 0) {
            $q = $pdo->prepare("SELECT id FROM productos WHERE codigo=?");
            $q->execute([$codigo]);
            $producto_id = $q->fetchColumn();
        }

        // 3. Insertar compra
        $sql = "INSERT INTO compras (proveedor_id, producto_id, fecha_compra, cantidad, valor_unitario, valor_total)
                VALUES (:proveedor_id, :producto_id, :fecha_compra, :cantidad, :valor_unitario, :valor_total)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':proveedor_id'   => $proveedor_id,
            ':producto_id'    => $producto_id,
            ':fecha_compra'   => $fecha_compra,
            ':cantidad'       => $cantidad,
            ':valor_unitario' => $valor_unitario,
            ':valor_total'    => $valor_total
        ]);

        echo "<script>alert(' Registro guardado con éxito'); window.location='index.php';</script>";
    } catch (PDOException $e) {
        echo " Error: " . $e->getMessage();
    }
}
?>

