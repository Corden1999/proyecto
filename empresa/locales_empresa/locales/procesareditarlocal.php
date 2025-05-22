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
$id_local = $_POST['id_local'];
$direccion = $_POST['direccion'];
$localidad = $_POST['localidad'];
$provincia = $_POST['provincia'];
$codigo_postal = $_POST['codigo_postal'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];
$disponible = $_POST['disponible'];
$foto_actual = $_POST['foto_actual'];

// Procesar la nueva foto si se ha subido una
$foto = $foto_actual; // Por defecto, mantener la foto actual
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $target_dir = "../../../img/locales/";
    $file_extension = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Verificar si es una imagen válida
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if ($check !== false) {
        // Intentar subir la imagen
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = "img/locales/" . $new_filename;
        }
    }
}

// Actualizar los datos en la base de datos
$sql = "UPDATE Locales SET 
        direccion = '$direccion',
        localidad = '$localidad',
        provincia = '$provincia',
        codigo_postal = '$codigo_postal',
        precio = '$precio',
        descripcion = '$descripcion',
        tipo = '$tipo',
        disponible = '$disponible',
        foto = '$foto'
        WHERE id_local = '$id_local' 
        AND id_usuario = " . $_SESSION['id_usuario'];

if (mysqli_query($conexion, $sql)) {
    // Redirigir de vuelta a mislocales.php con mensaje de éxito
    header("Location: mislocales.php?mensaje=Local actualizado correctamente");
} else {
    // Redirigir con mensaje de error
    header("Location: mislocales.php?error=Error al actualizar el local");
}

mysqli_close($conexion);
?> 