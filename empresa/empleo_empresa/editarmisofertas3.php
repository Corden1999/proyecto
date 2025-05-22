<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Mis Ofertas de Empleo</title>
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

        .ofertas-container {
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

        .oferta-card {
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

        .oferta-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .oferta-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .oferta-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .oferta-ubicacion {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .oferta-salario {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .oferta-tipo {
            display: block;
            padding: 8px 15px;
            background-color: #ae8b4f;
            color: #000000;
            border-radius: 15px;
            font-size: 14px;
            margin: 15px 0 0;
            font-weight: bold;
            text-align: left;
            width: fit-content;
        }

        .no-ofertas {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }

        .result-container {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: 40px auto;
            color: #ffffff;
            text-align: center;
        }

        .result-container a {
            display: inline-block;
            background-color: #ae8b4f;
            color: #000000;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .result-container a:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
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

    

    <div class="ofertas-container">
    <?php
    $name = $_SESSION['name'];
    echo "<div class='welcome-container'>
        <strong>¡Bienvenido! $name</strong><br>
        <a href='../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>";
    
    $servername = "localhost";
    $username = "root";
    $password = "rootroot";
    $dbname = "proyecto";

    $conn = new mysqli($servername, $username, $password, $dbname)
     or die("Connection failed: " . mysqli_connect_error());

    $id_empleo = $_POST['id_empleo'];
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $provincia = $_POST['provincia'];
    $codigo_postal = $_POST['codigo_postal'];
    $salario = $_POST['salario'];
    $tipo_contrato = $_POST['tipo_contrato'];

    $sql = "UPDATE Empleos SET titulo = '$titulo', descripcion = '$descripcion', direccion = '$direccion', localidad = '$localidad', provincia = '$provincia', codigo_postal = '$codigo_postal', salario = '$salario', tipo_contrato = '$tipo_contrato' WHERE id_empleo = '$id_empleo'";


    if (mysqli_query($conn, $sql)) {
        echo "<div class='result-container'>
                <div class='success-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Oferta actualizada con éxito</div>
                <a href='misofertas.php' style='background-color: #ae8b4f; color: #000000; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;'>Volver a mis ofertas</a>
              </div>";
    } else {
        echo "<div class='result-container'>
                <div class='error-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Error al actualizar: " . mysqli_error($conn) . "</div>
                <a href='misofertas.php' style='background-color: #ae8b4f; color: #000000; padding: 12px 25px; border-radius: 25px; text-decoration: none; font-weight: bold;'>Volver a mis ofertas</a>
              </div>";
    }

    mysqli_close($conn);
    ?>
    </div>
</body>
</html>