<?php
session_start();

header('Content-Type: application/json');

$datos = json_decode(file_get_contents("php://input"), true);

if (isset($datos)) {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

$arrayApi=explode("/",$_SERVER['REQUEST_URI']);  
$dirApi = array_filter($arrayApi)[4];
// echo "<pre>"; print_r($arrayApi); echo "<pre>";
if(count(array_filter($arrayApi))==4){
    switch ($dirApi) {
        case 'bitacora':
            $respuesta=array("success"=>false,"mensaje"=>"Entraste a bitacora");
            echo json_encode($respuesta);
            return;
            break;
        case 'usuarios':
                $usuariosObj = new ControladorUsuarios();
                
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){

                // $respuesta=$usuariosObj->createUsuario($_POST);
                
                // $respuesta=array("success"=>false,"mensaje"=>"Entraste a bitacora");

                echo "<pre>"; print_r($_POST); echo "<pre>";
                // echo json_encode($_POST);
                return;

            }
          
            return;
            break;
        
        default:

            break;
    }

    
}else{
    $respuesta=array("success"=>false,"mensaje"=>"Direccion no encontrada");
    echo json_encode($respuesta);
    return;
}
// if (!empty($Accion)){

//     switch ($Accion) {
     


//     }
// }
?>
