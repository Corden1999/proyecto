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

// Obtener y sanitizar los datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
$localidad = mysqli_real_escape_string($conexion, $_POST['localidad']);
$provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
$codigo_postal = mysqli_real_escape_string($conexion, $_POST['codigo_postal']);
$tipo_contrato = mysqli_real_escape_string($conexion, $_POST['tipo_contrato']);
$salario = mysqli_real_escape_string($conexion, $_POST['salario']);

// Insertar los datos en la tabla Empleos
$instruccion = "INSERT INTO Empleos (id_usuario, titulo, descripcion, direccion, localidad, provincia, codigo_postal, tipo_contrato, salario) 
                VALUES ('$id_usuario', '$titulo', '$descripcion', '$direccion', '$localidad', '$provincia', '$codigo_postal', '$tipo_contrato', '$salario')";

$resultado = mysqli_query($conexion, $instruccion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Empleo Publicado</title>
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

        .success-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            color: #ffffff;
            max-width: 500px;
            width: 90%;
            margin: 40px auto;
        }
        
        .success-message {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
        }
        
        .redirect-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .redirect-button:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .error-message {
            color: #ff4444;
            text-align: center;
            margin: 40px auto;
            max-width: 500px;
            padding: 20px;
            border: 2px solid #ff4444;
            border-radius: 15px;
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

    <?php if ($resultado): ?>
        <div class="success-container">
            <div class="success-message">¡Empleo insertado con éxito!</div>
            <a href="misofertas.php" class="redirect-button">Ver mis ofertas</a>
        </div>
    <?php else: ?>
        <div class="error-message">
            Error al insertar el empleo: <?php echo mysqli_error($conexion); ?>
        </div>
    <?php endif; ?>

    <?php
    // Cerrar conexión
    mysqli_close($conexion);
    ?>
</body>
</html>