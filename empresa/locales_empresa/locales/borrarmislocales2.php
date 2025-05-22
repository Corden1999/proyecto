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

// Obtener el ID del local a borrar
$id_local = isset($_POST['id_local']) ? $_POST['id_local'] : null;

if ($id_local) {
    // Verificar que el local pertenece al usuario actual
    $consulta = "SELECT * FROM Locales WHERE id_local = '$id_local' AND id_usuario = " . $_SESSION['id_usuario'];
    $resultado = mysqli_query($conexion, $consulta);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Obtener la información de la foto antes de borrar
        $local = mysqli_fetch_assoc($resultado);
        $foto = $local['foto'];
        
        // Borrar el local de la base de datos
        $sql = "DELETE FROM Locales WHERE id_local = '$id_local' AND id_usuario = " . $_SESSION['id_usuario'];
        
        if (mysqli_query($conexion, $sql)) {
            // Si se borró correctamente, intentar borrar la foto si existe
            if ($foto && file_exists("../../../" . str_replace('../../', '', $foto))) {
                unlink("../../../" . str_replace('../../', '', $foto));
            }
            header("Location: mislocales.php?mensaje=Local eliminado correctamente");
        } else {
            header("Location: mislocales.php?error=Error al eliminar el local");
        }
    } else {
        header("Location: mislocales.php?error=No tienes permiso para eliminar este local");
    }
} else {
    header("Location: mislocales.php?error=ID de local no válido");
}

mysqli_close($conexion);
?>  