<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Conectar con la base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener datos del formulario
$id_local = $_POST['id_local'];
$precio = $_POST['precio'];
$id_usuario = $_SESSION['id_usuario'];

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Verificar fondos del usuario arrendatario
    $sql_cuenta = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario FOR UPDATE";
    $result_cuenta = mysqli_query($conexion, $sql_cuenta);
    $cuenta = mysqli_fetch_assoc($result_cuenta);

    if (!$cuenta || $cuenta['saldo'] < $precio) {
        throw new Exception("No tiene fondos suficientes");
    }

    // Obtener el ID del arrendador
    $sql_arrendador = "SELECT id_usuario FROM Locales WHERE id_local = $id_local";
    $result_arrendador = mysqli_query($conexion, $sql_arrendador);
    $arrendador = mysqli_fetch_assoc($result_arrendador);
    $id_arrendador = $arrendador['id_usuario'];

    // Actualizar saldo del arrendatario (restar)
    $nuevo_saldo_arrendatario = $cuenta['saldo'] - $precio;
    $sql_actualizar_saldo_arrendatario = "UPDATE Cuenta SET saldo = $nuevo_saldo_arrendatario WHERE id_usuario = $id_usuario";
    mysqli_query($conexion, $sql_actualizar_saldo_arrendatario);

    // Actualizar saldo del arrendador (sumar)
    $sql_saldo_arrendador = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_arrendador FOR UPDATE";
    $result_saldo_arrendador = mysqli_query($conexion, $sql_saldo_arrendador);
    $saldo_arrendador = mysqli_fetch_assoc($result_saldo_arrendador);
    $nuevo_saldo_arrendador = $saldo_arrendador['saldo'] + $precio;
    $sql_actualizar_saldo_arrendador = "UPDATE Cuenta SET saldo = $nuevo_saldo_arrendador WHERE id_usuario = $id_arrendador";
    mysqli_query($conexion, $sql_actualizar_saldo_arrendador);

    // Actualizar estado del local
    $sql_actualizar_local = "UPDATE Locales SET disponible = 'no' WHERE id_local = $id_local";
    mysqli_query($conexion, $sql_actualizar_local);

    // Registrar la transacción
    $sql_transaccion = "INSERT INTO Transaccion_local_alquiler (id_usuario_casero, id_usuario_arrendatario, id_local, monto) 
                       VALUES ($id_arrendador, $id_usuario, $id_local, $precio)";
    mysqli_query($conexion, $sql_transaccion);

    // Registrar el gasto del arrendatario
    $sql_gasto = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                 SELECT id_cuenta, 'Alquiler de local', $precio FROM Cuenta WHERE id_usuario = $id_usuario";
    mysqli_query($conexion, $sql_gasto);

    // Registrar el ingreso del arrendador
    $sql_ingreso = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                   SELECT id_cuenta, 'Alquiler de local', $precio FROM Cuenta WHERE id_usuario = $id_arrendador";
    mysqli_query($conexion, $sql_ingreso);

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: alquilarlocal.php?mensaje=exito");
    exit();

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    
    // Redirigir con mensaje de error
    header("Location: alquilarlocal.php?mensaje=error&detalle=" . urlencode($e->getMessage()));
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 