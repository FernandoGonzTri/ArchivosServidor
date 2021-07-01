<?php
    //Mostrar errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Conexion a base de datos
    include('conexion.php');
    $user="root";
    $server="localhost";
    $db="HistoricoESP32";
    $pass=conexion();
    $tabla="MedidasTension";

    $con=mysqli_connect($server,$user,$pass,$db);

    // Si se recibe Datos con el Método GET, los procesamos
    if (isset($_GET['usuario'])){
        $user = $_GET['usuario'];
        $query = "SELECT fecha, tension_min, tension_max, puls FROM $tabla WHERE usuario='$user' ORDER BY id DESC";
        $result = mysqli_query($con, $query);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="15">
    <title>Consulta tomas</title>

</head>
    <style>
        table {
          table-layout: fixed;
          width: 100%;
          border-collapse: collapse;
          border: 3px solid purple;
        }

        caption {
          font-family: 'Rock Salt', cursive;
          padding: 20px;
          font-size: 25px;
          font-style: italic;
          color: #666;
          text-align: center;
          letter-spacing: 1px;
        }

        tbody tr:nth-child(odd) {
          text-align: center;
          background-color: #7C9CF3;
        }

        tbody tr:nth-child(even) {
          text-align: center;
           background-color: #D7E1FC;
        }
        table tr:nth-child(1){
          text-align: center;
          color: white;
          background-color: #3960C8;
        }

        table {
          background-color: #3960C8;
        }

        th, td {
          padding: 20px;
        }
    </style>
<body>
    <section>
        <table>
        <caption>Consulta de la tensión</caption>
            <tr>
                <td>Fecha</td>
                <td>Tensión minima</td>
                <td>Tensión máxima</td>
                <td>Pulsaciones</td>
             <tr>

         <?php
             if(isset($_GET['usuario'])){
                 while($row = mysqli_fetch_array($result)){
                         printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $row["fecha"],$row["tension_min"],$row["tension_max"],$row["puls"]);
                 }

                mysqli_free_result($result);
                mysqli_close($con);
            }

         ?>

        </table>
    </section>
</body>
</html>


