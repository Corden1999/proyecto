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

// Obtener el ID del piso del formulario
$id_piso = $_POST['id_piso'];

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar el estado del piso a disponible
    $sql_actualizar_piso = "UPDATE Pisos SET disponible = 'si' WHERE id_piso = $id_piso";
    mysqli_query($conexion, $sql_actualizar_piso);

    // Registrar la anulación del alquiler en la tabla de transacciones
    $sql_anulacion = "INSERT INTO Transaccion_piso_alquiler (id_usuario_casero, id_usuario_arrendatario, id_piso, monto, fecha_transaccion) 
                     SELECT id_usuario_casero, id_usuario_arrendatario, id_piso, monto, NOW() 
                     FROM Transaccion_piso_alquiler 
                     WHERE id_piso = $id_piso 
                     AND id_usuario_arrendatario = " . $_SESSION['id_usuario'] . "
                     ORDER BY fecha_transaccion DESC LIMIT 1";
    mysqli_query($conexion, $sql_anulacion);

    // Confirmar la transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: pisosalquilados.php?mensaje=exito");
    exit();

} catch (Exception $e) {
    // Si hay algún error, deshacer la transacción
    mysqli_rollback($conexion);
    
    // Redirigir con mensaje de error
    header("Location: pisosalquilados.php?mensaje=error");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 