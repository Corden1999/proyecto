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

// Obtener el ID del local
$id_local = isset($_POST['id_local']) ? $_POST['id_local'] : null;
$precio = isset($_POST['precio']) ? $_POST['precio'] : null;

if (!$id_local || !$precio) {
    header("Location: alquilarlocal.php");
    exit();
}

// Obtener información detallada del local
$consulta = "SELECT l.*, u.nombre as nombre_propietario
             FROM Locales l 
             JOIN Usuarios u ON l.id_usuario = u.id_usuario 
             WHERE l.id_local = '$id_local'";
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");

$local = mysqli_fetch_array($resultado);

// Obtener estadísticas del código postal
$codigo_postal = $local['codigo_postal'];
$consulta_estadisticas = "SELECT 
    COUNT(*) as total_locales,
    AVG(CASE WHEN tipo = 'venta' THEN precio END) as precio_medio_venta,
    AVG(CASE WHEN tipo = 'alquiler' THEN precio END) as precio_medio_alquiler,
    COUNT(CASE WHEN tipo = 'venta' THEN 1 END) as num_locales_venta,
    COUNT(CASE WHEN tipo = 'alquiler' THEN 1 END) as num_locales_alquiler,
    COUNT(CASE WHEN disponible = 'si' THEN 1 END) as num_locales_disponibles,
    COUNT(CASE WHEN disponible = 'no' THEN 1 END) as num_locales_ocupados
    FROM Locales 
    WHERE codigo_postal = '$codigo_postal'";
$resultado_estadisticas = mysqli_query($conexion, $consulta_estadisticas)
    or die("Fallo en la consulta de estadísticas");
$estadisticas = mysqli_fetch_assoc($resultado_estadisticas);

// Obtener trabajadores que buscan empleo en el mismo código postal
$consulta_trabajadores = "SELECT c.*, u.nombre, u.email, u.telefono 
                         FROM Curriculum c 
                         JOIN Usuarios u ON c.id_usuario = u.id_usuario 
                         WHERE c.codigo_postal = '$codigo_postal' 
                         AND u.tipo_usuario = 'particular'";
