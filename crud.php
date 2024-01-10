<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Operación de creación (Create)
    if (isset($_POST["crear"])) {
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        // Verificar si ya existe un producto con el mismo nombre
        $sqlVerificar = "SELECT * FROM productos WHERE nombre='$nombre'";
        $resultadoVerificar = $conexion->query($sqlVerificar);

        if ($resultadoVerificar->num_rows > 0) {
            echo "Error: Ya existe un producto con el mismo nombre.";
        } else {
            // Si no existe, proceder con la inserción
            $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad) VALUES ('$nombre', '$descripcion', $precio, $cantidad)";

            if ($conexion->query($sql) === TRUE) {
                // Producto creado con éxito
                // Puedes mostrar un mensaje de éxito o realizar otras acciones si es necesario
            } else {
                echo "Error al crear el producto: " . $conexion->error;
            }
        }
    }

    // Operación de actualización (Update)
    if (isset($_POST["actualizar"])) {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];
        $cantidad = $_POST["cantidad"];

        $sql = "UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio=$precio, cantidad=$cantidad WHERE id=$id";

        if ($conexion->query($sql) === TRUE) {
            // Producto actualizado con éxito
            // Puedes mostrar un mensaje de éxito o realizar otras acciones si es necesario
        } else {
            echo "Error al actualizar el producto: " . $conexion->error;
        }
    }
}

// Operación de eliminación (Delete)
if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $sql = "DELETE FROM productos WHERE id=$id";

    if ($conexion->query($sql) === TRUE) {
        // Producto eliminado con éxito
        // Puedes mostrar un mensaje de éxito o realizar otras acciones si es necesario
    } else {
        echo "Error al eliminar el producto: " . $conexion->error;
    }
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>CRUD de Productos</title>
    <style>
        .formulario-inline {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>CRUD de Productos</h2>

    <form action="crud.php" method="POST" class="formulario">
        <h3>Agregar Producto</h3>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion"></textarea>
        <label for="precio">Precio:</label>
        <input type="number" name="precio" step="0.01" required>
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" required>
        <button type="submit" name="crear">Agregar Producto</button>
    </form>

    <div class="lista-productos">
        <h3>Lista de Productos</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
            <?php
            include 'conexion.php';

            $sql = "SELECT * FROM productos";
            $resultado = $conexion->query($sql);

            while ($fila = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["id"] . "</td>";
                echo "<td><span id='nombre_" . $fila["id"] . "'>" . $fila["nombre"] . "</span><input type='text' id='edit_nombre_" . $fila["id"] . "' style='display:none;' value='" . $fila["nombre"] . "'></td>";
                echo "<td><span id='descripcion_" . $fila["id"] . "'>" . $fila["descripcion"] . "</span><textarea id='edit_descripcion_" . $fila["id"] . "' style='display:none;'>" . $fila["descripcion"] . "</textarea></td>";
                echo "<td><span id='precio_" . $fila["id"] . "'>" . $fila["precio"] . "</span><input type='number' step='0.01' id='edit_precio_" . $fila["id"] . "' style='display:none;' value='" . $fila["precio"] . "'></td>";
                echo "<td><span id='cantidad_" . $fila["id"] . "'>" . $fila["cantidad"] . "</span><input type='number' id='edit_cantidad_" . $fila["id"] . "' style='display:none;' value='" . $fila["cantidad"] . "'></td>";
                echo "<td>
                        <button type='button' onclick='editRow(" . $fila["id"] . ")'>Editar</button>
                        <button type='button' onclick='saveRow(" . $fila["id"] . ")' style='display:none;'>Guardar</button>
                        <a href='crud.php?eliminar=" . $fila["id"] . "' onclick='return confirm(\"¿Estás seguro?\")'>Eliminar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
function editRow(id) {
    // Ocultar datos y mostrar campos de edición
    toggleElementDisplay('nombre', id);
    toggleElementDisplay('descripcion', id);
    toggleElementDisplay('precio', id);
    toggleElementDisplay('cantidad', id);

    // Ocultar botón de editar y mostrar botón de guardar
    toggleButtonDisplay(id, 'edit', 'save');
}

function saveRow(id) {
    // Mostrar datos y ocultar campos de edición
    toggleElementDisplay('nombre', id);
    toggleElementDisplay('descripcion', id);
    toggleElementDisplay('precio', id);
    toggleElementDisplay('cantidad', id);

    // Ocultar botón de guardar y mostrar botón de editar
    toggleButtonDisplay(id, 'save', 'edit');
}

function toggleElementDisplay(fieldName, id) {
    document.getElementById(fieldName + '_' + id).style.display = 'inline-block';
    document.getElementById('edit_' + fieldName + '_' + id).style.display = 'none';
}

function toggleButtonDisplay(id, fromButton, toButton) {
    document.querySelector("button[onclick='" + fromButton + "Row(" + id + ")']").style.display = 'none';
    document.querySelector("button[onclick='" + toButton + "Row(" + id + ")']").style.display = 'inline-block';
}

</script>

</body>
</html>
