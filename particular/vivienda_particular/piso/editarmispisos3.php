<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener los datos del formulario
$id_piso = $_POST['id_piso'];
$direccion = $_POST['direccion'];
$localidad = $_POST['localidad'];
$provincia = $_POST['provincia'];
$codigo_postal = $_POST['codigo_postal'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];
$disponible = $_POST['disponible'];

// Procesar la foto si se ha subido una nueva
$foto = '';
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $foto_temp = $_FILES['foto']['tmp_name'];
    $foto_nombre = $_FILES['foto']['name'];
    $foto_destino = "../../../img/pisos/" . $foto_nombre;
    
    if (move_uploaded_file($foto_temp, $foto_destino)) {
        $foto = "img/pisos/" . $foto_nombre;
    }
}

// Construir la consulta SQL
$sql = "UPDATE Pisos SET 
        direccion = '$direccion',
        localidad = '$localidad',
        provincia = '$provincia',
        codigo_postal = '$codigo_postal',
        precio = '$precio',
        descripcion = '$descripcion',
        tipo = '$tipo',
        disponible = '$disponible'";

// Añadir la foto a la consulta solo si se ha subido una nueva
if ($foto != '') {
    $sql .= ", foto = '$foto'";
}

$sql .= " WHERE id_piso = '$id_piso' AND id_usuario = " . $_SESSION['id_usuario'];

// Ejecutar la consulta
if (mysqli_query($conexion, $sql)) {
    // Redirigir a mispisos.php después de actualizar
    header("Location: mispisos.php");
    exit();
} else {
    echo "Error al actualizar el piso: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>