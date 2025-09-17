<?php include "conexion/bd.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Compras</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center text-white p-3 bg-primary rounded">Módulo de Compras - Mi Elementos</h2>
    <div class="row mt-4">
        
        <!-- Formulario manual -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">Registro Manual de Compras</div>
                <div class="card-body">
                    <form action="guardar.php" method="POST" id="formCompra">
                        <div class="mb-3">
                            <label class="form-label">Documento Proveedor</label>
                            <input type="text" name="documento" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombres">Nombre del proveedor</label>
                            <input type="text" name="nombre" class="form-control" required>

                         </div>
                        <div class="mb-3">
                            <label class="form-label">Email Proveedor</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Compra</label>
                            <input type="date" name="fecha_compra" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código Producto</label>
                            <input type="text" name="codigo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre Producto</label>
                            <input type="text" name="producto" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valor Unitario</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Guardar Compra</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Subida CSV -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">Importar Compras desde CSV</div>
                <div class="card-body">
                    <form action="importar_csv.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar archivo CSV</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Importar Archivo</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Validación simple con JS -->
<script>
document.getElementById('formCompra').addEventListener('submit', function(e){
    let doc = document.querySelector('[name="documento"]').value;
    if(doc.length < 5){
        alert("El documento debe tener al menos 5 caracteres");
        e.preventDefault();
    }
});
</script>
</body>
</html>

<?php if (isset($_GET['mensaje'])): ?>
    <?php if ($_GET['mensaje'] == 'ok'): ?>
        <div class="alert alert-success text-center"> Registro guardado con éxito.</div>
    <?php elseif ($_GET['mensaje'] == 'error'): ?>
        <div class="alert alert-danger text-center"> Error: <?php echo $_GET['detalle']; ?></div>
    <?php endif; ?>
<?php endif; ?>
