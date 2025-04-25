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
            flex-direction: column;
            align-items: center;
            gap: 30px;
            padding: 40px 20px;
            margin-top: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .piso-card {
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

        .piso-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .piso-imagen {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .piso-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .piso-info {
            margin-bottom: 10px;
            font-size: 16px;
            line-height: 1.5;
        }

        .piso-propietario {
            color: #ae8b4f;
            font-size: 16px;
            margin: 10px 0;
            font-weight: bold;
        }

        .piso-precio {
            color: #ae8b4f;
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
        }

        .piso-tipo {
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

        .alquilar-button {
            background-color: #4CAF50;
            color: #000000;
            border: none;
            padding: 10px 20px;
            border-radius: 15px;
            font-size: 14px;
            margin-top: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .alquilar-button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .no-fondos {
            color: #ff4444;
            font-size: 14px;
            margin-top: 10px;
            font-weight: bold;
        }

        .no-pisos {
            text-align: center;
            color: #ae8b4f;
            font-size: 18px;
            margin-top: 40px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../../indexparticular.php"><img src="../../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='alquilarpiso.php'">Alquilar / comprar piso</button>
            <div class="dropdown-content">
                <button onclick="location.href='comprarpiso.php'">pisos en venta</button>
                <button onclick="location.href='alquilarpiso.php'">pisos en alquiler</button>
                <button onclick="location.href='buscarcomprarpiso.php'">buscar pisos en venta</button>
                <button onclick="location.href='buscaralquilarpiso.php'">buscar pisos en alquiler</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='arrendarpiso.php'">Arrendar / vender piso</button>
            <div class="dropdown-content">
                <button onclick="location.href='mispisos.php'">mis pisos</button>
                <button onclick="location.href='borrarmispisos.php'">borrar mis pisos</button>
                <button onclick="location.href='editarmispisos.php'">editar mis pisos</button>
                <button onclick="location.href='buscarmispisos.php'">buscar mis pisos</button>
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
     $tipo = $_POST['tipo'];

     $sql = "SELECT * FROM pisos WHERE (direccion = '$direccion' OR localidad = '$localidad' OR provincia = '$provincia' OR precio = '$precio') AND tipo = 'alquiler' and disponible = 'si'";
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
                
                // Obtener información del propietario
                $sql_propietario = "SELECT nombre FROM Usuarios WHERE id_usuario = " . $fila['id_usuario'];
                $result_propietario = mysqli_query($conexion, $sql_propietario);
                $propietario = mysqli_fetch_assoc($result_propietario);
                
                echo "<div class='piso-propietario'>Propietario: " . $propietario['nombre'] . "</div>";
                echo "<div class='piso-precio'>" . $fila['precio'] . "€/mes</div>";
                echo "<div class='piso-tipo'>" . ucfirst($fila['tipo']) . "</div>";
                
                // Verificar fondos del usuario
                $sql_cuenta = "SELECT saldo FROM Cuenta WHERE id_usuario = " . $_SESSION['id_usuario'];
                $result_cuenta = mysqli_query($conexion, $sql_cuenta);
                $cuenta = mysqli_fetch_assoc($result_cuenta);
                
                if ($cuenta && $cuenta['saldo'] >= $fila['precio']) {
                    echo "<form action='procesaralquilerpiso.php' method='POST'>";
                    echo "<input type='hidden' name='id_piso' value='" . $fila['id_piso'] . "'>";
                    echo "<input type='hidden' name='precio' value='" . $fila['precio'] . "'>";
                    echo "<button type='submit' class='alquilar-button'>Alquilar Piso</button>";
                    echo "</form>";
                } else {
                    echo "<div class='no-fondos'>No tiene fondos suficientes</div>";
                }
                
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-pisos'>No hay pisos que coincidan con los criterios de búsqueda</div>";
        }
        
  
     
    mysqli_close($conexion);
    ?>

    
</body>
</html>