<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../sesiones/iniciosesion.html");
    exit();
}

$name = $_SESSION['name'];

// Conectar con el servidor de base de datos
$conexion = mysqli_connect("localhost", "root", "rootroot", "proyecto")
    or die("No se puede conectar con el servidor");

// Obtener el saldo actual
$id_usuario = $_SESSION['id_usuario'];
$consulta_saldo = "SELECT saldo FROM Cuenta WHERE id_usuario = '$id_usuario'";
$resultado_saldo = mysqli_query($conexion, $consulta_saldo)
    or die("Error al obtener el saldo");
$saldo = mysqli_fetch_assoc($resultado_saldo);

// Obtener los últimos movimientos
$consulta_movimientos = "SELECT * FROM Movimientos 
                        WHERE id_usuario = '$id_usuario' 
                        ORDER BY fecha DESC 
                        LIMIT 10";
$resultado_movimientos = mysqli_query($conexion, $consulta_movimientos)
    or die("Error al obtener los movimientos");

// Obtener datos para la gráfica de gastos mensuales
$consulta_gastos = "SELECT 
    MONTH(fecha) as mes,
    SUM(CASE WHEN tipo = 'gasto' THEN cantidad ELSE 0 END) as gastos,
    SUM(CASE WHEN tipo = 'ingreso' THEN cantidad ELSE 0 END) as ingresos
    FROM Movimientos 
    WHERE id_usuario = '$id_usuario' 
    AND fecha >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY YEAR(fecha), MONTH(fecha)
    ORDER BY YEAR(fecha), MONTH(fecha)";
$resultado_gastos = mysqli_query($conexion, $consulta_gastos)
    or die("Error al obtener los datos de gastos");

// Preparar datos para la gráfica
$meses = array();
$gastos = array();
$ingresos = array();

while ($row = mysqli_fetch_assoc($resultado_gastos)) {
    $meses[] = date('M Y', mktime(0, 0, 0, $row['mes'], 1, date('Y')));
    $gastos[] = $row['gastos'];
    $ingresos[] = $row['ingresos'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Junteate - Banca</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .banca-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .saldo-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            color: #ffffff;
            grid-column: 1 / -1;
        }

        .saldo-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .saldo-valor {
            font-size: 48px;
            font-weight: bold;
            color: #ffffff;
        }

        .movimientos-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            color: #ffffff;
        }

        .movimientos-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .movimientos-table {
            width: 100%;
            border-collapse: collapse;
        }

        .movimientos-table th {
            color: #ae8b4f;
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ae8b4f;
        }

        .movimientos-table td {
            padding: 10px;
            border-bottom: 1px solid #ae8b4f;
        }

        .movimientos-table tr:last-child td {
            border-bottom: none;
        }

        .gasto {
            color: #ff4444;
        }

        .ingreso {
            color: #4CAF50;
        }

        .grafica-card {
            background-color: #000000;
            border: 2px solid #ae8b4f;
            border-radius: 15px;
            padding: 30px;
            color: #ffffff;
        }

        .grafica-titulo {
            color: #ae8b4f;
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .grafica-container {
            position: relative;
            height: 300px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="titulo"><a href="../indexparticular.php"><img src="../../img/titulo.png" alt="Junteate Logo"></a></h1>
    </div>
    
    <nav class="menu">
        <button onclick="location.href='bancaindex.php'">Banca</button>
        <button onclick="location.href='calculadorahipoteca.php'">Calculadora de Hipoteca</button>
        <button onclick="location.href='transferir.php'">Transferir</button>
    </nav>

    <div class='welcome-container'>
        <strong>¡Bienvenido! <?php echo $name; ?></strong><br>
        <a href='../../sesiones/mensajempresa.php'>Mensajes</a>
        <a href='../../sesiones/editarperfilempresa.php'>Editar Perfil</a>
        <a href='../../sesiones/logout.php'>Cerrar Sesión</a>
    </div>

    <div class="banca-container">
        <div class="saldo-card">
            <div class="saldo-titulo">Saldo Actual</div>
            <div class="saldo-valor"><?php echo number_format($saldo['saldo'], 2, ',', '.'); ?> €</div>
        </div>

        <div class="movimientos-card">
            <div class="movimientos-titulo">Últimos Movimientos</div>
            <table class="movimientos-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($movimiento = mysqli_fetch_assoc($resultado_movimientos)): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($movimiento['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($movimiento['concepto']); ?></td>
                            <td class="<?php echo $movimiento['tipo']; ?>">
                                <?php echo number_format($movimiento['cantidad'], 2, ',', '.'); ?> €
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="grafica-card">
            <div class="grafica-titulo">Gastos e Ingresos Mensuales</div>
            <div class="grafica-container">
                <canvas id="graficaGastos"></canvas>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('graficaGastos').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($meses); ?>,
                datasets: [{
                    label: 'Gastos',
                    data: <?php echo json_encode($gastos); ?>,
                    backgroundColor: 'rgba(255, 68, 68, 0.5)',
                    borderColor: 'rgba(255, 68, 68, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Ingresos',
                    data: <?php echo json_encode($ingresos); ?>,
                    backgroundColor: 'rgba(76, 175, 80, 0.5)',
                    borderColor: 'rgba(76, 175, 80, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(174, 139, 79, 0.1)'
                        },
                        ticks: {
                            color: '#ffffff'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(174, 139, 79, 0.1)'
                        },
                        ticks: {
                            color: '#ffffff'
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
mysqli_close($conexion);
?> 