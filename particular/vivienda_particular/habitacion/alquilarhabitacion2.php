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

// Obtener el ID de la habitación
$id_habitacion = isset($_POST['id_habitacion']) ? $_POST['id_habitacion'] : null;
$precio = isset($_POST['precio']) ? $_POST['precio'] : null;

if (!$id_habitacion || !$precio) {
    header("Location: alquilarhabitacion.php");
    exit();
}

// Obtener información detallada de la habitación
$consulta = "SELECT h.*, u.nombre as nombre_propietario
             FROM Habitaciones h 
             JOIN Usuarios u ON h.id_usuario = u.id_usuario 
             WHERE h.id_habitacion = '$id_habitacion'";
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");

$habitacion = mysqli_fetch_array($resultado);

// Obtener estadísticas del código postal
$codigo_postal = $habitacion['codigo_postal'];
$consulta_estadisticas = "SELECT 
    COUNT(*) as total_habitaciones,
    AVG(precio) as precio_medio,
    COUNT(CASE WHEN disponible = 'si' THEN 1 END) as habitaciones_disponibles,
    COUNT(CASE WHEN disponible = 'no' THEN 1 END) as habitaciones_ocupadas
    FROM Habitaciones 
    WHERE codigo_postal = '$codigo_postal'";
$resultado_estadisticas = mysqli_query($conexion, $consulta_estadisticas)
    or die("Fallo en la consulta de estadísticas");
$estadisticas = mysqli_fetch_assoc($resultado_estadisticas);

// Obtener ofertas de trabajo en la zona
$consulta_empleos = "SELECT e.*, u.nombre as nombre_empresa
                    FROM Empleos e 
                    JOIN Usuarios u ON e.id_usuario = u.id_usuario 
                    WHERE e.codigo_postal = '$codigo_postal'";
