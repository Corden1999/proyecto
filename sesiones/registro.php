<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Junteate</title>
    <style>
        body {
            background-color: #000000;
            margin: 0;
            padding: 0;
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
        
        .botones {
            text-align: right;
            margin: 10px;
            display: inline-block;
            float: right;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        
        .menu {
            background-color: #ae8b4f;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            padding: 15px 50px;
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
        
        .botones button {
            background-color: #ffffff;
            border: 2px solid #000000;
            color: #000000;
            padding: 12px 25px;
            margin: 0 5px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }
        
        .botones button:hover {
            background-color: #000000;
            color: #ffffff;
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

        .center-container {
            background-color: #000000;
            border: 2px solid #ffffff;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            margin: 50px auto;
            padding: 40px;
            max-width: 400px;
            text-align: center;
            color: #ffffff;
        }

        .center-container h3 {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .login-button {
            display: inline-block;
            background-color: #ae8b4f;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .error-message {
            color: #ff4444;
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ff4444;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="../index.html" class="titulo">
            <img src="../img/titulo.png" alt="Junteate Logo">
        </a>
        <div class="botones">
            <button onclick="location.href='iniciosesion.html'">INICIO DE SESIÓN</button>
            <button onclick="location.href='registro.html'">REGISTRO</button>
        </div>
    </div>
    
    <nav class="menu">
        <button onclick="location.href='#vivienda'">VIVIENDA</button>
        <button onclick="location.href='#empleo'">EMPLEO</button>
        <button onclick="location.href='../banca/banca.html'">BANCA</button>
    </nav>

    <?php
    include 'conn.php';

    $conn = mysqli_connect("localhost", "root", "rootroot", "proyecto");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $codigo_postal = $_POST['codigo_postal'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Encriptar la contraseña
    $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

    // Preparar la consulta SQL
    $query = "INSERT INTO Usuarios (nombre, email, contraseña, telefono, direccion, codigo_postal, tipo_usuario) 
              VALUES ('$nombre', '$email', '$password_encriptada', '$telefono', '$direccion', '$codigo_postal', '$tipo_usuario')";

    if (mysqli_query($conn, $query)) {
        // Obtener el ID del usuario recién creado
        $id_usuario = mysqli_insert_id($conn);
        
        // Crear una cuenta bancaria para el nuevo usuario
        $query_cuenta = "INSERT INTO Cuenta (id_usuario, saldo) VALUES ($id_usuario, 0.00)";
        mysqli_query($conn, $query_cuenta);

        echo "<div class='center-container'>
                <h3>La cuenta ha sido creada exitosamente.</h3>
                <a href='iniciosesion.html' class='login-button'>Inicio de sesión</a>
              </div>";
    } else {
        echo "<div class='center-container'>
                <div class='error-message'>
                    Error: " . mysqli_error($conn) . "
                </div>
                <a href='registro.html' class='login-button'>Volver al registro</a>
              </div>";
    }

    mysqli_close($conn);
    ?>
</body>
</html>