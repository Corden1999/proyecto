<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Verificar si el usuario existe
$verificar_usuario = "SELECT id_usuario FROM Usuarios WHERE id_usuario = '$id_usuario'";
$resultado_verificacion = mysqli_query($conexion, $verificar_usuario);

if (mysqli_num_rows($resultado_verificacion) == 0) {
    echo "Error: El usuario no existe en la base de datos.<br>";
    echo "ID de usuario: " . $id_usuario . "<br>";
    echo "<br><a href='crearcurriculum.php'>Volver al formulario</a>";
    exit();
}

// Obtener y escapar los datos del formulario
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$telefono = isset($_POST['telefono']) ? mysqli_real_escape_string($conexion, $_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? mysqli_real_escape_string($conexion, $_POST['direccion']) : '';
$codigo_postal = isset($_POST['codigo_postal']) ? mysqli_real_escape_string($conexion, $_POST['codigo_postal']) : '';
$experiencia = isset($_POST['experiencia']) ? mysqli_real_escape_string($conexion, $_POST['experiencia']) : '';
$formacion = isset($_POST['formacion']) ? mysqli_real_escape_string($conexion, $_POST['formacion']) : '';
$habilidades = isset($_POST['habilidades']) ? mysqli_real_escape_string($conexion, $_POST['habilidades']) : '';

// Insertar el nuevo currículum
$instruccion = "INSERT INTO Curriculum (id_usuario, nombre, email, telefono, direccion, codigo_postal, experiencia, formacion, habilidades) 
                VALUES ('$id_usuario', '$nombre', '$email', '$telefono', '$direccion', '$codigo_postal', '$experiencia', '$formacion', '$habilidades')";

// Mostrar la consulta SQL para depuración
echo "Consulta SQL: " . $instruccion . "<br>";

$resultado = mysqli_query($conexion, $instruccion);

if ($resultado) {
    // Redirigir a la página de visualización del currículum
    header("Location: micurriculum.php");
} else {
    // Mostrar mensaje de error detallado
    echo "Error al crear el currículum: " . mysqli_error($conexion) . "<br>";
    echo "Código de error: " . mysqli_errno($conexion) . "<br>";
    echo "ID de usuario: " . $id_usuario . "<br>";
    echo "<br><a href='crearcurriculum.php'>Volver al formulario</a>";
}

// Cerrar conexión
mysqli_close($conexion);
?>
