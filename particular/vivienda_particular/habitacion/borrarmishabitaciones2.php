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

// Obtener el ID del piso a eliminar
$id_habitacion = $_POST['id_habitacion'];

// Verificar que el piso pertenece al usuario actual
$verificar = "SELECT id_usuario FROM Habitaciones WHERE id_habitacion = $id_habitacion";
$resultado = mysqli_query($conexion, $verificar);
$fila = mysqli_fetch_array($resultado);

if ($fila['id_usuario'] == $_SESSION['id_usuario']) {
    // Eliminar el piso
    $instruccion = "DELETE FROM Habitaciones WHERE id_habitacion = $id_habitacion";
    
    if (mysqli_query($conexion, $instruccion)) {
        // Redirigir de vuelta a borrarmishabitaciones.php con mensaje de éxito
        header("Location: borrarmishabitaciones.php?mensaje=eliminado");
    } else {
        // Redirigir de vuelta a borrarmishabitaciones.php con mensaje de error
        header("Location: borrarmishabitaciones.php?mensaje=error");
    }
} else {
    // Redirigir de vuelta a borrarmishabitaciones.php con mensaje de error
    header("Location: borrarmishabitaciones.php?mensaje=no_permitido");
}

// Cerrar conexión
mysqli_close($conexion);
?> 