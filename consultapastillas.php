<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();

$con=mysqli_connect($server,$user,$pass,$db);

if($_POST['usuario'] == ""){
    $response["error"]=true;
    $response["mensaje"]="Por favor introduzca su usuario";
}else{
    $usuario=$_POST['usuario'];
    $sql = "select * from usuarios where usuario = '$usuario'";
    $mysql = mysqli_query($con, $sql);
    $comprobar_user = 0;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del usuario
        if($result['usuario'] == $_POST['usuario']) { //Si lo encuentra es que existe
            $comprobar_user = 1;
        }
    }

    if($comprobar_user==1){
        $sql = "select * from pastillas where usuario = '$usuario'";
        $mysql = mysqli_query($con, $sql);
        $response["error"]=false;
        while($result = mysqli_fetch_array($mysql)) {
             $response["mensaje"] = $response["mensaje"] . "/" . $result["name_pastilla"];
        }


    }else{
        $response["error"]=true;
        $response["mensaje"]="El usuario no existe";
    }
}

mysqli_close($con);
echo json_encode($response);