$resultado_trabajadores = mysqli_query($conexion, $consulta_trabajadores)
    or die("Fallo en la consulta de trabajadores");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Detalles de Alquiler de Local</title>
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
            top: 0px;
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

        .local-container {
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

        .local-card {
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

        .local-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .local-imagen {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .local-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .local-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .local-propietario {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .local-precio {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .local-tipo {
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
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../../indexempresa.php"><img src="../../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='alquilarlocal.php'">Alquilar / comprar local</button>
            <div class="dropdown-content">
                <button onclick="location.href='comprarlocal.php'">locales en venta</button>
                <button onclick="location.href='alquilarlocal.php'">locales en alquiler</button>
                <button onclick="location.href='buscarcomprarlocal.php'">buscar locales en venta</button>
                <button onclick="location.href='buscaralquilarlocal.php'">buscar locales en alquiler</button>
                <button onclick="location.href='localesalquilados.php'">locales alquilados</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='arrendarlocal.php'">Arrendar / venderlocal</button>
            <div class="dropdown-content">
                <button onclick="location.href='mislocales.php'">mis locales</button>
                <button onclick="location.href='borrarmislocales.php'">borrar mis locales</button>
                <button onclick="location.href='editarmislocales.php'">editar mis locales</button>
                <button onclick="location.href='buscarmislocales.php'">buscar mis locales</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="local-container">
        <div class="local-card">
            <img src="../../../<?php echo str_replace('../../', '', $local['foto']); ?>" alt="Foto del local" class="local-imagen">
            <div class="local-titulo"><?php echo htmlspecialchars($local['direccion']); ?></div>
            <div class="local-info"><?php echo htmlspecialchars($local['localidad'] . ", " . $local['provincia']); ?></div>
            <div class="local-info">Código Postal: <?php echo htmlspecialchars($local['codigo_postal']); ?></div>
            <div class="local-info"><?php echo htmlspecialchars($local['descripcion']); ?></div>
            <div class="local-propietario">Propietario: <?php echo htmlspecialchars($local['nombre_propietario']); ?></div>
            <div class="local-precio"><?php echo htmlspecialchars($local['precio']); ?>€</div>
            <div class="local-tipo"><?php echo ucfirst(htmlspecialchars($local['tipo'])); ?></div>

            <div class="botones-container">
                <form action="procesaralquiler.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_local" value="<?php echo $local['id_local']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $local['precio']; ?>">
                    <button type="submit" class="alquilar-button">Confirmar Alquiler</button>
                </form>

                <a href="../../../sesiones/chatempresa.php?id_usuario=<?php echo $local['id_usuario']; ?>" class="contactar-button">Contactar</a>
            </div>
        </div>

        <div class="estadisticas-container">
            <div class="estadisticas-titulo">Estadísticas de la zona</div>
            <div class="estadisticas-grid">
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo number_format($estadisticas['precio_medio_alquiler'], 2); ?>€</div>
                    <div class="estadistica-label">Precio Medio Alquiler</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo number_format($estadisticas['precio_medio_venta'], 2); ?>€</div>
                    <div class="estadistica-label">Precio Medio Venta</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['total_locales']; ?></div>
                    <div class="estadistica-label">Total de Locales</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_locales_alquiler']; ?></div>
                    <div class="estadistica-label">Locales en Alquiler</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_locales_venta']; ?></div>
                    <div class="estadistica-label">Locales en Venta</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_locales_disponibles']; ?></div>
                    <div class="estadistica-label">Locales Disponibles</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_locales_ocupados']; ?></div>
                    <div class="estadistica-label">Locales Ocupados</div>
                </div>
            </div>
        </div>

        <div class="trabajadores-container">
            <div class="trabajadores-titulo">Trabajadores en la zona</div>
            <?php
            if (mysqli_num_rows($resultado_trabajadores) > 0) {
                while ($trabajador = mysqli_fetch_array($resultado_trabajadores)) {
                    echo "<div class='trabajador-card'>";
                    echo "<div class='trabajador-nombre'>" . htmlspecialchars($trabajador['nombre']) . "</div>";
                    echo "<div class='trabajador-info'>Email: " . htmlspecialchars($trabajador['email']) . "</div>";
                    echo "<div class='trabajador-info'>Teléfono: " . htmlspecialchars($trabajador['telefono']) . "</div>";
                    echo "<div class='trabajador-info'>Dirección: " . htmlspecialchars($trabajador['direccion']) . "</div>";
                    
                    echo "<div class='trabajador-experiencia'>";
                    echo "<div class='trabajador-experiencia-titulo'>Experiencia</div>";
                    echo "<div class='trabajador-info'>" . nl2br(htmlspecialchars($trabajador['experiencia'])) . "</div>";
                    echo "</div>";
                    
                    echo "<div class='trabajador-experiencia'>";
                    echo "<div class='trabajador-experiencia-titulo'>Formación</div>";
                    echo "<div class='trabajador-info'>" . nl2br(htmlspecialchars($trabajador['formacion'])) . "</div>";
                    echo "</div>";
                    
                    echo "<div class='trabajador-experiencia'>";
                    echo "<div class='trabajador-experiencia-titulo'>Habilidades</div>";
                    echo "<div class='trabajador-info'>" . nl2br(htmlspecialchars($trabajador['habilidades'])) . "</div>";
                    echo "</div>";
                    
                    echo "<form action='../../../sesiones/chatempresa.php' method='GET' style='display: inline;'>";
                    echo "<input type='hidden' name='id_usuario' value='" . $trabajador['id_usuario'] . "'>";
                    echo "<button type='submit' class='contactar-trabajador-button'>Contactar</button>";
                    echo "</form>";
                    
                    echo "</div>";
                }
            } else {
                echo "<div class='no-trabajadores'>No hay trabajadores buscando empleo en esta zona</div>";
            }
            ?>
        </div>

        <a href="alquilarlocal.php" class="volver-button">Volver a Locales en Alquiler</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?> 