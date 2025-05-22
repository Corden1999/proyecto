<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Verificar que se recibió el ID del local
if (!isset($_POST['id_piso'])) {
    header("Location: mispisos.php");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID del local a eliminar
$id_piso = mysqli_real_escape_string($conexion, $_POST['id_piso']);
$id_usuario = $_SESSION['id_usuario'];

// Eliminar transacciones de alquiler relacionadas con el piso
$consulta_alquiler = "DELETE FROM Transaccion_piso_alquiler WHERE id_piso = '$id_piso'";
mysqli_query($conexion, $consulta_alquiler);

// Eliminar transacciones de venta relacionadas con el piso
$consulta_venta = "DELETE FROM Transaccion_piso_venta WHERE id_piso = '$id_piso'";
mysqli_query($conexion, $consulta_venta);

// Eliminar el piso solo si pertenece al usuario
$consulta_borrar = "DELETE FROM Pisos WHERE id_piso = '$id_piso' AND id_usuario = '$id_usuario'";
mysqli_query($conexion, $consulta_borrar);

mysqli_close($conexion);

header("Location: mispisos.php");
exit();
?>  