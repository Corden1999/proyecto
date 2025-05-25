<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];
$resultado = null;
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $precio_inmueble = floatval($_POST['precio_inmueble']);
    $ahorro = floatval($_POST['ahorro']);
    $plazo_anos = intval($_POST['plazo_anos']);
    $tipo_interes = floatval($_POST['tipo_interes']);

    // Validaciones básicas
    if ($precio_inmueble <= 0 || $ahorro < 0 || $plazo_anos <= 0 || $tipo_interes < 0) {
        $mensaje = "Por favor, introduce valores válidos.";
    } else {
        // Cálculo del préstamo
        $prestamo = $precio_inmueble - $ahorro;
        
        // Convertir interés anual a mensual
        $interes_mensual = ($tipo_interes / 100) / 12;
        
        // Número de pagos mensuales
        $num_pagos = $plazo_anos * 12;
        
        // Cálculo de la cuota mensual usando la fórmula de amortización
        if ($interes_mensual > 0) {
            $cuota_mensual = $prestamo * ($interes_mensual * pow(1 + $interes_mensual, $num_pagos)) / (pow(1 + $interes_mensual, $num_pagos) - 1);
        } else {
            $cuota_mensual = $prestamo / $num_pagos;
        }

        $resultado = array(
            'prestamo' => $prestamo,
            'cuota_mensual' => $cuota_mensual,
            'total_pagado' => $cuota_mensual * $num_pagos,
            'interes_total' => ($cuota_mensual * $num_pagos) - $prestamo
        );
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Calculadora de Hipoteca</title>
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

        .calculadora-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            color: #ffffff;
        }

        .calculadora-titulo {
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

        .resultado-container {
            margin-top: 30px;
            padding: 20px;
            background-color: rgba(174, 139, 79, 0.1);
            border-radius: 10px;
        }

        .resultado-item {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ae8b4f;
        }

        .resultado-item:last-child {
            border-bottom: none;
        }

        .resultado-label {
            color: #ae8b4f;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .resultado-valor {
            color: #ffffff;
            font-size: 18px;
        }

        .mensaje {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
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

    <div class="calculadora-container">
        <div class="calculadora-titulo">Calculadora de Hipoteca</div>
        
        <?php if ($mensaje): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="precio_inmueble">Precio del Inmueble (€):</label>
                <input type="number" id="precio_inmueble" name="precio_inmueble" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="ahorro">Ahorro Aportado (€):</label>
                <input type="number" id="ahorro" name="ahorro" step="0.01" min="0" required>
            </div>

            <div class="form-group">
                <label for="plazo_anos">Plazo en Años:</label>
                <input type="number" id="plazo_anos" name="plazo_anos" min="1" max="40" required>
            </div>

            <div class="form-group">
                <label for="tipo_interes">Tipo de Interés Anual (%):</label>
                <input type="number" id="tipo_interes" name="tipo_interes" step="0.01" min="0" required>
            </div>

            <button type="submit" class="submit-button">Calcular Hipoteca</button>
        </form>

        <?php if ($resultado): ?>
            <div class="resultado-container">
                <div class="resultado-item">
                    <div class="resultado-label">Préstamo Necesario:</div>
                    <div class="resultado-valor"><?php echo number_format($resultado['prestamo'], 2, ',', '.'); ?> €</div>
                </div>
                <div class="resultado-item">
                    <div class="resultado-label">Cuota Mensual:</div>
                    <div class="resultado-valor"><?php echo number_format($resultado['cuota_mensual'], 2, ',', '.'); ?> €</div>
                </div>
                <div class="resultado-item">
                    <div class="resultado-label">Total a Pagar:</div>
                    <div class="resultado-valor"><?php echo number_format($resultado['total_pagado'], 2, ',', '.'); ?> €</div>
                </div>
                <div class="resultado-item">
                    <div class="resultado-label">Interés Total:</div>
                    <div class="resultado-valor"><?php echo number_format($resultado['interes_total'], 2, ',', '.'); ?> €</div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 