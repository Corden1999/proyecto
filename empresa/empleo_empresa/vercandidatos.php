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

// Obtener el ID de la oferta
$id_empleo = isset($_POST['id_empleo']) ? $_POST['id_empleo'] : null;

if (!$id_empleo) {
    header("Location: misofertas.php");
    exit();
}

// Verificar que la oferta pertenece al usuario actual
$id_usuario = $_SESSION['id_usuario'];
$verificar = "SELECT * FROM Empleos WHERE id_empleo = '$id_empleo' AND id_usuario = '$id_usuario'";
$resultado_verificacion = mysqli_query($conexion, $verificar);

if (mysqli_num_rows($resultado_verificacion) === 0) {
    header("Location: misofertas.php");
    exit();
}

// Obtener información de la oferta
$oferta = mysqli_fetch_array($resultado_verificacion);

// Obtener los currículums de los candidatos inscritos
$consulta = "SELECT c.*, u.nombre as nombre_candidato, ie.fecha_inscripcion 
             FROM InscripcionesEmpleo ie 
             JOIN Curriculum c ON ie.id_usuario = c.id_usuario 
             JOIN Usuarios u ON c.id_usuario = u.id_usuario 
             WHERE ie.id_empleo = '$id_empleo' 
             ORDER BY ie.fecha_inscripcion DESC";
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");

$nfilas = mysqli_num_rows($resultado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Candidatos Inscritos</title>
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

        .candidatos-container {
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

        .oferta-info {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            color: #ffffff;
            margin-bottom: 20px;
        }

        .oferta-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .curriculum-card {
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

        .curriculum-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .curriculum-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .curriculum-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .curriculum-seccion {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ae8b4f;
        }

        .curriculum-seccion-titulo {
            color: #ae8b4f;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .fecha-inscripcion {
            color: #ae8b4f;
            font-size: 14px;
            margin-top: 10px;
            font-style: italic;
        }

        .no-candidatos {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .volver-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 15px;
            transition: all 0.3s ease;
            text-align: center;
            width: fit-content;
            text-decoration: none;
            display: inline-block;
        }

        .volver-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .botones-container {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .contratar-button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .contratar-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .contactar-button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .contactar-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexempresa.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='empleos.php'">Empleos</button>
            <div class="dropdown-content">
                <button onclick="location.href='publicaroferta.php'">publicar oferta</button>
                <button onclick="location.href='misofertas.php'">mis ofertas</button>
                <button onclick="location.href='borrarmisofertas.php'">borrar mis ofertas</button>
                <button onclick="location.href='editarmisofertas.php'">editar mis ofertas</button>
                <button onclick="location.href='buscarmisofertas.php'">buscar mis ofertas</button>
                <button onclick="location.href='misempleados.php'">mis empleados</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="candidatos-container">
        <div class="oferta-info">
            <div class="oferta-titulo"><?php echo htmlspecialchars($oferta['titulo']); ?></div>
            <div class="curriculum-info"><?php echo htmlspecialchars($oferta['descripcion']); ?></div>
        </div>

        <?php
        if ($nfilas > 0) {
            while ($candidato = mysqli_fetch_array($resultado)) {
                echo "<div class='curriculum-card'>";
                echo "<div class='curriculum-titulo'>" . htmlspecialchars($candidato['nombre_candidato']) . "</div>";
                
                echo "<div class='curriculum-info'>";
                echo "<strong>Email:</strong> " . htmlspecialchars($candidato['email']) . "<br>";
                echo "<strong>Teléfono:</strong> " . htmlspecialchars($candidato['telefono']) . "<br>";
                echo "<strong>Dirección:</strong> " . htmlspecialchars($candidato['direccion']) . "<br>";
                echo "<strong>Código Postal:</strong> " . htmlspecialchars($candidato['codigo_postal']);
                echo "</div>";
                
                echo "<div class='curriculum-seccion'>";
                echo "<div class='curriculum-seccion-titulo'>Experiencia Laboral</div>";
                echo "<div class='curriculum-info'>" . nl2br(htmlspecialchars($candidato['experiencia'])) . "</div>";
                echo "</div>";
                
                echo "<div class='curriculum-seccion'>";
                echo "<div class='curriculum-seccion-titulo'>Formación</div>";
                echo "<div class='curriculum-info'>" . nl2br(htmlspecialchars($candidato['formacion'])) . "</div>";
                echo "</div>";
                
                echo "<div class='curriculum-seccion'>";
                echo "<div class='curriculum-seccion-titulo'>Habilidades</div>";
                echo "<div class='curriculum-info'>" . nl2br(htmlspecialchars($candidato['habilidades'])) . "</div>";
                echo "</div>";
                
                echo "<div class='fecha-inscripcion'>Inscrito el: " . date('d/m/Y H:i', strtotime($candidato['fecha_inscripcion'])) . "</div>";
                
                echo "<div class='botones-container'>";
                echo "<form action='contratar.php' method='POST' style='display: inline;'>";
                echo "<input type='hidden' name='id_empleo' value='" . $id_empleo . "'>";
                echo "<input type='hidden' name='id_candidato' value='" . $candidato['id_usuario'] . "'>";
                echo "<button type='submit' class='contratar-button'>Contratar</button>";
                echo "</form>";
                
                echo "<form action='contactar.php' method='POST' style='display: inline;'>";
                echo "<input type='hidden' name='id_empleo' value='" . $id_empleo . "'>";
                echo "<input type='hidden' name='id_candidato' value='" . $candidato['id_usuario'] . "'>";
                echo "<input type='hidden' name='email_candidato' value='" . $candidato['email'] . "'>";
                echo "<button type='submit' class='contactar-button'>Contactar</button>";
                echo "</form>";
                echo "</div>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-candidatos'>No hay candidatos inscritos en esta oferta</div>";
        }
        ?>

        <a href="misofertas.php" class="volver-button">Volver a Mis Ofertas</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?> 