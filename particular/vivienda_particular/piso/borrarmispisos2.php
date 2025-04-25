<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Verificar que se recibió el ID del local
if (!isset($_POST['id_piso'])) {
    header("Location: borrarmispisos.php");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID del local a eliminar
$id_piso = mysqli_real_escape_string($conexion, $_POST['id_piso']);
$id_usuario = $_SESSION['id_usuario'];

// Verificar que el local existe y pertenece al usuario
$verificar = "SELECT id_piso FROM Pisos WHERE id_piso = '$id_piso' AND id_usuario = '$id_usuario'";
$resultado = mysqli_query($conexion, $verificar);

if (mysqli_num_rows($resultado) > 0) {
    // Primero eliminar las transacciones relacionadas
    $eliminar_transacciones = "DELETE FROM Transaccion_piso_alquiler WHERE id_piso = '$id_piso'";
    mysqli_query($conexion, $eliminar_transacciones);
    
    $eliminar_transacciones_venta = "DELETE FROM Transaccion_piso_venta WHERE id_piso = '$id_piso'";
    mysqli_query($conexion, $eliminar_transacciones_venta);
    
    // Luego eliminar el local
    $instruccion = "DELETE FROM Pisos WHERE id_piso = '$id_piso' AND id_usuario = '$id_usuario'";
    
    if (mysqli_query($conexion, $instruccion)) {
        header("Location: borrarmispisos.php");
    } else {
        header("Location: borrarmispisos.php");
    }
} else {
    header("Location: borrarmispisos.php");
}

mysqli_close($conexion);
?>  