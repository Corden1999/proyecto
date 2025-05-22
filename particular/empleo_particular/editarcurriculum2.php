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

// Recoger datos del formulario
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conexion, $_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? mysqli_real_escape_string($conexion, $_POST['direccion']) : '';
$codigo_postal = isset($_POST['codigo_postal']) ? mysqli_real_escape_string($conexion, $_POST['codigo_postal']) : '';
$experiencia = isset($_POST['experiencia']) ? mysqli_real_escape_string($conexion, $_POST['experiencia']) : '';
$formacion = isset($_POST['formacion']) ? mysqli_real_escape_string($conexion, $_POST['formacion']) : '';
$habilidades = isset($_POST['habilidades']) ? mysqli_real_escape_string($conexion, $_POST['habilidades']) : '';

// Actualizar el currículum
$sql = "UPDATE Curriculum SET nombre='$nombre', email='$email', telefono='$telefono', direccion='$direccion', codigo_postal='$codigo_postal', experiencia='$experiencia', formacion='$formacion', habilidades='$habilidades' WHERE id_usuario='$id_usuario'";

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Editar Curriculum</title>
    <style>
        body {
            background-color: #000000;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', Arial, sans-serif;
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
    <div class="result-container">
        <?php
        if (mysqli_query($conexion, $sql)) {
            echo "<div class='success-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Currículum actualizado con éxito</div>";
        } else {
            echo "<div class='error-text' style='color: #ae8b4f; font-size: 24px; margin-bottom: 20px; text-align: center;'>Error al actualizar: " . mysqli_error($conexion) . "</div>";
        }
        ?>
        <a href='micurriculum.php'>Volver a mi currículum</a>
    </div>
<?php mysqli_close($conexion); ?>
</body>
</html>