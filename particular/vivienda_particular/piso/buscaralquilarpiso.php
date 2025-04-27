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
                <button onclick="location.href='pisosalquilados.php'">pisos alquilados</button>
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
    ?>

    <div class="form-container">
        <h2>Buscar pisos en alquiler</h2>
        <form action="buscaralquilarpiso2.php" method="POST">
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">
            </div>

            <div class="form-group">
                <label for="localidad">Localidad:</label>
                <input type="text" id="localidad" name="localidad">
            </div>

            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <input type="text" id="provincia" name="provincia">
            </div>

            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01">
            </div>

            <div class="form-group">
                <input type="submit" value="Buscar">
            </div>
        </form>
    </div>

</body>
</html>