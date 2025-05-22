<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../../sesiones/iniciosesion.html");
    exit();
}

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener los datos del formulario
$id_empleo = isset($_POST['id_empleo']) ? $_POST['id_empleo'] : null;
$id_empresa = isset($_POST['id_empresa']) ? $_POST['id_empresa'] : null;
$id_usuario = $_SESSION['id_usuario'];

if (!$id_empleo || !$id_empresa) {
    header("Location: ofertasinscritas.php");
    exit();
}

// Verificar si ya está inscrito
$consulta_inscripcion = "SELECT * FROM InscripcionesEmpleo 
                        WHERE id_usuario = '$id_usuario' 
                        AND id_empleo = '$id_empleo'";
$resultado_inscripcion = mysqli_query($conexion, $consulta_inscripcion);

if (mysqli_num_rows($resultado_inscripcion) > 0) {
    echo "<script>
            alert('Ya estás inscrito en esta oferta de empleo');
            window.location.href = 'ofertasinscritas.php';
          </script>";
    exit();
}

// Insertar la inscripción
$consulta_insertar = "INSERT INTO InscripcionesEmpleo (id_usuario, id_empleo) 
                     VALUES ('$id_usuario', '$id_empleo')";
$resultado_insertar = mysqli_query($conexion, $consulta_insertar);

if ($resultado_insertar) {
    echo "<script>
            alert('Te has inscrito correctamente en la oferta de empleo');
            window.location.href = 'ofertasinscritas.php';
          </script>";
} else {
    echo "<script>
            alert('Error al inscribirse en la oferta de empleo');
            window.location.href = 'ofertasinscritas.php';
          </script>";
}

mysqli_close($conexion);
?> 