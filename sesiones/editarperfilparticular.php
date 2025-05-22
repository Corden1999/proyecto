<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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

        .form-group input:focus {
            outline: none;
            border-color: #ffffff;
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
        <h1 class="titulo"><a href="../particular/indexparticular.php"><img src="../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='../particular/vivienda_particular/piso/piso.php'">Vivienda</button>
            <div class="dropdown-content">
                <button onclick="location.href='../particular/vivienda_particular/piso/piso.php'">Pisos</button>
                <button onclick="location.href='../particular/vivienda_particular/habitacion/habitacion.php'">Habitaciones</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='../particular/empleo_particular/empleosparticular.php'">Empleo</button>
        </div>
        <button onclick="location.href='../banca/banca.html'">Banca</button>
    </nav>

    <?php
$name = $_SESSION['name'];

echo "<div class='welcome-container'>
    <strong>¡Bienvenido! $name</strong><br>
    <a href='mensajeparticular.php'>Mensajes</a>
    <a href='editarperfil.php'>Editar Perfil</a>
    <a href='logout.php'>Cerrar Sesión</a>
</div>";	

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "rootroot";
$dbname = "proyecto";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $dbname)
or  die("Conexión fallida: " . mysqli_connect_error());

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT nombre, email, contraseña, telefono, direccion, codigo_postal FROM Usuarios WHERE id_usuario = '$id_usuario'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "No se encontró el usuario.";
}

// Cerrar la conexión
mysqli_close($conn);
?>

    <div class="form-container">
        <h2>Editar Perfil</h2>
        <form action="editarperfilparticular2.php" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" >
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" >
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo $row['telefono']; ?>" >
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo $row['direccion']; ?>" >
            </div>

            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" value="<?php $row['codigo_postal']; ?>" >
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="botones-formulario">
                <input type="submit" value="Actualizar" class="boton-login">
                <input type="reset" value="Limpiar" class="boton-login">
            </div>
        </form>
    </div>
</body>
</html>