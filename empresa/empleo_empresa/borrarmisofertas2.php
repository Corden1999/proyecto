<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Verificar que se recibió el ID de la oferta
if (!isset($_POST['id_empleo'])) {
    header("Location: borrarmisofertas.php");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID de la oferta a eliminar
$id_empleo = mysqli_real_escape_string($conexion, $_POST['id_empleo']);
$id_usuario = $_SESSION['id_usuario'];

// Verificar que la oferta existe y pertenece al usuario
$verificar = "SELECT id_empleo FROM Empleos WHERE id_empleo = '$id_empleo' AND id_usuario = '$id_usuario'";
$resultado = mysqli_query($conexion, $verificar);

if (mysqli_num_rows($resultado) > 0) {
    // Eliminar la oferta
    $instruccion = "DELETE FROM Empleos WHERE id_empleo = '$id_empleo' AND id_usuario = '$id_usuario'";
    
    if (mysqli_query($conexion, $instruccion)) {
        header("Location: borrarmisofertas.php");
    } else {
        header("Location: borrarmisofertas.php");
    }
} else {
    header("Location: borrarmisofertas.php");
}

mysqli_close($conexion);
?>  