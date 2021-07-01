<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();

$con=mysqli_connect($server,$user,$pass,$db);

if($_POST['usuario'] == '' or $_POST['password'] == '' or $_POST['repassword'] == '' or $_POST['email'] == '' or $_POST['refPastillero'] == '') {
    $response["error"]=true;
    $response["mensaje"]="Por favor rellene todos los campos.";
}else{
    $sql = "select * from usuarios";
    $mysql = mysqli_query($con, $sql);
    $comprobar_user = 0;
    $comprobarPastilleroRegistrado = 0;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del usuario
        if($result['usuario'] == $_POST['usuario']) { //Si lo encuentra es que ya esta registrado
            $comprobar_user = 1;
        }
        if($result['refPastillero'] == $_POST['refPastillero']){
           $comprobarPastilleroRegistrado = 1;
        }
    }
    $sql = "select * from pastilleros";
    $mysql = mysqli_query($con, $sql);
    $comprobar_pastillero = 0;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del pastillero
        if($result["num_ref"] == $_POST['refPastillero']){
            $comprobar_pastillero = 1;
        }
    }
    if($comprobar_user == 0 && $comprobar_pastillero == 1 && $comprobarPastilleroRegistrado == 0) {
        if($_POST['password'] == $_POST['repassword']) {
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $refPastillero = $_POST['refPastillero'];
            $passHash=password_hash($password, PASSWORD_DEFAULT);
            $queryInsert = "INSERT INTO usuarios (usuario, password, email, refPastillero) VALUES ('$usuario','$passHash','$email','$refPastillero')";
            if (!mysqli_query($con, $queryInsert)) {
                $response["error"]=true;
                $response["mensaje"]="Insercion incorrecta";

            }else{
                $response["error"]=false;
                $response["mensaje"]="Usted se ha registrado correctamente";

                #Se añade un usuario
                $command = "mosquitto_passwd -b /var/www/ESP32/passwd ".$usuario." ".$password ;
                echo exec($command , $out );

                #Se modifica el acl
                $file = fopen("/var/www/ESP32/acl","a");
                fwrite($file,"user ".$usuario.PHP_EOL);
                fwrite($file,"topic readwrite /ESP32/".$usuario."/#".PHP_EOL.PHP_EOL);

                fclose($file);

                echo exec('sudo service mosquitto reload');

            }
        } else {
            $response["error"]=true;
            $response["mensaje"]="Las contraseñas no son iguales, intentelo nuevamente.";
        }
    } else if ($comprobar_user == 1){
       $response["error"]=true;
       $response["mensaje"]="Este usuario ya ha sido registrado anteriormente.";

    } else if ($comprobar_pastillero == 0){
       $response["error"]=true;
       $response["mensaje"]="El número de referencia no ha sido encontrado.";
    }else if ($comprobarPastilleroRegistrado == 1){
       $response["error"]=true;
       $response["mensaje"]="Ese número de referencia ya esta registrado en otro usuario";
    }
}

mysqli_close($con);
echo json_encode($response);
?>

