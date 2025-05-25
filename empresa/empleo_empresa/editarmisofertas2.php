<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el ID de la oferta a editar
$id_empleo = isset($_POST['id_empleo']) ? $_POST['id_empleo'] : '';

if (empty($id_empleo)) {
    header("Location: editarmisofertas.php");
    exit();
}

// Obtener los datos de la oferta
$consulta = "SELECT * FROM Empleos WHERE id_empleo = '$id_empleo' AND id_usuario = " . $_SESSION['id_usuario'];
$resultado = mysqli_query($conexion, $consulta)
    or die("Fallo en la consulta");

if (mysqli_num_rows($resultado) == 0) {
    header("Location: editarmisofertas.php");
    exit();
}

$row = mysqli_fetch_array($resultado);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Editar Oferta de Empleo</title>
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
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            margin: 40px auto;
            color: #ffffff;
        }

        .form-container h2 {
            color: #ae8b4f;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #ae8b4f;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ae8b4f;
            border-radius: 5px;
            background-color: #000000;
            color: #ffffff;
            font-size: 16px;
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .form-group input[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
            width: auto;
            margin-top: 20px;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
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

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="form-container">
        <h2>Editar Oferta de Empleo</h2>
        <form action="editarmisofertas3.php" method="POST">
            <input type="hidden" name="id_empleo" value="<?php echo $row['id_empleo']; ?>">
            
            <div class="form-group">
                <label for="titulo">Título del Puesto</label>
                <input type="text" id="titulo" name="titulo" value="<?php echo $row['titulo']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción del Puesto</label>
                <textarea id="descripcion" name="descripcion" required><?php echo $row['descripcion']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo $row['direccion']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="localidad">Localidad</label>
                <input type="text" id="localidad" name="localidad" value="<?php echo $row['localidad']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="provincia">Provincia</label>
                <input type="text" id="provincia" name="provincia" value="<?php echo $row['provincia']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tipo_contrato">Tipo de Contrato</label>
                <select id="tipo_contrato" name="tipo_contrato" required>
                    <option value="">Seleccione un tipo de contrato</option>
                    <option value="Indefinido" <?php if ($row['tipo_contrato'] == 'Indefinido') echo 'selected'; ?>>Indefinido</option>
                    <option value="Temporal" <?php if ($row['tipo_contrato'] == 'Temporal') echo 'selected'; ?>>Temporal</option>
                    <option value="Prácticas" <?php if ($row['tipo_contrato'] == 'Prácticas') echo 'selected'; ?>>Prácticas</option>
                    <option value="Formación" <?php if ($row['tipo_contrato'] == 'Formación') echo 'selected'; ?>>Formación</option>
                    <option value="Autónomo" <?php if ($row['tipo_contrato'] == 'Autónomo') echo 'selected'; ?>>Autónomo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="codigo_postal">Código Postal</label>
                <input type="text" id="codigo_postal" name="codigo_postal" value="<?php echo $row['codigo_postal']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="salario">Salario (€)</label>
                <input type="number" id="salario" name="salario" step="0.01" value="<?php echo $row['salario']; ?>" required>
            </div>
            
            <div class="form-group">
                <input type="submit" value="Actualizar Oferta">
            </div>
        </form>
    </div>
</body>
</html>
<?php
mysqli_close($conexion);
?>