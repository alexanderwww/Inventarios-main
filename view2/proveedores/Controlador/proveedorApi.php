<?php

session_start();


include "./proveedorCtrl.php";
// include "../../../includes/autocarga.inc.php";

// include "provedoresCtrl.php";
header('Content-Type: application/json');
$recepcion = json_decode(file_get_contents("php://input"), true);
if (!empty($recepcion)) {

    $post = json_decode(file_get_contents("php://input"), true);

    $Accion = $post['Accion'];

} else {

    $Accion = $_POST['Accion'];

}
$obj = new provedoresCtrl();
if (!empty($Accion)){
    switch ($Accion) {
        case 'getProvedores':



            $resp=$obj->getProveedores('proveedoresCT');

            



            if($resp['Estado']){

                echo  json_encode(array("success" => true, "Data" => $resp['Data']));

            }

            else{

                echo json_encode(array("success" => false, "Data" => "Error controlador"));

            }



            break;



        case 'getSelect':


                // print_r('prueba');
                // die;
                $resp=$obj->getOneValue($post['data']['nameTabla'],$post['data']['datos'],$post['data']['idBuscar']);



                if($resp['Estado']){

                    echo  json_encode(array("success" => true, "Data" => $resp['Data']));

                }

                else{

                    echo json_encode(array("success" => false, "Data" => "Error controlador"));

                }



                break;



        case 'altaProvedor':

            $resp=$obj->altaProvedor($post['data']);



            if ($resp) {



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

        case 'UpdateProvedor':



            $resp=$obj->UpdateProvedor($post['Id'],$post['data']);



            if($resp == true){

                $data= array("success" => true, "message" => "Se ha modificado Registro");

                echo json_encode($data);



            }else{



                $data= array("success" => false, "message" => "No se ha Encontrado Registro");

                echo json_encode($data);

            }

            break;



        case 'getDataProvedor':



            $resp = $obj->GetDataIDEdit("proveedores", $post['Id']);



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




    }

}

