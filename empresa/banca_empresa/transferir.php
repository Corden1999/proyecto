<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];
$mensaje = '';

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_destinatario = mysqli_real_escape_string($conexion, $_POST['email']);
    $concepto = mysqli_real_escape_string($conexion, $_POST['concepto']);
    $cantidad = floatval($_POST['cantidad']);
    $id_usuario_origen = $_SESSION['id_usuario'];

    // Verificar que el usuario tiene suficiente saldo
    $consulta_saldo = "SELECT saldo FROM Cuenta WHERE id_usuario = '$id_usuario_origen'";
    $resultado_saldo = mysqli_query($conexion, $consulta_saldo);
    $saldo = mysqli_fetch_assoc($resultado_saldo);

    if ($saldo['saldo'] >= $cantidad) {
        // Buscar el ID del usuario destinatario
        $consulta_destinatario = "SELECT id_usuario FROM Usuarios WHERE email = '$email_destinatario'";
        $resultado_destinatario = mysqli_query($conexion, $consulta_destinatario);

        if (mysqli_num_rows($resultado_destinatario) > 0) {
            $destinatario = mysqli_fetch_assoc($resultado_destinatario);
            $id_usuario_destino = $destinatario['id_usuario'];

            // Iniciar transacción
            mysqli_begin_transaction($conexion);

            try {
                // Actualizar saldo del usuario origen
                $nuevo_saldo_origen = $saldo['saldo'] - $cantidad;
                mysqli_query($conexion, "UPDATE Cuenta SET saldo = $nuevo_saldo_origen WHERE id_usuario = '$id_usuario_origen'");

                // Actualizar saldo del usuario destino
                mysqli_query($conexion, "UPDATE Cuenta SET saldo = saldo + $cantidad WHERE id_usuario = '$id_usuario_destino'");

                // Registrar movimiento de gasto para el origen
                mysqli_query($conexion, "INSERT INTO Movimientos (id_usuario, tipo, cantidad, concepto) 
                                       VALUES ('$id_usuario_origen', 'gasto', $cantidad, 'Transferencia a $email_destinatario: $concepto')");

                // Registrar movimiento de ingreso para el destino
                mysqli_query($conexion, "INSERT INTO Movimientos (id_usuario, tipo, cantidad, concepto) 
                                       VALUES ('$id_usuario_destino', 'ingreso', $cantidad, 'Transferencia recibida: $concepto')");

                mysqli_commit($conexion);
                $mensaje = "Transferencia realizada con éxito.";
            } catch (Exception $e) {
                mysqli_rollback($conexion);
                $mensaje = "Error al realizar la transferencia. Por favor, inténtelo de nuevo.";
            }
        } else {
            $mensaje = "El correo electrónico del destinatario no existe.";
        }
    } else {
        $mensaje = "No tiene suficiente saldo para realizar la transferencia.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Transferir</title>
    <style>
        body {
            background-color: #000000;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', Arial, sans-serif;
        }
        
        .titulo {
            margin: 0;
            display: inline-block;
            vertical-align: middle;
        }
        
        .titulo img {
            height: 50px;
            width: auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 20px;
            margin-bottom: 40px;
        }
        
        .menu {
            background-color: #ae8b4f;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            padding: 15px 50px;
            margin-top: 80px;
        }
        
        .menu button {
            background-color: #000000;
            border: 2px solid #000000;
            color: white;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            min-width: 150px;
        }
        
        .menu button:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .welcome-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            margin: 20px;
            color: #ffffff;
            position: absolute;
            top: 20px;
            right: 10px;
            text-align: right;
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .welcome-container strong {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        .welcome-container a {
            color: #ae8b4f;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            font-weight: bold;
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .welcome-container a:hover {
            color: #ffffff;
        }

        .transferir-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            color: #ffffff;
        }

        .transferir-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ae8b4f;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ae8b4f;
            border-radius: 8px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #ffffff;
        }

        .submit-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .mensaje {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .mensaje.exito {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }

        .mensaje.error {
            background-color: rgba(255, 68, 68, 0.2);
            color: #ff4444;
            border: 1px solid #ff4444;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexempresa.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <button onclick="location.href='bancaindex.php'">Banca</button>
        <button onclick="location.href='calculadorahipoteca.php'">Calculadora de Hipoteca</button>
        <button onclick="location.href='transferir.php'">Transferir</button>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="transferir-container">
        <div class="transferir-titulo">Realizar Transferencia</div>
        
        <?php if ($mensaje): ?>
            <div class="mensaje <?php echo strpos($mensaje, 'éxito') !== false ? 'exito' : 'error'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Correo del Destinatario:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="concepto">Concepto:</label>
                <input type="text" id="concepto" name="concepto" required>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad (€):</label>
                <input type="number" id="cantidad" name="cantidad" step="0.01" min="0.01" required>
            </div>

            <button type="submit" class="submit-button">Realizar Transferencia</button>
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?> 