<?php
session_start();

use LDAP\Result;

// include "../../../includes/autocarga.inc.php";
include "usuariosCtrl.php";
header('Content-Type: application/json');


$recepcion = json_decode(file_get_contents("php://input"), true);



if (!empty($recepcion)) {
    $post = json_decode(file_get_contents("php://input"), true);
    $Accion = $post['Accion'];
} else {
    $Accion = $_POST['Accion'];
}


$Obj = new usuariosCtrl();



if (!empty($Accion)){

    switch ($Accion) {

        case 'getUsuarios':

            $resp=$Obj->getAllData('cuentasUSUARIOS');

            if($resp['Estado']){
                echo  json_encode(array("success" => true, "Data" => $resp['Data']));
            }
            else{
                echo json_encode(array("success" => false, "Data" => "Error controlador"));
            }

            break;

        case 'getRoles':
            $arrayRoles=$Obj->getRoles();
            $array= json_encode($arrayRoles,JSON_PRETTY_PRINT);

            if($arrayRoles['Estado'] == true){

                $data= array("success" => true, "Data" => $arrayRoles['Rol']);
                echo json_encode($data);
            }else{
    
                $data= array("success" => false, "Data" => "No se ha modificado Registro");
                echo json_encode($data);
            }


            break;
        
        case 'altaUsuario':
            $arrayUser=$Obj->altaUser($post['data']);
            if ($arrayUser === true) {

                echo json_encode(array(

                    "success" => true,

                    "message" => "Guardado de forma correcta.",

                ), JSON_PRETTY_PRINT);

            }
            else {

                echo json_encode(array(

                    "success" => false,

                    "message" => "Error al guardar"

                ), JSON_PRETTY_PRINT);

            }

            break;

        
        case 'eliminarUserStatus':
            // Solo pone al usuario en status 0 
            $delres=$Obj->cambiarStatusUser($post['Id'],$post['data']);

            if ($delres == true) {
                $result = array('success'=>true,'message'=>'El usuario se ha eliminado con éxito');
                echo json_encode($result);
            }else {
                $result = array('success'=>false,'message'=>'No se eliminó el usuario');
                echo json_encode($result);
            }

            break;

        case 'getDataUsuario':

            $resp = $Obj->GetDataIDEdit("user_accounts", $post['Id']);


            if ($resp['Estado'] == true) {

                echo json_encode(array(

                    "success" => true,

                    "message" => $resp['Data'],

                ), JSON_PRETTY_PRINT);

            } else {

                echo json_encode(array(

                    "success" => false,

                    "message" => "error al devolver los datos"

                ), JSON_PRETTY_PRINT);

            }

            break;

        case 'updateUsuario':
            $resp=$Obj->updateUsuario($post['Id'],$post['data']);
            // echo json_encode($post['data']);
            // break;
            if($resp == true){
                $data= array("success" => true, "message" => "Se ha modificado Registro");
                echo json_encode($data);

            }else{
    
                $data= array("success" => false, "message" => "No se ha Encontrado Registro");
                echo json_encode($data);
            }
            break;
        case 'getUnidades':

            $resp=$Obj->getAllData('unidadesSTATUS');

            if($resp['Estado']){
                echo  json_encode(array("success" => true, "Data" => $resp['Data']));
            }
            else{
                echo json_encode(array("success" => false, "Data" => "Error controlador"));
            }

            break;

        case 'updateUnidades':

            $resp=$Obj->updateAsignarUnidades($post['Id'],$post['Equipos']);
    
            if($resp){
                echo  json_encode(array("success" => true, "Data" => $resp['Data']));
            }
            else{
                echo json_encode(array("success" => false, "Data" => "Error controlador"));
            }
    
            break;

        case 'getUnidadesUser':

            $resp = $Obj->GetDataIDEdit("cuentasUSUARIOS", $post['Id']);

            if ($resp['Estado'] == true) {

                echo json_encode(array(

                    "success" => true,

                    "message" => $resp['Data'],

                ), JSON_PRETTY_PRINT);

            } else {

                echo json_encode(array(

                    "success" => false,

                    "message" => "error al devolver los datos"

                ), JSON_PRETTY_PRINT);

            }
            break;

        
        case 'restablecimiento':
            $rest = $Obj->restablecimiento($post['Id']);
            if ($rest==true){
                $result = array('success'=>true,'message'=>'Restablecimiento de autentificación de 2 pasos');
                echo json_encode($result);
            }else {
                $result = array('success'=>false,'message'=>'No se ejecutó el restablecimiento de autentificación de 2 pasos');
                echo json_encode($result);
            }

            break;

        case 'eliminarFoto':

            $nombre=$post['nameFoto'];
            if(!unlink("../../../requerimientos/imgGeneral/usuarios/".$nombre)){
                echo 'No se elimino';
            }else{
                echo 'Se elimino correctamente la img: '.$nombre;
            }

            break;
        case 'getSelect':
            // print_r($post);
            $resp=$Obj->getOneValue($post['data']['nameTabla'],$post['data']['datos']);



            if($resp['Estado']){

                echo  json_encode(array("success" => true, "Data" => $resp['Data']));

            }

            else{

                echo json_encode(array("success" => false, "Data" => "Error controlador"));

            }

            break;

    }
}


// 999