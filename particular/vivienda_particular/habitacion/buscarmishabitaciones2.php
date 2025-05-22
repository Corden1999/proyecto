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

// Obtener los parámetros de búsqueda
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$localidad = isset($_POST['localidad']) ? $_POST['localidad'] : '';
$provincia = isset($_POST['provincia']) ? $_POST['provincia'] : '';
$codigo_postal = isset($_POST['codigo_postal']) ? $_POST['codigo_postal'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';

// Construir la consulta SQL
$consulta = "SELECT h.*, u.nombre as nombre_propietario
             FROM Habitaciones h 
             JOIN Usuarios u ON h.id_usuario = u.id_usuario 
             WHERE h.id_usuario = " . $_SESSION['id_usuario'];

if (!empty($direccion)) {
    $consulta .= " AND h.direccion LIKE '%$direccion%'";
}
if (!empty($localidad)) {
    $consulta .= " AND h.localidad LIKE '%$localidad%'";
}
if (!empty($provincia)) {
    $consulta .= " AND h.provincia LIKE '%$provincia%'";
}
if (!empty($codigo_postal)) {
    $consulta .= " AND h.codigo_postal LIKE '%$codigo_postal%'";
}
if (!empty($precio)) {
    $consulta .= " AND h.precio <= $precio";
}

$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Resultados de Búsqueda</title>
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

        .pisos-container {
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

        .piso-card {
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

        .piso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .piso-imagen {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .piso-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .piso-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .piso-precio {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .piso-tipo {
            display: block;
            padding: 8px 15px;
            background-color: #ae8b4f;
            color: #000000;
            border-radius: 15px;
            font-size: 14px;
            margin: 15px 0 0;
            font-weight: bold;
            text-align: left;
            width: fit-content;
        }

        .piso-disponible {
            display: block;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: #000000;
            border-radius: 15px;
            font-size: 14px;
            margin: 15px 0 0;
            font-weight: bold;
            text-align: left;
            width: fit-content;
        }

        .piso-no-disponible {
            display: block;
            padding: 8px 15px;
            background-color: #ff4444;
            color: #000000;
            border-radius: 15px;
            font-size: 14px;
            margin: 15px 0 0;
            font-weight: bold;
            text-align: left;
            width: fit-content;
        }

        .no-pisos {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .ver-detalles-button {
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
        }

        .ver-detalles-button:hover {
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

    <div class="pisos-container">
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($habitacion = mysqli_fetch_assoc($resultado)) {
                echo "<div class='piso-card'>";
                echo "<img src='../../../" . str_replace('../../', '', $habitacion['foto']) . "' alt='Foto de la habitación' class='piso-imagen'>";
                echo "<div class='piso-titulo'>" . htmlspecialchars($habitacion['direccion']) . "</div>";
                echo "<div class='piso-info'>" . htmlspecialchars($habitacion['localidad'] . ", " . $habitacion['provincia']) . "</div>";
                echo "<div class='piso-info'>Código Postal: " . htmlspecialchars($habitacion['codigo_postal']) . "</div>";
                echo "<div class='piso-info'>" . htmlspecialchars($habitacion['descripcion']) . "</div>";
                echo "<div class='piso-precio'>" . htmlspecialchars($habitacion['precio']) . "€/mes</div>";
                if ($habitacion['disponible'] == 'si') {
                    echo "<div class='piso-disponible'>Disponible</div>";
                } else {
                    echo "<div class='piso-no-disponible'>No disponible</div>";
                }
                
                echo "<form action='mishabitaciones2.php' method='POST'>";
                echo "<input type='hidden' name='id_habitacion' value='" . $habitacion['id_habitacion'] . "'>";
                echo "<input type='hidden' name='precio' value='" . $habitacion['precio'] . "'>";
                echo "<button type='submit' class='ver-detalles-button'>Ver Detalles</button>";
                echo "</form>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-pisos'>No se encontraron habitaciones que coincidan con los criterios de búsqueda</div>";
        }
        ?>

        <a href="buscarmishabitaciones.php" class="volver-button">Volver a Buscar</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>