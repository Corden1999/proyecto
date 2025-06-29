<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener los chats del usuario
$sql = "SELECT DISTINCT 
    CASE 
        WHEN c.id_remitente = " . $_SESSION['id_usuario'] . " THEN c.id_destinatario
        ELSE c.id_remitente
    END as id_otro_usuario,
    u.nombre as nombre_otro_usuario,
    (
        SELECT mensaje 
        FROM Chat 
        WHERE (id_remitente = " . $_SESSION['id_usuario'] . " AND id_destinatario = id_otro_usuario)
        OR (id_remitente = id_otro_usuario AND id_destinatario = " . $_SESSION['id_usuario'] . ")
        ORDER BY fecha_envio DESC 
        LIMIT 1
    ) as ultimo_mensaje,
    (
        SELECT fecha_envio 
        FROM Chat 
        WHERE (id_remitente = " . $_SESSION['id_usuario'] . " AND id_destinatario = id_otro_usuario)
        OR (id_remitente = id_otro_usuario AND id_destinatario = " . $_SESSION['id_usuario'] . ")
        ORDER BY fecha_envio DESC 
        LIMIT 1
    ) as fecha_ultimo_mensaje
FROM Chat c
JOIN Usuarios u ON u.id_usuario = CASE 
    WHEN c.id_remitente = " . $_SESSION['id_usuario'] . " THEN c.id_destinatario
    ELSE c.id_remitente
END
WHERE c.id_remitente = " . $_SESSION['id_usuario'] . " 
OR c.id_destinatario = " . $_SESSION['id_usuario'] . "
ORDER BY fecha_ultimo_mensaje DESC";

$resultado = mysqli_query($conexion, $sql)
    or die("Error al ejecutar la consulta");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Mensajes</title>
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

        .chats-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            padding: 40px 20px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .chat-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            color: #ffffff;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .chat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .chat-nombre {
            color: #ae8b4f;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .chat-ultimo-mensaje {
            color: #ffffff;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .chat-fecha {
            color: #ae8b4f;
            font-size: 14px;
            text-align: right;
        }

        .no-chats {
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
        <button onclick="location.href='../empresa/empleo_empresa/publicaroferta.php'">Empleo</button>
        <button onclick="location.href='../empresa/banca_empresa/bancaindex.php'">Banca</button>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='mensajempresa.php'>Mensajes</a>
        <a href='editarperfilempresa.php'>Editar Perfil</a>
        <a href='logout.php'>Cerrar Sesión</a>
    </div>

    <div class="chats-container">
        <?php
        if (mysqli_num_rows($resultado) > 0) {
            while ($chat = mysqli_fetch_array($resultado)) {
                echo "<a href='chatempresa.php?id_usuario=" . $chat['id_otro_usuario'] . "' class='chat-card'>";
                echo "<div class='chat-nombre'>" . htmlspecialchars($chat['nombre_otro_usuario']) . "</div>";
                echo "<div class='chat-ultimo-mensaje'>" . htmlspecialchars($chat['ultimo_mensaje']) . "</div>";
                echo "<div class='chat-fecha'>" . date('d/m/Y H:i', strtotime($chat['fecha_ultimo_mensaje'])) . "</div>";
                echo "</a>";
            }
        } else {
            echo "<div class='no-chats'>No tienes mensajes</div>";
        }
        ?>
        <a href="../empresa/indexempresa.php" class="volver-button">Volver al Inicio</a>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>
