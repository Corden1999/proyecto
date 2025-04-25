<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Junteate - Editar Perfil</title>
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
            margin-top: 40px;
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
            padding: 30px;
            margin: 30px auto;
            max-width: 600px;
            color: #ffffff;
        }

        .form-container h2 {
            color: #ae8b4f;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ae8b4f;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .botones-formulario {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .boton-login {
            background-color: #ae8b4f;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .boton-login:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .result-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            max-width: 600px;
            color: #ffffff;
            text-align: center;
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .success-text {
            color: #ae8b4f;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-text {
            color: #ff4444;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .back-link {
            background-color: #ae8b4f;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .back-link:hover {
            background-color: #ffffff;
            color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../index.html"><img src="../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='#vivienda'">Vivienda</button>
            <div class="dropdown-content">
                <button onclick="location.href='../vivienda/buscar_piso.html'">Buscar Piso</button>
                <button onclick="location.href='../vivienda/buscar_habitacion.html'">Buscar Habitación</button>
                <button onclick="location.href='../vivienda/buscar_local.html'">Buscar Local</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='#empleo'">Empleo</button>
            <div class="dropdown-content">
                <button onclick="location.href='../empleo/buscar.html'">Buscar Empleo</button>
                <button onclick="location.href='../empleo/mis_solicitudes.html'">Mis Solicitudes</button>
            </div>
        </div>
        <button onclick="location.href='../banca/banca.html'">Banca</button>
    </nav>

    <?php

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

echo "<div class='welcome-container'>
    <strong>¡Bienvenido! $name</strong><br>
    <a href='editarperfil.php'>Editar Perfil</a>
    <a href='logout.php'>Cerrar Sesión</a>
</div>";

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "proyecto";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Recuperar datos del formulario
$id_usuario = $_SESSION['id_usuario'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];
$codigo_postal = $_POST['codigo_postal'];
$password = $_POST['password'];

// Preparar y ejecutar la consulta de actualización
$sql = "UPDATE Usuarios SET 
        nombre = '$nombre', 
        email = '$email', 
        telefono = '$telefono', 
        direccion = '$direccion', 
        codigo_postal = '$codigo_postal'";

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", contraseña = '$password_hash'";
}

$sql .= " WHERE id_usuario = '$id_usuario'";

if (mysqli_query($conn, $sql)) {
    echo "<div class='result-container'>
            <div class='success-text'>Usuario actualizado con éxito</div>
            <a href='editarperfil.php' class='back-link'>Volver al Perfil</a>
          </div>";
} else {
    echo "<div class='result-container'>
            <div class='error-text'>Error al actualizar: " . mysqli_error($conn) . "</div>
            <a href='editarperfil.php' class='back-link'>Volver al Perfil</a>
          </div>";
}

// Cerrar la conexión
mysqli_close($conn);
?>
</body>
</html>