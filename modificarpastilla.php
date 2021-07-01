<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();

$con=mysqli_connect($server,$user,$pass,$db);

if($_POST['usuario'] == '' or $_POST['password'] == '' or $_POST['name_pastilla'] == '' or $_POST['desayuno'] == '' or $_POST['almuerzo'] == '' or $_POST['merienda'] == '' or $_POST['cena'] == ''){
    $response["error"]=true;
    $response["mensaje"]="Por favor rellene todos los campos.";
}else{
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $sql = "select * from usuarios where usuario='".$usuario."'";
    $mysql = mysqli_query($con, $sql);
    $comprobar_user = 0;
    while($result = mysqli_fetch_array($mysql)){     //Recorremos la tabla en  busca del usuario
        if(password_verify($password,$result['password'])){ //Si lo encuentra es que existe
            $comprobar_user = 1;
        }
    }
    if($comprobar_user == 1){

        $name_pastilla = $_POST['name_pastilla'];
        $desayuno = $_POST['desayuno'];
        $almuerzo = $_POST['almuerzo'];
        $merienda = $_POST['merienda'];
        $cena = $_POST['cena'];
        $queryUpdate="UPDATE pastillas SET desayuno='$desayuno', almuerzo='$almuerzo', merienda='$merienda', cena='$cena' WHERE name_pastilla='$name_pastilla' AND usuario='$usuario'";
        if(!mysqli_query($con,$queryUpdate)){
                $response["error"]=true;
                $response["mensaje"]="Modificación incorrecta";
        }else{
                $response["error"]=false;
                $response["mensaje"]="Modificación correcta";

        }
    }else if($comprobar_user == 0){
        $response["error"]=true;
        $response["mensaje"]="El usuario o la contraseña son incorrectos";
    }
}

mysqli_close($con);
echo json_encode($response);
