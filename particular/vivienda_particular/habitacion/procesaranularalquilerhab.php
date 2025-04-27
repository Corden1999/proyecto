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

// Obtener el ID de la habitación del formulario
$id_habitacion = $_POST['id_habitacion'];

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar el estado de la habitación a disponible
    $sql_actualizar_habitacion = "UPDATE Habitaciones SET disponible = 'si' WHERE id_habitacion = $id_habitacion";
    mysqli_query($conexion, $sql_actualizar_habitacion);

    // Registrar la anulación del alquiler en la tabla de transacciones
    $sql_anulacion = "INSERT INTO Transaccion_habitacion_alquiler (id_usuario_habitacion_casero, id_usuario_habitacion_arrendatario, id_habitacion, monto, fecha_transaccion) 
                     SELECT id_usuario_habitacion_casero, id_usuario_habitacion_arrendatario, id_habitacion, monto, NOW() 
                     FROM Transaccion_habitacion_alquiler 
                     WHERE id_habitacion = $id_habitacion 
                     AND id_usuario_habitacion_arrendatario = " . $_SESSION['id_usuario'] . "
                     ORDER BY fecha_transaccion DESC LIMIT 1";
    mysqli_query($conexion, $sql_anulacion);

    // Confirmar la transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: habitacionesalquiladas.php?mensaje=exito");
    exit();

} catch (Exception $e) {
    // Si hay algún error, deshacer la transacción
    mysqli_rollback($conexion);
    
    // Redirigir con mensaje de error
    header("Location: habitacionesalquiladas.php?mensaje=error");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 