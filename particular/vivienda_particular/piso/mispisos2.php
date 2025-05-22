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

// Obtener el ID del piso
$id_piso = isset($_POST['id_piso']) ? $_POST['id_piso'] : null;
$precio = isset($_POST['precio']) ? $_POST['precio'] : null;

if (!$id_piso || !$precio) {
    header("Location: mispisos.php");
    exit();
}

// Obtener información detallada del piso
$consulta = "SELECT p.*, u.nombre as nombre_propietario
             FROM Pisos p 
             JOIN Usuarios u ON p.id_usuario = u.id_usuario 
             WHERE p.id_piso = '$id_piso' 
             AND p.id_usuario = " . $_SESSION['id_usuario'];
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");

$piso = mysqli_fetch_array($resultado);

// Obtener estadísticas del código postal
$codigo_postal = $piso['codigo_postal'];
$consulta_estadisticas = "SELECT 
    COUNT(*) as total_pisos,
    AVG(CASE WHEN tipo = 'alquiler' THEN precio ELSE NULL END) as precio_medio_alquiler,
    AVG(CASE WHEN tipo = 'venta' THEN precio ELSE NULL END) as precio_medio_venta,
    COUNT(CASE WHEN disponible = 'si' THEN 1 END) as num_pisos_disponibles,
    COUNT(CASE WHEN disponible = 'no' THEN 1 END) as num_pisos_ocupados,
    COUNT(CASE WHEN tipo = 'alquiler' THEN 1 END) as num_pisos_alquiler,
    COUNT(CASE WHEN tipo = 'venta' THEN 1 END) as num_pisos_venta
    FROM Pisos 
    WHERE codigo_postal = '$codigo_postal'";
$resultado_estadisticas = mysqli_query($conexion, $consulta_estadisticas)
    or die("Fallo en la consulta de estadísticas");
$estadisticas = mysqli_fetch_assoc($resultado_estadisticas);

// Obtener ofertas de trabajo del mismo código postal
$consulta_empleos = "SELECT e.*, u.nombre as nombre_empresa 
                    FROM Empleos e 
                    JOIN Usuarios u ON e.id_usuario = u.id_usuario 
                    WHERE e.codigo_postal = '$codigo_postal' 
                    ORDER BY e.fecha_publicacion DESC 
                    LIMIT 5";
$resultado_empleos = mysqli_query($conexion, $consulta_empleos)
    or die("Fallo en la consulta de empleos");

