<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Verificar que se recibió el ID del local
if (!isset($_POST['id_local'])) {
    header("Location: borrarmislocales.php");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID del local a eliminar
$id_local = mysqli_real_escape_string($conexion, $_POST['id_local']);
$id_usuario = $_SESSION['id_usuario'];

// Verificar que el local existe y pertenece al usuario
$verificar = "SELECT id_local FROM Locales WHERE id_local = '$id_local' AND id_usuario = '$id_usuario'";
$resultado = mysqli_query($conexion, $verificar);

if (mysqli_num_rows($resultado) > 0) {
    // Primero eliminar las transacciones relacionadas
    $eliminar_transacciones = "DELETE FROM Transaccion_local_alquiler WHERE id_local = '$id_local'";
    mysqli_query($conexion, $eliminar_transacciones);
    
    $eliminar_transacciones_venta = "DELETE FROM Transaccion_local_venta WHERE id_local = '$id_local'";
    mysqli_query($conexion, $eliminar_transacciones_venta);
    
    // Luego eliminar el local
    $instruccion = "DELETE FROM Locales WHERE id_local = '$id_local' AND id_usuario = '$id_usuario'";
    
    if (mysqli_query($conexion, $instruccion)) {
        header("Location: borrarmislocales.php");
    } else {
        header("Location: borrarmislocales.php");
    }
} else {
    header("Location: borrarmislocales.php");
}

mysqli_close($conexion);
?>  