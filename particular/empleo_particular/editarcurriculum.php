<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];
$id_usuario = $_SESSION['id_usuario'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID del currículum
$id_curriculum = isset($_POST['id_curriculum']) ? $_POST['id_curriculum'] : null;

if (!$id_curriculum) {
    header("Location: micurriculum.php");
    exit();
}

// Obtener los datos del currículum
$instruccion = "SELECT * FROM Curriculum WHERE id_curriculum = '" . mysqli_real_escape_string($conexion, $id_curriculum) . "' AND id_usuario = '" . mysqli_real_escape_string($conexion, $id_usuario) . "'";
$consulta = mysqli_query($conexion, $instruccion)
    or die("Fallo en la consulta: " . mysqli_error($conexion));

if (mysqli_num_rows($consulta) === 0) {
    header("Location: micurriculum.php");
    exit();
}

$curriculum = mysqli_fetch_array($consulta);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Editar Currículum</title>
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

        .curriculum-container {
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

        .curriculum-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            color: #ae8b4f;
            font-size: 16px;
            font-weight: bold;
        }

        .form-group input,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .submit-button {
            background-color: #28a745;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            align-self: center;
        }

        .submit-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .cancelar-button {
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            align-self: center;
        }

        .cancelar-button:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        .buttons-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
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

    <div class="curriculum-container">
        <div class="curriculum-card">
            <div class="curriculum-titulo">Editar Currículum</div>
            <form action="editarcurriculum2.php" method="POST" class="curriculum-form">
                <input type="hidden" name="id_curriculum" value="<?php echo $curriculum['id_curriculum']; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($curriculum['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($curriculum['email']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($curriculum['telefono']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($curriculum['direccion']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="codigo_postal">Código Postal</label>
                    <input type="text" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($curriculum['codigo_postal']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="experiencia">Experiencia Laboral</label>
                    <textarea id="experiencia" name="experiencia" required><?php echo htmlspecialchars($curriculum['experiencia']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="formacion">Formación</label>
                    <textarea id="formacion" name="formacion" required><?php echo htmlspecialchars($curriculum['formacion']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="habilidades">Habilidades</label>
                    <textarea id="habilidades" name="habilidades" required><?php echo htmlspecialchars($curriculum['habilidades']); ?></textarea>
                </div>
                
                <div class="buttons-container">
                    <button type="submit" class="submit-button">Guardar Cambios</button>
                    <button type="button" class="cancelar-button" onclick="location.href='micurriculum.php'">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>