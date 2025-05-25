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
$id_piso = $_POST['id_piso'];
$precio = $_POST['precio'];
$id_usuario_arrendatario = $_SESSION['id_usuario'];

// Obtener información del piso
$sql_piso = "SELECT id_usuario FROM Pisos WHERE id_piso = $id_piso";
$result_piso = mysqli_query($conexion, $sql_piso);
$piso = mysqli_fetch_assoc($result_piso);
$id_usuario_casero = $piso['id_usuario'];

// Verificar fondos del arrendatario
$sql_cuenta_arrendatario = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_arrendatario";
$result_cuenta_arrendatario = mysqli_query($conexion, $sql_cuenta_arrendatario);
$cuenta_arrendatario = mysqli_fetch_assoc($result_cuenta_arrendatario);

if ($cuenta_arrendatario['saldo'] < $precio) {
    header("Location: alquilarpiso.php?error=fondos_insuficientes");
    exit();
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar saldo del arrendatario
    $nuevo_saldo_arrendatario = $cuenta_arrendatario['saldo'] - $precio;
    $sql_update_arrendatario = "UPDATE Cuenta SET saldo = $nuevo_saldo_arrendatario WHERE id_usuario = $id_usuario_arrendatario";
    mysqli_query($conexion, $sql_update_arrendatario);

    // Obtener y actualizar saldo del casero
    $sql_cuenta_casero = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_casero";
    $result_cuenta_casero = mysqli_query($conexion, $sql_cuenta_casero);
    $cuenta_casero = mysqli_fetch_assoc($result_cuenta_casero);
    $nuevo_saldo_casero = $cuenta_casero['saldo'] + $precio;
    $sql_update_casero = "UPDATE Cuenta SET saldo = $nuevo_saldo_casero WHERE id_usuario = $id_usuario_casero";
    mysqli_query($conexion, $sql_update_casero);

    // Registrar la transacción
    $sql_transaccion = "INSERT INTO Transaccion_piso_alquiler (id_usuario_casero, id_usuario_arrendatario, id_piso, monto) 
                        VALUES ($id_usuario_casero, $id_usuario_arrendatario, $id_piso, $precio)";
    mysqli_query($conexion, $sql_transaccion);

    // Marcar el piso como no disponible
    $sql_update_piso = "UPDATE Pisos SET disponible = 'no' WHERE id_piso = $id_piso";
    mysqli_query($conexion, $sql_update_piso);

    // Registrar gasto para el arrendatario
    $sql_gasto = "INSERT INTO Movimientos (id_usuario, tipo, cantidad, concepto) 
                  VALUES ($id_usuario_arrendatario, 'gasto', $precio, 'Alquiler de piso')";
    mysqli_query($conexion, $sql_gasto);

    // Registrar ingreso para el casero
    $sql_ingreso = "INSERT INTO Movimientos (id_usuario, tipo, cantidad, concepto) 
                    VALUES ($id_usuario_casero, 'ingreso', $precio, 'Ingreso por alquiler de piso')";
    mysqli_query($conexion, $sql_ingreso);

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: alquilarpiso.php?success=alquiler_exitoso");
    exit();

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    header("Location: alquilarpiso.php?error=transaccion_fallida");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 