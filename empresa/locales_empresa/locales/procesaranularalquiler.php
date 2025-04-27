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

// Obtener el ID del local del formulario
$id_local = $_POST['id_local'];

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar el estado del local a disponible
    $sql_actualizar_local = "UPDATE Locales SET disponible = 'si' WHERE id_local = $id_local";
    mysqli_query($conexion, $sql_actualizar_local);

    // Registrar la anulación del alquiler en la tabla de transacciones
    $sql_anulacion = "INSERT INTO Transaccion_local_alquiler (id_usuario_casero, id_usuario_arrendatario, id_local, monto, fecha_transaccion) 
                     SELECT id_usuario_casero, id_usuario_arrendatario, id_local, monto, NOW() 
                     FROM Transaccion_local_alquiler 
                     WHERE id_local = $id_local 
                     AND id_usuario_arrendatario = " . $_SESSION['id_usuario'] . "
                     ORDER BY fecha_transaccion DESC LIMIT 1";
    mysqli_query($conexion, $sql_anulacion);

    // Confirmar la transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: localesalquilados.php?mensaje=exito");
    exit();

} catch (Exception $e) {
    // Si hay algún error, deshacer la transacción
    mysqli_rollback($conexion);
    
    // Redirigir con mensaje de error
    header("Location: localesalquilados.php?mensaje=error");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 