
<?php

//Conexion a base de datos
include('conexion.php');
$user="root";
$server="localhost";
$db="HistoricoESP32";
$pass=conexion();

$con=mysqli_connect($server,$user,$pass,$db);

date_default_timezone_set("Europe/Madrid");

if($_POST['usuario'] == '' or $_POST['password'] == '' or $_POST['azucar'] == ''){
    $response["error"]=true;
    $response["mensaje"]="Por favor rellene todos los campos.";
}else{
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $azucar = $_POST['azucar'];

    $sql = "SELECT * FROM usuarios WHERE usuario ='".$usuario."'" ;
    $mysql= mysqli_query($con,$sql);
    $comprobar_user = 0;
    while($result = mysqli_fetch_array($mysql)) {     //Recorremos la tabla en  busca del usuario
        if(password_verify($_POST['password'],$result['password'])) { //Si lo encuentra es que ya esta registrado
            $comprobar_user = 1;
        }
    }

   if($comprobar_user == 1){
        $fecha = date('d-m-Y H:i:s');
        $queryInsert = "INSERT INTO MedidasAzucar (usuario, fecha, azucar) VALUES ('$usuario', '$fecha', '$azucar')";
        if (!mysqli_query($con, $queryInsert)) {
                $response["error"]=true;
                $response["mensaje"]="Insercion incorrecta";

       }else{
                $response["error"]=false;
                $response["mensaje"]="Medida de azúcar añadido correctamente.";
       }

   }else if($comprobar_user == 0){
        $response["error"]=true;
        $response["mensaje"]="El usuario o la contraseña son incorrectos";
   }

}

mysqli_close($con);
echo json_encode($response);
?>