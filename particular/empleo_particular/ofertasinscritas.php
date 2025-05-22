<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}
$id_usuario = $_SESSION['id_usuario'];
$name = $_SESSION['name'];

$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Si se recibió un ID de empleo para desapuntarse
if (isset($_POST['desapuntarse']) && isset($_POST['id_empleo'])) {
    $id_empleo = $_POST['id_empleo'];
    $eliminar = "DELETE FROM InscripcionesEmpleo WHERE id_usuario = '$id_usuario' AND id_empleo = '$id_empleo'";
    
    if (mysqli_query($conexion, $eliminar)) {
        echo "<script>alert('Te has desapuntado correctamente de la oferta');</script>";
    } else {
        echo "<script>alert('Error al desapuntarte de la oferta');</script>";
    }
}

// Si se recibió un ID de empleo para inscribirse
if (isset($_POST['id_empleo']) && !isset($_POST['desapuntarse'])) {
    $id_empleo = $_POST['id_empleo'];
    
    // Verificar si ya está inscrito
    $verificar = "SELECT * FROM InscripcionesEmpleo WHERE id_usuario = '$id_usuario' AND id_empleo = '$id_empleo'";
    $resultado_verificacion = mysqli_query($conexion, $verificar);
    
    if (mysqli_num_rows($resultado_verificacion) > 0) {
        echo "<script>alert('Ya estás inscrito en esta oferta');</script>";
    } else {
        // Insertar la inscripción
        $fecha_inscripcion = date('Y-m-d H:i:s');
        $insertar = "INSERT INTO InscripcionesEmpleo (id_usuario, id_empleo, fecha_inscripcion) 
                     VALUES ('$id_usuario', '$id_empleo', '$fecha_inscripcion')";
        
        if (mysqli_query($conexion, $insertar)) {
            echo "<script>alert('Te has inscrito correctamente en la oferta');</script>";
        } else {
            echo "<script>alert('Error al inscribirse en la oferta');</script>";
        }
    }
}

// Consulta para obtener las ofertas en las que el usuario está inscrito
$consulta = "
    SELECT e.*, u.nombre AS nombre_empresa
    FROM InscripcionesEmpleo ie
    JOIN Empleos e ON ie.id_empleo = e.id_empleo
    JOIN Usuarios u ON e.id_usuario = u.id_usuario
    WHERE ie.id_usuario = '$id_usuario'
    ORDER BY ie.fecha_inscripcion DESC
";
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta: " . mysqli_error($conexion));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Ofertas Inscritas</title>
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

        .no-ofertas {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexparticular.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
        <div class='welcome-container'>
            <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
            <a href='../../sesiones/mensajeparticular.php'>Mensajes</a>
            <a href='../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
            <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
        </div>
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
    <div class="ofertas-container">
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($oferta = mysqli_fetch_assoc($resultado)) {
                echo "<div class='oferta-card'>";
                echo "<div class='oferta-titulo'>" . htmlspecialchars($oferta['titulo']) . "</div>";
                echo "<div class='oferta-info'>" . htmlspecialchars($oferta['descripcion']) . "</div>";
                echo "<div class='oferta-ubicacion'>" . htmlspecialchars($oferta['direccion']) . ", " . htmlspecialchars($oferta['localidad']) . ", " . htmlspecialchars($oferta['provincia']) . "</div>";
                echo "<div class='oferta-info'>Código Postal: " . htmlspecialchars($oferta['codigo_postal']) . "</div>";
                echo "<div class='oferta-salario'>" . htmlspecialchars($oferta['salario']) . "€</div>";
                echo "<div class='oferta-tipo'>" . htmlspecialchars($oferta['tipo_contrato']) . "</div>";
                
                // Información de la empresa
                echo "<div class='oferta-empresa'>";
                echo "<div class='oferta-empresa-info'>Publicado por: " . htmlspecialchars($oferta['nombre_empresa']) . "</div>";
                echo "</div>";
                
                // Botón de desapuntarse
                echo "<form action='ofertasinscritas.php' method='POST'>";
                echo "<input type='hidden' name='id_empleo' value='" . $oferta['id_empleo'] . "'>";
                echo "<input type='hidden' name='desapuntarse' value='1'>";
                echo "<button type='submit' class='desapuntarse-button'>Desapuntarse</button>";
                echo "</form>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-ofertas'>No estás inscrito en ninguna oferta de empleo.</div>";
        }
        mysqli_close($conexion);
        ?>
    </div>
</body>
</html>