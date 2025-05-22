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

// Obtener datos del formulario
$direccion = $_POST['direccion'];
$localidad = $_POST['localidad'];
$provincia = $_POST['provincia'];
$codigo_postal = $_POST['codigo_postal'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];
$tipo = $_POST['tipo'];
$target_dir = "../../img/";

// Verificar si se envió un archivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    // Obtener el nombre y ruta del archivo destino
    $target_file = $target_dir . basename($file["name"]);

    // Verificar si el archivo es realmente una imagen
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        die("El archivo seleccionado no es una imagen.");
    }

    // Verificar si el archivo ya existe
    if (file_exists($target_file)) {
        die("El archivo ya existe en el servidor.");
    }

    // Intentar mover el archivo al directorio de destino
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        echo "La imagen " . htmlspecialchars(basename($file["name"])) . " se ha subido correctamente.";
    } else {
        echo "Hubo un error al subir el archivo.";
    }
} else {
    echo "No se ha seleccionado ningún archivo.";
}

// Enviar consulta
$id_usuario = $_SESSION['id_usuario'];
$instruccion = "INSERT INTO Locales (direccion, localidad, provincia, codigo_postal, precio, descripcion, disponible, tipo, foto, id_usuario) 
                VALUES ('$direccion', '$localidad', '$provincia', '$codigo_postal', '$precio', '$descripcion', 'si', '$tipo', '$target_file', '$id_usuario')";

if (mysqli_query($conexion, $instruccion)) {
    echo "Local insertado con éxito.";
} else {
    echo "Error al insertar local: " . mysqli_error($conexion);
}

// Cerrar la conexión
mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Procesar Local</title>
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

        .form-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            margin: 30px auto;
            padding: 30px;
            max-width: 800px;
            color: #ffffff;
        }

        .form-container h2 {
            color: #ae8b4f;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .message {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            font-size: 18px;
        }

        .success {
            background-color: #4CAF50;
            color: white;
        }

        .error {
            background-color: #f44336;
            color: white;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            background-color: #ae8b4f;
            color: #000000;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .back-button a:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
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

    <div class="form-container">
        <h2>Local insertado con éxito</h2>
        <div class="back-button">
            <a href="arrendarlocal.php">Volver al formulario</a>
        </div>
    </div>
</body>
</html>