<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_habitacion = $_POST['id_habitacion'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $codigo_postal = $_POST['codigo_postal'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $disponible = $_POST['disponible'];

    // Procesar la foto si se ha subido una nueva
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../../../img/habitaciones/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
        // Verificar si es una imagen real
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            // Verificar el tipo de archivo
            if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg") {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    $foto = "img/habitaciones/" . basename($_FILES["foto"]["name"]);
                }
            }
        }
    } else {
        // Mantener la foto actual
        $consulta_foto = "SELECT foto FROM Habitaciones WHERE id_habitacion = '$id_habitacion'";
        $resultado_foto = mysqli_query($conexion, $consulta_foto);
        $fila_foto = mysqli_fetch_array($resultado_foto);
        $foto = $fila_foto['foto'];
    }

    // Actualizar la habitación en la base de datos
    $consulta = "UPDATE Habitaciones SET 
                 direccion = '$direccion',
                 localidad = '$localidad',
                 provincia = '$provincia',
                 codigo_postal = '$codigo_postal',
                 precio = '$precio',
                 descripcion = '$descripcion',
                 disponible = '$disponible',
                 foto = '$foto'
                 WHERE id_habitacion = '$id_habitacion' 
                 AND id_usuario = " . $_SESSION['id_usuario'];

    if (mysqli_query($conexion, $consulta)) {
        // Redirigir de vuelta a la página de detalles
        header("Location: mishabitaciones2.php");
        exit();
    } else {
        echo "Error al actualizar la habitación: " . mysqli_error($conexion);
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Editar Habitación</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #ae8b4f;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-group input[type="submit"] {
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
            width: auto;
            margin-top: 20px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .result-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 50px auto;
            max-width: 600px;
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
        }

        .success-text {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .back-link {
            background-color: #ae8b4f;
            color: #000000;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
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
                <button onclick="location.href='borrarmishabitaciones.php'">borrar habitaciones</button>
                <button onclick="location.href='editarmishabitaciones.php'">editar habitaciones</button>
                <button onclick="location.href='buscarmishabitaciones.php'">buscar mis habitaciones</button>
            </div>
        </div>
    </nav>

    <?php
     $name = $_SESSION['name'];
     echo "<div class='welcome-container'>
         <strong>¡Bienvenido! $name</strong><br>
         <a href='../../../sesiones/mensajeparticular.php'>Mensajes</a>
         <a href='../../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
         <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
     </div>";
     
     $servername = "localhost";
     $username = "root";
     $password = "rootroot";
     $dbname = "proyecto";
 
     $conn = new mysqli($servername, $username, $password, $dbname)
      or die("Connection failed: " . mysqli_connect_error());
 
     $id_habitacion = $_POST['id_habitacion'];
     $direccion = $_POST['direccion'];
     $localidad = $_POST['localidad'];
     $provincia = $_POST['provincia'];
     $codigo_postal = $_POST['codigo_postal'];
     $descripcion = $_POST['descripcion'];
     $precio = $_POST['precio'];
 
     $sql = "UPDATE Habitaciones SET direccion = '$direccion', localidad = '$localidad', provincia = '$provincia', codigo_postal = '$codigo_postal', descripcion = '$descripcion', precio = '$precio' WHERE id_habitacion = '$id_habitacion'";
        
    if (mysqli_query($conn, $sql)) {
        echo "<div class='result-container'>
                <div class='success-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Habitación actualizada con éxito</div>
                <a href='editarmishabitaciones.php' style='background-color: #ae8b4f; color: #000000; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;'>Volver a mis habitaciones</a>
              </div>";
    } else {
        echo "<div class='result-container'>
                <div class='error-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Error al actualizar: " . mysqli_error($conn) . "</div>
                <a href='editarmishabitaciones.php' style='background-color: #ae8b4f; color: #000000; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;'>Volver a mis habitaciones</a>
              </div>";
    }

    mysqli_close($conn);
    ?>

</body>
</html>