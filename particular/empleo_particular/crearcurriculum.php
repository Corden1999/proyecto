<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Crear Curriculum</title>
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

        .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            color: #ffffff;
        }

        .form-title {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #ae8b4f;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .form-textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
            min-height: 100px;
            resize: vertical;
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #ffffff;
        }

        .create-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            display: block;
            margin: 20px auto;
            transition: all 0.3s ease;
        }

        .create-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexparticular.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='empleosparticular.php'">Empleos</button>
            <div class="dropdown-content">
                <button onclick="location.href='micurriculum.php'">mi curriculum</button>
                <button onclick="location.href='misofertas.php'">ofertas inscritas</button>
                <button onclick="location.href='buscarofertas.php'">buscar ofertas</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../sesiones/mensajeparticular.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilparticular.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="form-container">
        <h2 class="form-title">Crear Curriculum</h2>
        <form action="crearcurriculum2.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" class="form-input" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-input" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="telefono">Teléfono</label>
                <input type="tel" class="form-input" id="telefono" name="telefono">
            </div>

            <div class="form-group">
                <label class="form-label" for="direccion">Dirección</label>
                <input type="text" class="form-input" id="direccion" name="direccion">
            </div>

            <div class="form-group">
                <label class="form-label" for="codigo_postal">Código Postal</label>
                <input type="text" class="form-input" id="codigo_postal" name="codigo_postal">
            </div>

            <div class="form-group">
                <label class="form-label" for="experiencia">Experiencia Laboral</label>
                <textarea class="form-textarea" id="experiencia" name="experiencia" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="formacion">Formación Académica</label>
                <textarea class="form-textarea" id="formacion" name="formacion" rows="4"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="habilidades">Habilidades</label>
                <textarea class="form-textarea" id="habilidades" name="habilidades" rows="4"></textarea>
            </div>

            <button type="submit" class="create-button">Crear Curriculum</button>
        </form>
    </div>
</body>
</html>
