<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();
$con=mysqli_connect($server,$user,$pass,$db);

if($_POST['usuario'] == '' or $_POST['password'] == ''){
    $response["error"]=true;
    $response["mensaje"]="Por favor rellene todos los campos.";
}else{
    $sql = "select * from usuarios";
    $mysql = mysqli_query($con, $sql);
    $response["error"]=true;
    $response["mensaje"]="Usuario o contraseña incorrecto";
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del usuario
        if(($result['usuario'] == $_POST['usuario']) && (password_verify($_POST['password'],$result['password']))){
            $response["error"] = false;
            $response["mensaje"] = "Inicio de sesión correcto";
        }
    }


}

mysqli_close($con);
echo json_encode($response);
?>