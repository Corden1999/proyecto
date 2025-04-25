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
    // Verificar fondos del usuario comprador
    $sql_cuenta = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario FOR UPDATE";
    $result_cuenta = mysqli_query($conexion, $sql_cuenta);
    $cuenta = mysqli_fetch_assoc($result_cuenta);

    if (!$cuenta || $cuenta['saldo'] < $precio) {
        throw new Exception("No tiene fondos suficientes");
    }

    // Obtener el ID del vendedor
    $sql_vendedor = "SELECT id_usuario FROM Locales WHERE id_local = $id_local";
    $result_vendedor = mysqli_query($conexion, $sql_vendedor);
    $vendedor = mysqli_fetch_assoc($result_vendedor);
    $id_vendedor = $vendedor['id_usuario'];

    // Actualizar saldo del comprador (restar)
    $nuevo_saldo_comprador = $cuenta['saldo'] - $precio;
    $sql_actualizar_saldo_comprador = "UPDATE Cuenta SET saldo = $nuevo_saldo_comprador WHERE id_usuario = $id_usuario";
    mysqli_query($conexion, $sql_actualizar_saldo_comprador);

    // Actualizar saldo del vendedor (sumar)
    $sql_saldo_vendedor = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_vendedor FOR UPDATE";
    $result_saldo_vendedor = mysqli_query($conexion, $sql_saldo_vendedor);
    $saldo_vendedor = mysqli_fetch_assoc($result_saldo_vendedor);
    $nuevo_saldo_vendedor = $saldo_vendedor['saldo'] + $precio;
    $sql_actualizar_saldo_vendedor = "UPDATE Cuenta SET saldo = $nuevo_saldo_vendedor WHERE id_usuario = $id_vendedor";
    mysqli_query($conexion, $sql_actualizar_saldo_vendedor);

    // Actualizar estado del local
    $sql_actualizar_local = "UPDATE Locales SET disponible = 'no', id_usuario = $id_usuario WHERE id_local = $id_local";
    mysqli_query($conexion, $sql_actualizar_local);

    // Registrar la transacción
    $sql_transaccion = "INSERT INTO Transaccion_local_venta (id_usuario_vendedor, id_usuario_comprador, id_local, monto) 
                       VALUES ($id_vendedor, $id_usuario, $id_local, $precio)";
    mysqli_query($conexion, $sql_transaccion);

    // Registrar el gasto del comprador
    $sql_gasto = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                 SELECT id_cuenta, 'Compra de local', $precio FROM Cuenta WHERE id_usuario = $id_usuario";
    mysqli_query($conexion, $sql_gasto);

    // Registrar el ingreso del vendedor
    $sql_ingreso = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                   SELECT id_cuenta, 'Venta de local', $precio FROM Cuenta WHERE id_usuario = $id_vendedor";
    mysqli_query($conexion, $sql_ingreso);

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: comprarlocal.php?mensaje=exito");
    exit();

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    
    // Redirigir con mensaje de error
    header("Location: comprarlocal.php?mensaje=error&detalle=" . urlencode($e->getMessage()));
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 