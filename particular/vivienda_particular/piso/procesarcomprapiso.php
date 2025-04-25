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
$id_usuario_comprador = $_SESSION['id_usuario'];

// Obtener información del piso
$sql_piso = "SELECT id_usuario FROM Pisos WHERE id_piso = $id_piso";
$result_piso = mysqli_query($conexion, $sql_piso);
$piso = mysqli_fetch_assoc($result_piso);
$id_usuario_vendedor = $piso['id_usuario'];

// Verificar fondos del comprador
$sql_cuenta_comprador = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_comprador";
$result_cuenta_comprador = mysqli_query($conexion, $sql_cuenta_comprador);
$cuenta_comprador = mysqli_fetch_assoc($result_cuenta_comprador);

if ($cuenta_comprador['saldo'] < $precio) {
    header("Location: comprarpiso.php?error=fondos_insuficientes");
    exit();
}

// Iniciar transacción
mysqli_begin_transaction($conexion);

try {
    // Actualizar saldo del comprador
    $nuevo_saldo_comprador = $cuenta_comprador['saldo'] - $precio;
    $sql_update_comprador = "UPDATE Cuenta SET saldo = $nuevo_saldo_comprador WHERE id_usuario = $id_usuario_comprador";
    mysqli_query($conexion, $sql_update_comprador);

    // Obtener y actualizar saldo del vendedor
    $sql_cuenta_vendedor = "SELECT saldo FROM Cuenta WHERE id_usuario = $id_usuario_vendedor";
    $result_cuenta_vendedor = mysqli_query($conexion, $sql_cuenta_vendedor);
    $cuenta_vendedor = mysqli_fetch_assoc($result_cuenta_vendedor);
    $nuevo_saldo_vendedor = $cuenta_vendedor['saldo'] + $precio;
    $sql_update_vendedor = "UPDATE Cuenta SET saldo = $nuevo_saldo_vendedor WHERE id_usuario = $id_usuario_vendedor";
    mysqli_query($conexion, $sql_update_vendedor);

    // Registrar la transacción
    $sql_transaccion = "INSERT INTO Transaccion_piso_venta (id_usuario_vendedor, id_usuario_comprador, id_piso, monto) 
                        VALUES ($id_usuario_vendedor, $id_usuario_comprador, $id_piso, $precio)";
    mysqli_query($conexion, $sql_transaccion);

    // Actualizar el propietario del piso
    $sql_update_piso = "UPDATE Pisos SET id_usuario = $id_usuario_comprador, disponible = 'no' WHERE id_piso = $id_piso";
    mysqli_query($conexion, $sql_update_piso);

    // Registrar gasto para el comprador
    $sql_gasto = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                  VALUES ((SELECT id_cuenta FROM Cuenta WHERE id_usuario = $id_usuario_comprador), 
                  'Compra de piso', $precio)";
    mysqli_query($conexion, $sql_gasto);

    // Registrar ingreso para el vendedor
    $sql_ingreso = "INSERT INTO Gastos (id_cuenta, descripcion, monto) 
                    VALUES ((SELECT id_cuenta FROM Cuenta WHERE id_usuario = $id_usuario_vendedor), 
                    'Venta de piso', $precio)";
    mysqli_query($conexion, $sql_ingreso);

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redirigir con mensaje de éxito
    header("Location: comprarpiso.php?success=compra_exitosa");
    exit();

} catch (Exception $e) {
    // Revertir transacción en caso de error
    mysqli_rollback($conexion);
    header("Location: comprarpiso.php?error=transaccion_fallida");
    exit();
}

// Cerrar conexión
mysqli_close($conexion);
?> 