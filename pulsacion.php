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

    $con=mysqli_connect($server,$user,$pass,$db);

    date_default_timezone_set("Europe/Madrid");
    //Crea un archivo de texto para guardar los datos que envía el ESP32
    //if (!file_exists("./myBD.txt")){
        // si no existe el archivo, lo creamos
        //file_put_contents("./myBD.txt", "");
    //}
    // Si se recibe Datos con el Método POST, los procesamos
    if (isset($_POST['boton']) && isset($_POST['usuario']) && isset($_POST['password'])){
        $user = $_POST['usuario'];
        $password = $_POST['password'];
        $sql = "select * from usuarios where usuario='".$user."'";
        $mysql = mysqli_query($con, $sql);
        $comprobar_user = 0;
        while($result = mysqli_fetch_array($mysql)){     //Recorremos la tabla en  busca del usuario
                if(password_verify($password,$result['password'])){ //Si lo encuentra es que existe
                    $comprobar_user = 1;
                }
        }

        if($comprobar_user == 1){
                $boton = $_POST['boton'];                                         //Se coge de la variable
                $fechaActual = date('d-m-Y H:i:s') . "\r\n";                             //Cogemos la hora
                $sql = "INSERT INTO TomasRealizadas (usuario, fecha, correcto) VALUES ('$user', '$fechaActual', '$boton')";
                if (mysqli_query($con, $sql)) {
                        echo "Pulsacion guardada correctamente";
                } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($con);
                }
        }else{
                echo "Error";
        }

        //$fileSave = file_put_contents("./myBD.txt", $fechaActual);        //Añadir al fichero
    }

    // Leemos los datos del archivo para guardarlos en variables
?>
