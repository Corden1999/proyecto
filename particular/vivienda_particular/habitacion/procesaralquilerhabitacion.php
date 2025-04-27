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

// Obtener datos del formulario
$id_habitacion = $_POST['id_habitacion'];
$precio = $_POST['precio'];
$id_usuario_habitacion_arrendatario = $_SESSION['id_usuario'];

// Obtener información de la habitación
$sql_habitacion = "SELECT id_usuario FROM Habitaciones WHERE id_habitacion = $id_habitacion";
$result_habitacion = mysqli_query($conexion, $sql_habitacion);
$habitacion = mysqli_fetch_assoc($result_habitacion);
$id_usuario_habitacion_casero = $habitacion['id_usuario'];

// Verificar fondos del arrendatario
$sql_cuenta_arrendatario = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_habitacion_arrendatario";
$result_cuenta_arrendatario = mysqli_query($conexion, $sql_cuenta_arrendatario);
$cuenta_arrendatario = mysqli_fetch_assoc($result_cuenta_arrendatario);

if ($cuenta_arrendatario['saldo'] < $precio) {
    header("Location: alquilarhabitacion.php?error=fondos_insuficientes");
    exit();
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar saldo del arrendatario
    $nuevo_saldo_arrendatario = $cuenta_arrendatario['saldo'] - $precio;
    $sql_update_arrendatario = "UPDATE Cuenta SET saldo = $nuevo_saldo_arrendatario WHERE id_usuario = $id_usuario_habitacion_arrendatario";
    mysqli_query($conexion, $sql_update_arrendatario);

    // Obtener y actualizar saldo del casero
    $sql_cuenta_casero = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_habitacion_casero";
    $result_cuenta_casero = mysqli_query($conexion, $sql_cuenta_casero);
    $cuenta_casero = mysqli_fetch_assoc($result_cuenta_casero);
    $nuevo_saldo_casero = $cuenta_casero['saldo'] + $precio;
    $sql_update_casero = "UPDATE Cuenta SET saldo = $nuevo_saldo_casero WHERE id_usuario = $id_usuario_habitacion_casero";
    mysqli_query($conexion, $sql_update_casero);

    // Registrar la transacción
    $sql_transaccion = "INSERT INTO Transaccion_habitacion_alquiler (id_usuario_habitacion_casero, id_usuario_habitacion_arrendatario, id_habitacion, monto) 
                        VALUES ($id_usuario_habitacion_casero, $id_usuario_habitacion_arrendatario, $id_habitacion, $precio)";
    mysqli_query($conexion, $sql_transaccion);

    // Marcar la habitación como no disponible
    $sql_update_habitacion = "UPDATE Habitaciones SET disponible = 'no' WHERE id_habitacion = $id_habitacion";
    mysqli_query($conexion, $sql_update_habitacion);

    // Registrar gasto para el arrendatario
    $sql_gasto = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                  VALUES ((SELECT id_cuenta FROM Cuenta WHERE id_usuario = $id_usuario_habitacion_arrendatario), 
                  'Alquiler de habitación', $precio)";
    mysqli_query($conexion, $sql_gasto);

    // Registrar ingreso para el casero
    $sql_ingreso = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                    VALUES ((SELECT id_cuenta FROM Cuenta WHERE id_usuario = $id_usuario_habitacion_casero), 
                    'Ingreso por alquiler de habitación', $precio)";
    mysqli_query($conexion, $sql_ingreso);

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: alquilarhabitacion.php?success=alquiler_exitoso");
    exit();

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    header("Location: alquilarhabitacion.php?error=transaccion_fallida");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?>
