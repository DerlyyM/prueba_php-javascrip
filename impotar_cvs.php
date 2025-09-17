<?php
include "conexion.php";

if (isset($_POST["importar"])) {
    try {
        if ($_FILES['archivo_csv']['error'] === UPLOAD_ERR_OK) {
            $archivoTmp = $_FILES['archivo_csv']['tmp_name'];

            if (($handle = fopen($archivoTmp, "r")) !== false) {
                // Leer encabezados y saltarlos
                fgetcsv($handle, 1000, ",");

                while (($datos = fgetcsv($handle, 1000, ",")) !== false) {
                    // Asegurar que vienen todos los campos
                    if (count($datos) < 9) {
                        continue;
                    }

                    // CSV esperado:
                    // documento, nombres, email, fecha_compra, codigo, nombre_producto, marca, cantidad, valor_unitario
                    $documento       = trim($datos[0]);
                    $nombres         = trim($datos[1]);
                    $email           = trim($datos[2]);
                    $fecha_compra    = trim($datos[3]);
                    $codigo          = trim($datos[4]);
                    $nombre_producto = trim($datos[5]);
                    $marca           = trim($datos[6]);
                    $cantidad        = (int) $datos[7];
                    $valor_unitario  = (float) $datos[8];
                    $valor_total     = $cantidad * $valor_unitario;

                    // 1. Insertar/actualizar proveedor
                    $stmt = $pdo->prepare("INSERT INTO proveedores (documento, nombres, email)
                                           VALUES (?, ?, ?)
                                           ON DUPLICATE KEY UPDATE nombres=?, email=?");
                    $stmt->execute([$documento, $nombres, $email, $nombres, $email]);

                    $proveedor_id = $pdo->lastInsertId();
                    if ($proveedor_id == 0) {
                        $q = $pdo->prepare("SELECT id FROM proveedores WHERE documento=?");
                        $q->execute([$documento]);
                        $proveedor_id = $q->fetchColumn();
                    }

                    // 2. Insertar/actualizar producto
                    $stmt = $pdo->prepare("INSERT INTO productos (codigo, nombre, marca)
                                           VALUES (?, ?, ?)
                                           ON DUPLICATE KEY UPDATE nombre=?, marca=?");
                    $stmt->execute([$codigo, $nombre_producto, $marca, $nombre_producto, $marca]);

                    $producto_id = $pdo->lastInsertId();
                    if ($producto_id == 0) {
                        $q = $pdo->prepare("SELECT id FROM productos WHERE codigo=?");
                        $q->execute([$codigo]);
                        $producto_id = $q->fetchColumn();
                    }

                    // 3. Insertar compra
                    $stmt = $pdo->prepare("INSERT INTO compras (proveedor_id, producto_id, fecha_compra, cantidad, valor_unitario, valor_total)
                                           VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$proveedor_id, $producto_id, $fecha_compra, $cantidad, $valor_unitario, $valor_total]);
                }

                fclose($handle);
                echo "<script>alert(' Datos importados correctamente desde el CSV'); window.location='index.php';</script>";
            } else {
                echo "<div class='alert alert-danger'> No se pudo abrir el archivo CSV.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'> Error al subir el archivo CSV.</div>";
        }
    } catch (PDOException $e) {
        echo " Error: " . $e->getMessage();
    }
}
?>