$resultado_empleos = mysqli_query($conexion, $consulta_empleos)
    or die("Fallo en la consulta de empleos");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Detalles de Alquiler de Habitación</title>
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

        .habitacion-container {
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

        .botones-container {
            display: flex;
            gap: 5px;
            margin-top: 20px;
        }

        .alquilar-button {
            background-color: #4CAF50;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
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

        .volver-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .volver-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .trabajadores-container {
            margin-top: 30px;
            width: 100%;
        }

        .trabajadores-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .trabajador-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .trabajador-nombre {
            color: #ae8b4f;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .trabajador-info {
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.5;
        }

        .trabajador-experiencia {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ae8b4f;
        }

        .trabajador-experiencia-titulo {
            color: #ae8b4f;
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .no-trabajadores {
            color: #ae8b4f;
            font-size: 18px;
            text-align: center;
            padding: 20px;
        }

        .contactar-trabajador-button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            margin-top: 15px;
            width: fit-content;
            text-align: left;
        }

        .contactar-trabajador-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }

        .estadisticas-container {
            margin-top: 30px;
            width: 100%;
        }

        .estadisticas-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .estadisticas-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .estadistica-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
        }

        .estadistica-valor {
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .estadistica-label {
            color: #ae8b4f;
            font-size: 16px;
            font-weight: bold;
        }

        .habitaciones-ocupadas-container {
            margin-top: 30px;
            width: 100%;
        }

        .habitaciones-ocupadas-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .habitacion-ocupada-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .habitacion-ocupada-titulo {
            color: #ae8b4f;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .habitacion-ocupada-info {
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.5;
        }

        .habitacion-ocupada-fecha {
            color: #ae8b4f;
            font-size: 14px;
            margin-top: 10px;
            font-style: italic;
        }

        .empleos-container {
            margin-top: 30px;
            width: 100%;
        }

        .empleos-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .empleo-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .empleo-titulo {
            color: #ae8b4f;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .empleo-info {
            margin-bottom: 8px;
            font-size: 16px;
            line-height: 1.5;
        }

        .empleo-empresa {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .empleo-salario {
            color: #ae8b4f;
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }

        .contactar-empleo-button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            margin-top: 15px;
            width: fit-content;
            text-align: left;
        }

        .contactar-empleo-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .no-empleos {
            color: #ae8b4f;
            font-size: 18px;
            text-align: center;
            padding: 20px;
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

    <div class="habitacion-container">
        <div class="habitacion-card">
            <img src="../../../<?php echo str_replace('../../', '', $habitacion['foto']); ?>" alt="Foto de la habitación" class="habitacion-imagen">
            <div class="habitacion-titulo"><?php echo htmlspecialchars($habitacion['direccion']); ?></div>
            <div class="habitacion-info"><?php echo htmlspecialchars($habitacion['localidad'] . ", " . $habitacion['provincia']); ?></div>
            <div class="habitacion-info">Código Postal: <?php echo htmlspecialchars($habitacion['codigo_postal']); ?></div>
            <div class="habitacion-info"><?php echo htmlspecialchars($habitacion['descripcion']); ?></div>
            <div class="habitacion-propietario">Propietario: <?php echo htmlspecialchars($habitacion['nombre_propietario']); ?></div>
            <div class="habitacion-precio"><?php echo htmlspecialchars($habitacion['precio']); ?>€/mes</div>

            <div class="botones-container">
                <form action="procesaralquilerhabitacion.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_habitacion" value="<?php echo $habitacion['id_habitacion']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $habitacion['precio']; ?>">
                    <button type="submit" class="alquilar-button">Confirmar Alquiler</button>
                </form>

                <a href="../../../sesiones/chat.php?id_usuario=<?php echo $habitacion['id_usuario']; ?>" class="contactar-button">Contactar</a>
            </div>
        </div>

        <div class="estadisticas-container">
            <div class="estadisticas-titulo">Estadísticas de la zona</div>
            <div class="estadisticas-grid">
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo number_format($estadisticas['precio_medio'], 2); ?>€</div>
                    <div class="estadistica-label">Precio Medio</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['habitaciones_disponibles']; ?></div>
                    <div class="estadistica-label">Habitaciones Disponibles</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['habitaciones_ocupadas']; ?></div>
                    <div class="estadistica-label">Habitaciones Ocupadas</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['total_habitaciones']; ?></div>
                    <div class="estadistica-label">Total de Habitaciones</div>
                </div>
            </div>
        </div>

        <div class="empleos-container">
            <div class="empleos-titulo">Ofertas de Trabajo en la Zona</div>
            <?php
            if (mysqli_num_rows($resultado_empleos) > 0) {
                while ($empleo = mysqli_fetch_array($resultado_empleos)) {
                    echo "<div class='empleo-card'>";
                    echo "<div class='empleo-titulo'>" . htmlspecialchars($empleo['titulo']) . "</div>";
                    echo "<div class='empleo-info'>" . htmlspecialchars($empleo['descripcion']) . "</div>";
                    echo "<div class='empleo-info'>" . htmlspecialchars($empleo['direccion'] . ", " . $empleo['localidad'] . ", " . $empleo['provincia']) . "</div>";
                    echo "<div class='empleo-info'>Tipo de Contrato: " . htmlspecialchars($empleo['tipo_contrato']) . "</div>";
                    echo "<div class='empleo-empresa'>Empresa: " . htmlspecialchars($empleo['nombre_empresa']) . "</div>";
                    echo "<div class='empleo-salario'>" . htmlspecialchars($empleo['salario']) . "€</div>";
                    
                    echo "<form action='../../empleo_particular/ofertasincritas.php' method='POST'>";
                    echo "<input type='hidden' name='id_empleo' value='" . $empleo['id_empleo'] . "'>";
                    echo "<input type='hidden' name='id_empresa' value='" . $empleo['id_usuario'] . "'>";
                    echo "<button type='submit' class='contactar-empleo-button'>Inscribirse</button>";
                    echo "</form>";
                    
                    echo "</div>";
                }
            } else {
                echo "<div class='no-empleos'>No hay ofertas de trabajo en esta zona</div>";
            }
            ?>
        </div>

        <a href="alquilarhabitacion.php" class="volver-button">Volver a Habitaciones en Alquiler</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?> 