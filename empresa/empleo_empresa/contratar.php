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
$id_empleo = $_POST['id_empleo'];
$id_candidato = $_POST['id_candidato'];

// Obtener información de la oferta
$consulta_oferta = "SELECT * FROM Empleos WHERE id_empleo = '$id_empleo'";
$resultado_oferta = mysqli_query($conexion, $consulta_oferta);
$oferta = mysqli_fetch_array($resultado_oferta);

// Obtener información del candidato
$consulta_candidato = "SELECT nombre FROM Usuarios WHERE id_usuario = '$id_candidato'";
$resultado_candidato = mysqli_query($conexion, $consulta_candidato);
$candidato = mysqli_fetch_array($resultado_candidato);

// Insertar en la tabla Empleados
$sql = "INSERT INTO Empleados (id_usuario, id_usuario_contratante, cargo, salario) 
        VALUES ('$id_candidato', '" . $_SESSION['id_usuario'] . "', '" . $oferta['titulo'] . "', '" . $oferta['salario'] . "')";

if (mysqli_query($conexion, $sql)) {
    // Eliminar todas las inscripciones relacionadas con esta oferta
    $sql_eliminar_inscripciones = "DELETE FROM InscripcionesEmpleo WHERE id_empleo = '$id_empleo'";
    mysqli_query($conexion, $sql_eliminar_inscripciones);
    
    // Eliminar la oferta de empleo
    $sql_eliminar = "DELETE FROM Empleos WHERE id_empleo = '$id_empleo'";
    mysqli_query($conexion, $sql_eliminar);
    
    echo "<script>
        alert('Empleado contratado correctamente');
        window.location.href='misempleados.php';
    </script>";
} else {
    echo "<script>
        alert('Error al contratar: " . mysqli_error($conexion) . "');
        window.location.href='vercandidatos.php';
    </script>";
}

mysqli_close($conexion);
?> 