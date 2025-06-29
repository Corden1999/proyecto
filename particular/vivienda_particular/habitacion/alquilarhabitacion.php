<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Enviar consulta
$instruccion = "SELECT * FROM Habitaciones WHERE disponible = 'si'";
$consulta = mysqli_query($conexion, $instruccion)
    or die("Fallo en la consulta");

// Mostrar resultados de la consulta
$nfilas = mysqli_num_rows($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Habitaciones en alquiler</title>
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

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #ae8b4f;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 10px;
            padding: 10px 0;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content button {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            border-radius: 0;
            margin: 0;
            min-width: auto;
        }

        .dropdown-content button:hover {
            background-color: #000000;
            color: white;
            transform: none;
            box-shadow: none;
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

        .habitaciones-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            padding: 40px 20px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .habitacion-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            color: #ffffff;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .habitacion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .habitacion-imagen {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .habitacion-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .habitacion-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .habitacion-propietario {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .habitacion-precio {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .alquilar-button {
            background-color: #4CAF50;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            margin-top: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            margin-right: 10px;
        }

        .alquilar-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .contactar-button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            margin-top: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }

        .contactar-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .botones-container {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .no-fondos {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            font-weight: bold;
        }

        .no-habitaciones {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .en-propiedad {
            background-color: #0066cc;
            color: white;
            padding: 8px 15px;
            border-radius: 15px;
            font-size: 14px;
            margin: 15px 0;
            font-weight: bold;
            text-align: center;
            width: fit-content;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../../indexparticular.php"><img src="../../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='alquilarhabitacion.php'">Alquilar habitación</button>
            <div class="dropdown-content">
                <button onclick="location.href='buscaralquilarhabitacion.php'">buscar habitaciones en alquiler</button>
                <button onclick="location.href='habitacionesalquiladas.php'">habitaciones alquiladas</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='arrendarhabitacion.php'">Arrendar habitación</button>
            <div class="dropdown-content">
                <button onclick="location.href='mishabitaciones.php'">mis habitaciones</button>
                <button onclick="location.href='buscarmishabitaciones.php'">buscar mis habitaciones</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../../sesiones/mensajeparticular.php'>Mensajes</a>
        <a href='../../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
        <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="habitaciones-container">
        <?php
        if ($nfilas > 0) {
            for ($i=0; $i<$nfilas; $i++) {
                $resultado = mysqli_fetch_array($consulta);
                echo "<div class='habitacion-card'>";
                echo "<img src='../../../" . str_replace('../../', '', $resultado['foto']) . "' alt='Foto de la habitación' class='habitacion-imagen'>";
                echo "<div class='habitacion-titulo'>" . $resultado['direccion'] . "</div>";
                echo "<div class='habitacion-info'>" . $resultado['localidad'] . ", " . $resultado['provincia'] . "</div>";
                echo "<div class='habitacion-info'>Código Postal: " . $resultado['codigo_postal'] . "</div>";
                echo "<div class='habitacion-info'>" . $resultado['descripcion'] . "</div>";
                
                // Obtener información del propietario
                $sql_propietario = "SELECT nombre FROM Usuarios WHERE id_usuario = " . $resultado['id_usuario'];
                $result_propietario = mysqli_query($conexion, $sql_propietario);
                $propietario = mysqli_fetch_assoc($result_propietario);
                
                echo "<div class='habitacion-propietario'>Propietario: " . $propietario['nombre'] . "</div>";
                echo "<div class='habitacion-precio'>" . $resultado['precio'] . "€/mes</div>";
                
                // Verificar si la habitación es del usuario actual
                if ($resultado['id_usuario'] == $_SESSION['id_usuario']) {
                    echo "<div class='en-propiedad'>En Propiedad</div>";
                } else {
                    // Verificar fondos del usuario
                    $sql_cuenta = "SELECT saldo FROM Cuenta WHERE id_usuario = " . $_SESSION['id_usuario'];
                    $result_cuenta = mysqli_query($conexion, $sql_cuenta);
                    $cuenta = mysqli_fetch_assoc($result_cuenta);
                    
                    echo "<div class='botones-container'>";
                    if ($cuenta && $cuenta['saldo'] >= $resultado['precio']) {
                        echo "<form action='alquilarhabitacion2.php' method='POST'>";
                        echo "<input type='hidden' name='id_habitacion' value='" . $resultado['id_habitacion'] . "'>";
                        echo "<input type='hidden' name='precio' value='" . $resultado['precio'] . "'>";
                        echo "<button type='submit' class='alquilar-button'>Alquilar Habitación</button>";
                        echo "</form>";
                    } else {
                        echo "<div class='no-fondos'>No tiene fondos suficientes</div>";
                    }
                    
                    echo "<a href='../../../sesiones/chat.php?id_usuario=" . $resultado['id_usuario'] . "' class='contactar-button'>Contactar</a>";
                    echo "</div>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-habitaciones'>No hay habitaciones disponibles para alquilar</div>";
        }

        // Cerrar conexión
        mysqli_close($conexion);
        ?>
    </div>
</body>
</html>