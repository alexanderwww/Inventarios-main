<?php
session_start(); 

include "loginCtrl.php";

header('Content-Type: application/json');


$datos = json_decode(file_get_contents("php://input"), true);


if (!empty($datos)) {
    $post = json_decode(file_get_contents("php://input"), true);
    $Accion = $post['Accion'];
} else {
    $Accion = $_POST['Accion'];
}


$Obj = new loginCtrl();



if (!empty($Accion)){

    switch ($Accion) {
      
            case 'dataLogin':
                if(isset($post)){
                $user = $post['data']['usuario'];
                $pass = $post['data']['password'];
                $resp = $Obj->dataLogin($user, $pass);
    
                if ($resp['success'] === true) {
    
                    echo json_encode(array("success" => true, "message" => $resp['message']));

                } else {
                    $data= array("success" => false, "message" => $resp['message']);
                    echo json_encode($data);
    
                }
            }else{
                $data= array("success" => false, "message" => "No se recibio usuario o contraseña");
                echo json_encode($data);    
            }
    
            break;
         
    }
}
?>
