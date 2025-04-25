<?php
    session_start();
	// Connection info. file
	include 'conn.php';	
	
	$conn = mysqli_connect("localhost", "root", "rootroot", "proyecto");

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$email = $_POST['email']; 
	$password = $_POST['password'];
	
	$result = mysqli_query($conn, "SELECT id_usuario, email, contraseña, nombre, tipo_usuario FROM Usuarios WHERE email = '$email'");
	
	$row = mysqli_fetch_assoc($result);
	
	// Variable $hash hold the password hash on database
	$hash = $row['contraseña'];
	
	
	if (password_verify($password, $hash)) {	
		$_SESSION['loggedin'] = true;
		$_SESSION['id_usuario'] = $row['id_usuario'];
		$_SESSION['name'] = $row['nombre'];
        $_SESSION['tipo'] = $row['tipo_usuario'];
		$_SESSION['start'] = time();
		$_SESSION['expire'] = $_SESSION['start'] + (30 * 60); // 30 minutos de sesión
	

      if ($_SESSION['tipo'] == 'empresa') {
        header("Location: ../empresa/indexempresa.php");
      } else if ($_SESSION['tipo'] == 'particular') {
        header("Location: ../particular/indexparticular.php");           
      }
	
	} else {
		echo "<div class='center-container'>
                <h3>¡Email o Contraseña incorrectos!</h3>
                <a href='iniciosesion.html' class='login-button'>Intentar de nuevo</a>
              </div>";			
	}	
	mysqli_close($conn);
?>