// Obtener información de alquiler/venta si existe
$consulta_transaccion = "";
if ($piso['tipo'] == 'alquiler') {
    $consulta_transaccion = "SELECT tpa.*, u.nombre as nombre_cliente 
                            FROM Transaccion_piso_alquiler tpa 
                            JOIN Usuarios u ON tpa.id_usuario_arrendatario = u.id_usuario 
                            WHERE tpa.id_piso = '$id_piso'";
} else {
    $consulta_transaccion = "SELECT tpv.*, u.nombre as nombre_cliente 
                            FROM Transaccion_piso_venta tpv 
                            JOIN Usuarios u ON tpv.id_usuario_comprador = u.id_usuario 
                            WHERE tpv.id_piso = '$id_piso'";
}
$resultado_transaccion = mysqli_query($conexion, $consulta_transaccion);
$transaccion = mysqli_fetch_assoc($resultado_transaccion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Detalles de Mi Piso</title>
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

        .piso-container {
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

        .piso-propietario {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
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

        .fecha-transaccion {
            color: #ae8b4f;
            font-size: 14px;
            margin-top: 10px;
            font-style: italic;
        }

        .botones-container {
            display: flex;
            gap: 5px;
            margin-top: 20px;
        }

        .editar-button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .editar-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .borrar-button {
            background-color: #ff4444;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .borrar-button:hover {
            background-color: #cc0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
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

        .estado-piso {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .cliente-info {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .fecha-transaccion {
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
            <button onclick="location.href='alquilarpiso.php'">Alquilar / comprar piso</button>
            <div class="dropdown-content">
                <button onclick="location.href='comprarpiso.php'">pisos en venta</button>
                <button onclick="location.href='alquilarpiso.php'">pisos en alquiler</button>
                <button onclick="location.href='buscarcomprarpiso.php'">buscar pisos en venta</button>
                <button onclick="location.href='buscaralquilarpiso.php'">buscar pisos en alquiler</button>
                <button onclick="location.href='pisosalquilados.php'">pisos alquilados</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='arrendarpiso.php'">Arrendar / vender piso</button>
            <div class="dropdown-content">
                <button onclick="location.href='mispisos.php'">mis pisos</button>
                <button onclick="location.href='buscarmispisos.php'">buscar mis pisos</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../../sesiones/mensajeparticular.php'>Mensajes</a>
        <a href='../../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
        <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="piso-container">
        <div class="piso-card">
            <img src="../../../<?php echo str_replace('../../', '', $piso['foto']); ?>" alt="Foto del piso" class="piso-imagen">
            <div class="piso-titulo"><?php echo htmlspecialchars($piso['direccion']); ?></div>
            <div class="piso-info"><?php echo htmlspecialchars($piso['localidad'] . ", " . $piso['provincia']); ?></div>
            <div class="piso-info">Código Postal: <?php echo htmlspecialchars($piso['codigo_postal']); ?></div>
            <div class="piso-info"><?php echo htmlspecialchars($piso['descripcion']); ?></div>
            <div class="piso-propietario">Propietario: <?php echo htmlspecialchars($piso['nombre_propietario']); ?></div>
            <div class="piso-precio"><?php echo htmlspecialchars($piso['precio']); ?>€<?php echo $piso['tipo'] == 'alquiler' ? '/mes' : ''; ?></div>
            <div class="piso-tipo"><?php echo ucfirst($piso['tipo']); ?></div>
            <div class="estado-piso">Estado: <?php echo $piso['disponible'] == 'si' ? 'Disponible' : 'No disponible'; ?></div>

            <?php if ($transaccion): ?>
                <div class="cliente-info">
                    <?php echo $piso['tipo'] == 'alquiler' ? 'Arrendatario' : 'Comprador'; ?>: 
                    <?php echo htmlspecialchars($transaccion['nombre_cliente']); ?>
                </div>
                <div class="fecha-transaccion">
                    Fecha de <?php echo $piso['tipo'] == 'alquiler' ? 'alquiler' : 'venta'; ?>: 
                    <?php echo date('d/m/Y', strtotime($transaccion['fecha_transaccion'])); ?>
                </div>
            <?php endif; ?>

            <div class="botones-container">
                <form action="editarmispisos2.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_piso" value="<?php echo $piso['id_piso']; ?>">
                    <input type="hidden" name="direccion" value="<?php echo htmlspecialchars($piso['direccion']); ?>">
                    <input type="hidden" name="localidad" value="<?php echo htmlspecialchars($piso['localidad']); ?>">
                    <input type="hidden" name="provincia" value="<?php echo htmlspecialchars($piso['provincia']); ?>">
                    <input type="hidden" name="codigo_postal" value="<?php echo htmlspecialchars($piso['codigo_postal']); ?>">
                    <input type="hidden" name="precio" value="<?php echo htmlspecialchars($piso['precio']); ?>">
                    <input type="hidden" name="descripcion" value="<?php echo htmlspecialchars($piso['descripcion']); ?>">
                    <input type="hidden" name="tipo" value="<?php echo htmlspecialchars($piso['tipo']); ?>">
                    <input type="hidden" name="disponible" value="<?php echo htmlspecialchars($piso['disponible']); ?>">
                    <input type="hidden" name="foto" value="<?php echo htmlspecialchars($piso['foto']); ?>">
                    <button type="submit" class="editar-button">Editar</button>
                </form>

                <form action="borrarmispisos2.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id_piso" value="<?php echo $piso['id_piso']; ?>">
                    <button type="submit" class="borrar-button" onclick="return confirm('¿Estás seguro de que quieres eliminar este piso?');">Borrar</button>
                </form>
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
                    <div class="estadistica-valor"><?php echo $estadisticas['total_pisos']; ?></div>
                    <div class="estadistica-label">Total de Pisos</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_pisos_alquiler']; ?></div>
                    <div class="estadistica-label">Pisos en Alquiler</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_pisos_venta']; ?></div>
                    <div class="estadistica-label">Pisos en Venta</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_pisos_disponibles']; ?></div>
                    <div class="estadistica-label">Pisos Disponibles</div>
                </div>
                <div class="estadistica-card">
                    <div class="estadistica-valor"><?php echo $estadisticas['num_pisos_ocupados']; ?></div>
                    <div class="estadistica-label">Pisos Ocupados</div>
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

        <a href="mispisos.php" class="volver-button">Volver a Mis Pisos</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?> 