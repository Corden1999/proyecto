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

// Verificar si se recibió el ID de la habitación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_habitacion'])) {
    $id_habitacion = $_POST['id_habitacion'];
    
    // Primero eliminar las transacciones relacionadas
    $consulta_transacciones = "DELETE FROM Transaccion_habitacion_alquiler 
                             WHERE id_habitacion = '$id_habitacion'";
    
    if (mysqli_query($conexion, $consulta_transacciones)) {
        // Ahora eliminar la habitación
        $consulta = "DELETE FROM Habitaciones 
                    WHERE id_habitacion = '$id_habitacion' 
                    AND id_usuario = " . $_SESSION['id_usuario'];

        if (mysqli_query($conexion, $consulta)) {
            // Redirigir a la página de mis habitaciones
            header("Location: mishabitaciones.php");
            exit();
        } else {
            echo "Error al eliminar la habitación: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al eliminar las transacciones relacionadas: " . mysqli_error($conexion);
    }
} else {
    // Si no se recibió el ID, redirigir a la página de mis habitaciones
    header("Location: mishabitaciones.php");
    exit();
}

mysqli_close($conexion);
?> 