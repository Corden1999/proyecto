<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Panel Empresa</title>
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
        }

        .welcome-container a {
            color: #ae8b4f;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        .welcome-container a:hover {
            color: #ffffff;
        }

        .panel-empresa {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            margin: 30px auto;
            padding: 30px;
            max-width: 800px;
            color: #ffffff;
        }

        .panel-empresa h2 {
            color: #ae8b4f;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .panel-empresa p {
            line-height: 1.6;
            font-size: 16px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../index.html"><img src="../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='locales_empresa/locales/locales.php'">Locales</button>
        </div>
        <div class="dropdown">
            <button onclick="location.href='#empleo'">Empleo</button>
            <div class="dropdown-content">
                <button onclick="location.href='../empleo/publicar.html'">Publicar Oferta</button>
                <button onclick="location.href='../empleo/mis_ofertas.html'">Mis Ofertas</button>
            </div>
        </div>
        <button onclick="location.href='../banca/banca.html'">Banca</button>
    </nav>

    <?php
    $name = $_SESSION['name'];
    echo "<div class='welcome-container'>
        <strong>¡Bienvenido!</strong> $name
        <a href='../sesiones/edit-profile.php'>Editar Perfil</a>
        <a href='../sesiones/logout.php'>Cerrar Sesión</a>
    </div>";
    ?>

    <div class="panel-empresa">
        <h2>Panel de Control Empresarial</h2>
        <p>Bienvenido a tu panel de control empresarial. Desde aquí podrás gestionar tus publicaciones de viviendas y ofertas de empleo. Utiliza el menú superior para acceder a todas las funcionalidades disponibles.</p>
    </div>

</body>
</html>