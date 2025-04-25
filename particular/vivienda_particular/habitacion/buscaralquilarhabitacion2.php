<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Buscar Mis Pisos</title>
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

        .pisos-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .piso-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            width: calc(33.33% - 20px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .piso-imagen {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .piso-titulo {
            color: #ae8b4f;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .piso-info {
            color: #ffffff;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .piso-precio {
            color: #ae8b4f;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }

        .piso-tipo {
            color: #ffffff;
            font-size: 14px;
            font-weight: normal;
        }

        .no-pisos {
            color: #ffffff;
            font-size: 16px;
            text-align: center;
            margin: 20px;
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
        <a href='../../../sesiones/editarperfil.php'>Editar Perfil</a>
        <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>";

     // Conectar con el servidor de base de datos
     $conexion = mysqli_connect ("localhost", "root", "rootroot","proyecto")
     or die ("No se puede conectar con el servidor");   

     $direccion = $_POST['direccion'];
     $localidad = $_POST['localidad'];
     $provincia = $_POST['provincia'];
     $precio = $_POST['precio'];
     

     $sql = "SELECT * FROM habitaciones WHERE (direccion = '$direccion' OR localidad = '$localidad' OR provincia = '$provincia' OR precio = '$precio') AND disponible = 'si'";
     $resultado = mysqli_query($conexion, $sql)
        or die ("Error al ejecutar la consulta");

        $nfilas = mysqli_num_rows($resultado);
        if ($nfilas > 0) {
            echo "<div class='pisos-container'>";
            for ($i=0; $i<$nfilas; $i++) {
                $fila = mysqli_fetch_array($resultado);
                echo "<div class='piso-card'>";
                echo "<img src='../../../" . str_replace('../../', '', $fila['foto']) . "' alt='Foto del piso' class='piso-imagen'>";
                echo "<div class='piso-titulo'>" . $fila['direccion'] . "</div>";
                echo "<div class='piso-info'>" . $fila['localidad'] . ", " . $fila['provincia'] . "</div>";
                echo "<div class='piso-info'>Código Postal: " . $fila['codigo_postal'] . "</div>";
                echo "<div class='piso-info'>" . $fila['descripcion'] . "</div>";
                echo "<div class='piso-precio'>" . $fila['precio'] . "€</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-pisos'>No hay habitaciones que coincidan con los criterios de búsqueda</div>";
        }
        
  
     
    mysqli_close($conexion);
    ?>

    
</body>
</html>