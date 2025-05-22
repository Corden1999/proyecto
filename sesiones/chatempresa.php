<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Verificar si se proporcionó un ID de usuario
if (!isset($_GET['id_usuario'])) {
    header("Location: mensajempresa.php");
    exit();
}

$id_otro_usuario = $_GET['id_usuario'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener información del otro usuario
$sql_usuario = "SELECT nombre FROM Usuarios WHERE id_usuario = '$id_otro_usuario'";
$resultado_usuario = mysqli_query($conexion, $sql_usuario)
    or die("Error al obtener información del usuario");
$otro_usuario = mysqli_fetch_assoc($resultado_usuario);

// Procesar el envío de mensajes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensaje'])) {
    $mensaje = $_POST['mensaje'];
    $sql_insert = "INSERT INTO Chat (id_remitente, id_destinatario, mensaje) 
                   VALUES (" . $_SESSION['id_usuario'] . ", '$id_otro_usuario', '$mensaje')";
    mysqli_query($conexion, $sql_insert)
        or die("Error al enviar el mensaje");
}

// Obtener los mensajes de la conversación
$sql_mensajes = "SELECT c.*, u.nombre as nombre_remitente 
                 FROM Chat c 
                 JOIN Usuarios u ON c.id_remitente = u.id_usuario 
                 WHERE (c.id_remitente = " . $_SESSION['id_usuario'] . " AND c.id_destinatario = '$id_otro_usuario')
                    OR (c.id_remitente = '$id_otro_usuario' AND c.id_destinatario = " . $_SESSION['id_usuario'] . ")
                 ORDER BY c.fecha_envio ASC";
$resultado_mensajes = mysqli_query($conexion, $sql_mensajes)
    or die("Error al obtener los mensajes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Chat con <?php echo htmlspecialchars($otro_usuario['nombre']); ?></title>
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

        .chat-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .chat-header {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            color: #ffffff;
            margin-bottom: 20px;
            text-align: center;
        }

        .chat-header h2 {
            color: #ae8b4f;
            margin: 0;
            font-size: 24px;
        }

        .mensajes-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            height: 500px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .mensaje {
            margin-bottom: 15px;
            max-width: 70%;
        }

        .mensaje-propio {
            margin-left: auto;
            text-align: right;
        }

        .mensaje-otro {
            margin-right: auto;
        }

        .mensaje-contenido {
            background-color: #ae8b4f;
            color: #000000;
            padding: 10px 15px;
            border-radius: 15px;
            display: inline-block;
            font-size: 16px;
        }

        .mensaje-fecha {
            color: #ae8b4f;
            font-size: 12px;
            margin-top: 5px;
        }

        .mensaje-nombre {
            color: #ae8b4f;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container {
            width: 100%;
            display: flex;
            gap: 10px;
        }

        .mensaje-input {
            flex-grow: 1;
            padding: 10px;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .enviar-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .enviar-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .volver-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .volver-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
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
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexempresa.php"><img src="../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='../empresa/locales_empresa/locales/alquilarlocal.php'">Locales</button>
        </div>
        <button onclick="location.href='../empresa/empleo_empresa/empleo/empleo.php'">Empleo</button>
        <button onclick="location.href='../empresa/banca_empresa/banca/banca.php'">Banca</button>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='mensajempresa.php'>Mensajes</a>
        <a href='editarperfilempresa.php'>Editar Perfil</a>
        <a href='logout.php'>Cerrar Sesión</a>
    </div>

    <div class="chat-container">
        <div class="chat-header">
            <h2><?php echo htmlspecialchars($otro_usuario['nombre']); ?></h2>
        </div>

        <div class="mensajes-container">
            <?php
            while ($mensaje = mysqli_fetch_assoc($resultado_mensajes)) {
                $es_propio = $mensaje['id_remitente'] == $_SESSION['id_usuario'];
                $clase_mensaje = $es_propio ? 'mensaje-propio' : 'mensaje-otro';
                
                echo "<div class='mensaje $clase_mensaje'>";
                if (!$es_propio) {
                    echo "<div class='mensaje-nombre'>" . htmlspecialchars($mensaje['nombre_remitente']) . "</div>";
                }
                echo "<div class='mensaje-contenido'>" . htmlspecialchars($mensaje['mensaje']) . "</div>";
                echo "<div class='mensaje-fecha'>" . date('d/m/Y H:i', strtotime($mensaje['fecha_envio'])) . "</div>";
                echo "</div>";
            }
            ?>
        </div>

        <form action="chatempresa.php?id_usuario=<?php echo $id_otro_usuario; ?>" method="POST" class="form-container">
            <input type="text" name="mensaje" class="mensaje-input" placeholder="Escribe tu mensaje..." required>
            <button type="submit" class="enviar-button">Enviar</button>
        </form>

        <a href="mensajempresa.php" class="volver-button">Volver a Mensajes</a>
    </div>

    <script>
        // Hacer scroll al último mensaje
        window.onload = function() {
            var mensajesContainer = document.querySelector('.mensajes-container');
            mensajesContainer.scrollTop = mensajesContainer.scrollHeight;
        }
    </script>
</body>
</html>
<?php
mysqli_close($conexion);
?>
