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

// Obtener los pisos alquilados por el usuario
$sql = "SELECT DISTINCT p.*, tpa.fecha_transaccion 
        FROM Pisos p 
        JOIN Transaccion_piso_alquiler tpa ON p.id_piso = tpa.id_piso 
        WHERE tpa.id_usuario_arrendatario = " . $_SESSION['id_usuario'] . " 
        AND p.disponible = 'no'
        GROUP BY p.id_piso
        ORDER BY tpa.fecha_transaccion DESC";
$resultado = mysqli_query($conexion, $sql)
    or die("Error al ejecutar la consulta");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Mis Pisos Alquilados</title>
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

        .anular-button {
            background-color: #ff4444;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            margin-top: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .anular-button:hover {
            background-color: #cc0000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .no-pisos {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .fecha-alquiler {
            color: #ae8b4f;
            font-size: 14px;
            margin-top: 10px;
            font-style: italic;
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

    <div class="pisos-container">
        <?php
        $nfilas = mysqli_num_rows($resultado);
        if ($nfilas > 0) {
            while ($fila = mysqli_fetch_array($resultado)) {
                echo "<div class='piso-card'>";
                echo "<img src='../../../" . str_replace('../../', '', $fila['foto']) . "' alt='Foto del piso' class='piso-imagen'>";
                echo "<div class='piso-titulo'>" . $fila['direccion'] . "</div>";
                echo "<div class='piso-info'>" . $fila['localidad'] . ", " . $fila['provincia'] . "</div>";
                echo "<div class='piso-info'>Código Postal: " . $fila['codigo_postal'] . "</div>";
                echo "<div class='piso-info'>" . $fila['descripcion'] . "</div>";
                
                // Obtener información del propietario
                $sql_propietario = "SELECT nombre FROM Usuarios WHERE id_usuario = " . $fila['id_usuario'];
                $result_propietario = mysqli_query($conexion, $sql_propietario);
                $propietario = mysqli_fetch_assoc($result_propietario);
                
                echo "<div class='piso-propietario'>Propietario: " . $propietario['nombre'] . "</div>";
                echo "<div class='piso-precio'>" . $fila['precio'] . "€/mes</div>";
                echo "<div class='piso-tipo'>" . ucfirst($fila['tipo']) . "</div>";
                echo "<div class='fecha-alquiler'>Alquilado el: " . date('d/m/Y', strtotime($fila['fecha_transaccion'])) . "</div>";
                
                echo "<form action='procesaranularalquilerpiso.php' method='POST'>";
                echo "<input type='hidden' name='id_piso' value='" . $fila['id_piso'] . "'>";
                echo "<button type='submit' class='anular-button'>Anular Alquiler</button>";
                echo "</form>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-pisos'>No tienes pisos alquilados</div>";
        }

        mysqli_close($conexion);
        ?>
    </div>
</body>
</html> 