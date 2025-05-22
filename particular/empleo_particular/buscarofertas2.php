<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Mis Ofertas de Empleo</title>
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
            justify-content: center;
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

        .ofertas-container {
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

        .oferta-card {
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

        .oferta-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .oferta-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .oferta-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .oferta-ubicacion {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .oferta-salario {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .oferta-tipo {
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

        .oferta-empresa {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ae8b4f;
        }

        .oferta-empresa-info {
            color: #ae8b4f;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .no-ofertas {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .inscribirse-button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
            transition: all 0.3s ease;
            text-align: center;
            width: fit-content;
        }

        .inscribirse-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .desapuntarse-button {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
            transition: all 0.3s ease;
            text-align: center;
            width: fit-content;
        }

        .desapuntarse-button:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexparticular.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='empleosparticular.php'">Empleos</button>
            <div class="dropdown-content">
                <button onclick="location.href='micurriculum.php'">mi curriculum</button>
                <button onclick="location.href='ofertasinscritas.php'">ofertas inscritas</button>
                <button onclick="location.href='buscarofertas.php'">buscar ofertas</button>
            </div>
        </div>
    </nav>

    <?php
    $name = $_SESSION['name'];

    echo "<div class='welcome-container'>
        <strong>¡Bienvenido! $name</strong><br>
        <a href='../../sesiones/mensajeparticular.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>";

    // Conectar con el servidor de base de datos
    $conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
        or die("No se puede conectar con el servidor");

    // Construir la consulta SQL base
    $sql = "SELECT e.*, u.nombre as nombre_empresa 
            FROM Empleos e 
            JOIN Usuarios u ON e.id_usuario = u.id_usuario 
            WHERE 1=1";

    // Añadir condiciones solo si los campos no están vacíos
    if (!empty($_POST['titulo'])) {
        $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
        $sql .= " AND e.titulo LIKE '%$titulo%'";
    }
    if (!empty($_POST['direccion'])) {
        $direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
        $sql .= " AND e.direccion LIKE '%$direccion%'";
    }
    if (!empty($_POST['localidad'])) {
        $localidad = mysqli_real_escape_string($conexion, $_POST['localidad']);
        $sql .= " AND e.localidad LIKE '%$localidad%'";
    }
    if (!empty($_POST['provincia'])) {
        $provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
        $sql .= " AND e.provincia LIKE '%$provincia%'";
    }
    if (!empty($_POST['tipo_contrato'])) {
        $tipo_contrato = mysqli_real_escape_string($conexion, $_POST['tipo_contrato']);
        $sql .= " AND e.tipo_contrato = '$tipo_contrato'";
    }
    if (!empty($_POST['codigo_postal'])) {
        $codigo_postal = mysqli_real_escape_string($conexion, $_POST['codigo_postal']);
        $sql .= " AND e.codigo_postal LIKE '%$codigo_postal%'";
    }
    if (!empty($_POST['salario'])) {
        $salario = mysqli_real_escape_string($conexion, $_POST['salario']);
        $sql .= " AND e.salario <= '$salario'";
    }

    $resultado = mysqli_query($conexion, $sql)
        or die("Error al ejecutar la consulta: " . mysqli_error($conexion));

    $nfilas = mysqli_num_rows($resultado);
    if ($nfilas > 0) {
        echo "<div class='ofertas-container'>";
        while ($fila = mysqli_fetch_array($resultado)) {
            echo "<div class='oferta-card'>";
            echo "<div class='oferta-titulo'>" . htmlspecialchars($fila['titulo']) . "</div>";
            echo "<div class='oferta-info'>" . htmlspecialchars($fila['descripcion']) . "</div>";
            echo "<div class='oferta-ubicacion'>" . htmlspecialchars($fila['direccion']) . ", " . htmlspecialchars($fila['localidad']) . ", " . htmlspecialchars($fila['provincia']) . "</div>";
            echo "<div class='oferta-info'>Código Postal: " . htmlspecialchars($fila['codigo_postal']) . "</div>";
            echo "<div class='oferta-salario'>" . htmlspecialchars($fila['salario']) . "€</div>";
            echo "<div class='oferta-tipo'>" . htmlspecialchars($fila['tipo_contrato']) . "</div>";
            echo "<div class='oferta-empresa'>";
            echo "<div class='oferta-empresa-info'>Publicado por: " . htmlspecialchars($fila['nombre_empresa']) . "</div>";
            echo "</div>";
            
            // Botón para inscribirse
            echo "<form action='ofertasinscritas.php' method='POST'>";
            echo "<input type='hidden' name='id_empleo' value='" . $fila['id_empleo'] . "'>";
            echo "<button type='submit' class='inscribirse-button'>Inscribirse</button>";
            echo "</form>";
            
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<div class='no-ofertas'>No hay ofertas que coincidan con los criterios de búsqueda</div>";
    }
    
    mysqli_close($conexion);
    ?>
</body>
</html>