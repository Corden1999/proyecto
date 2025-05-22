<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener los datos del local
$id_local = isset($_POST['id_local']) ? $_POST['id_local'] : null;
$direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';
$localidad = isset($_POST['localidad']) ? $_POST['localidad'] : '';
$provincia = isset($_POST['provincia']) ? $_POST['provincia'] : '';
$codigo_postal = isset($_POST['codigo_postal']) ? $_POST['codigo_postal'] : '';
$precio = isset($_POST['precio']) ? $_POST['precio'] : '';
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
$disponible = isset($_POST['disponible']) ? $_POST['disponible'] : '';
$foto = isset($_POST['foto']) ? $_POST['foto'] : '';

if (!$id_local) {
    header("Location: mislocales.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Editar Local</title>
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

        .form-group label {
            display: block;
            color: #ae8b4f;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
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

        .form-group select {
            cursor: pointer;
        }

        .form-group input[type="file"] {
            color: #ffffff;
        }

        .submit-button {
            background-color: #ae8b4f;
            color: #000000;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            width: 100%;
            margin-top: 20px;
        }

        .submit-button:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(174, 139, 79, 0.3);
        }

        .current-image {
            max-width: 300px;
            margin: 10px 0;
            border-radius: 10px;
        }

        .image-preview {
            margin-top: 10px;
            color: #ae8b4f;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../../indexempresa.php"><img src="../../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <div class="dropdown">
            <button onclick="location.href='alquilarlocal.php'">Alquilar / comprar local</button>
            <div class="dropdown-content">
                <button onclick="location.href='comprarlocal.php'">locales en venta</button>
                <button onclick="location.href='alquilarlocal.php'">locales en alquiler</button>
                <button onclick="location.href='buscarcomprarlocal.php'">buscar locales en venta</button>
                <button onclick="location.href='buscaralquilarlocal.php'">buscar locales en alquiler</button>
                <button onclick="location.href='localesalquilados.php'">locales alquilados</button>
            </div>
        </div>
        <div class="dropdown">
            <button onclick="location.href='arrendarlocal.php'">Arrendar / venderlocal</button>
            <div class="dropdown-content">
                <button onclick="location.href='mislocales.php'">mis locales</button>
                <button onclick="location.href='borrarmislocales.php'">borrar mis locales</button>
                <button onclick="location.href='buscarmislocales.php'">buscar mis locales</button>
            </div>
        </div>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="form-container">
        <h2 class="form-title">Editar Local</h2>
        <form action="procesareditarlocal.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_local" value="<?php echo $id_local; ?>">
            
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($direccion); ?>" required>
            </div>

            <div class="form-group">
                <label for="localidad">Localidad:</label>
                <input type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($localidad); ?>" required>
            </div>

            <div class="form-group">
                <label for="provincia">Provincia:</label>
                <input type="text" id="provincia" name="provincia" value="<?php echo htmlspecialchars($provincia); ?>" required>
            </div>

            <div class="form-group">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" value="<?php echo htmlspecialchars($codigo_postal); ?>" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="alquiler" <?php echo $tipo == 'alquiler' ? 'selected' : ''; ?>>Alquiler</option>
                    <option value="venta" <?php echo $tipo == 'venta' ? 'selected' : ''; ?>>Venta</option>
                </select>
            </div>

            <div class="form-group">
                <label for="disponible">Disponibilidad:</label>
                <select id="disponible" name="disponible" required>
                    <option value="si" <?php echo $disponible == 'si' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="no" <?php echo $disponible == 'no' ? 'selected' : ''; ?>>No disponible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto" accept="image/*">
                <input type="hidden" name="foto_actual" value="<?php echo htmlspecialchars($foto); ?>">
                <?php if ($foto): ?>
                    <div class="image-preview">
                        <p>Imagen actual:</p>
                        <img src="../../../<?php echo str_replace('../../', '', $foto); ?>" alt="Foto actual" class="current-image">
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="submit-button">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>
