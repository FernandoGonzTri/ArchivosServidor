<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();


$con=mysqli_connect($server,$user,$pass,$db);

if($_POST['usuario'] == '' or $_POST['password'] == '' or $_POST['name_pastilla'] == '' or $_POST['desayuno'] == ''  or $_POST['almuerzo'] == '' or $_POST['merienda'] == '' or $_POST['cena'] == ''){
    $response["error"]=true;
    $response["mensaje"]="Por favor rellene todos los campos.";
}else{
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $name_pastilla = $_POST['name_pastilla'];
    $desayuno = $_POST['desayuno'];
    $almuerzo = $_POST['almuerzo'];
    $merienda = $_POST['merienda'];
    $cena = $_POST['cena'];

    $sql = "SELECT * FROM usuarios WHERE usuario ='".$usuario."'" ;
    $mysql= mysqli_query($con,$sql);
    $comprobar_user = 0;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del usuario
        if(password_verify($password,$result['password'])) { //Si lo encuentra es que ya esta registrado
            $comprobar_user = 1;
        }
    }
    $sql = "select * from pastillas WHERE usuario ='".$usuario."'";
    $mysql = mysqli_query($con, $sql);
    $comprobar_pastillas = 1;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del pastillero
        if($result["name_pastilla"] == $_POST['name_pastilla']){
                $comprobar_pastillas = 0;
        }
    }

   if($comprobar_pastillas == 1 && $comprobar_user == 1){
        $usuario = $_POST['usuario'];
        $name_pastilla = $_POST['name_pastilla'];
        $desayuno = $_POST['desayuno'];
        $almuerzo = $_POST['almuerzo'];
        $merienda = $_POST['merienda'];
        $cena = $_POST['cena'];

        $queryInsert = "INSERT INTO pastillas (usuario, name_pastilla, desayuno, almuerzo, merienda, cena) VALUES ('$usuario', '$name_pastilla', '$desayuno', '$almuerzo', '$merienda', '$cena')";
        if (!mysqli_query($con, $queryInsert)) {
                $response["error"]=true;
                $response["mensaje"]="Insercion incorrecta";

       }else{
                $response["error"]=false;
                $response["mensaje"]="Medicamento añadido correctamente.";
       }

   }else if($comprobar_user == 0){
        $response["error"]=true;
        $response["mensaje"]="El usuario o la contraseña son incorrectos";
   }else if($comprobar_pastillas == 0){
        $response["error"]=true;
        $response["mensaje"]="Este medicamente ya existe";
   }

}

mysqli_close($con);
echo json_encode($response);
?>
