<?php



session_start();







// include "../../../includes/autocarga.inc.php";



include "clientesCtrl.php";



header('Content-Type: application/json');











$recepcion = json_decode(file_get_contents("php://input"), true);











if (!empty($recepcion)) {



    $post = json_decode(file_get_contents("php://input"), true);



    $Accion = $post['Accion'];



} else {



    $Accion = $_POST['Accion'];



}











$obj = new clientesCtrl();















if (!empty($Accion)){







    switch ($Accion) {







        case 'getClientes':



            



            // $resp=$obj->getClientes('clientesCT','Id,Status,NombreCom,RazonSocial,RFC,RegimenFiscal,Telefono,Ext,CalleCliente,CPCliente,dias_credito,Ejecutiva,ColoniaCliente,CiudadCliente,EstadoCliente,PaisCliente');



            $resp=$obj->getClientes('clientesCT');

            





            if($resp['Estado']){



                echo  json_encode(array("success" => true, "Data" => $resp['Data']));



            }



            else{



                echo json_encode(array("success" => false, "Data" => "Error controlador"));



            }







            break;







        case 'getSelect':







                $resp=$obj->getOneValue($post['data']['nameTabla'],$post['data']['datos']);



    



                if($resp['Estado']){



                    echo  json_encode(array("success" => true, "Data" => $resp['Data']));



                }



                else{



                    echo json_encode(array("success" => false, "Data" => "Error controlador"));



                }



    



                break;







        case 'altaCliente':



            $resp=$obj->altaCliente($post['data']);











            if ($resp) {







                echo json_encode(array(







                    "success" => true,







                    "message" => "Guardado de forma correcta",







                ), JSON_PRETTY_PRINT);







            }



            else {







                echo json_encode(array(







                    "success" => false,







                    "message" => "Error al guardar"







                ), JSON_PRETTY_PRINT);







            }







            break;



        case 'updateCliente':







            $resp=$obj->updateCliente($post['Id'],$post['data']);







            if($resp == true){



                $data= array("success" => true, "message" => "Se ha modificado Registro");



                echo json_encode($data);







            }else{



    



                $data= array("success" => false, "message" => "No se ha Encontrado Registro");



                echo json_encode($data);



            }



            break;







        case 'getDataCliente':



            $resp = $obj->GetDataIDEdit("clientesCT", $post['Id']);







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











        case 'getDataExcel':







            $resp=$obj->getDataExcel('clientesCT');



    



            if($resp['Estado']){



                echo  json_encode(array("success" => true, "Data" => $resp['Data']));



            }



            else{



                echo json_encode(array("success" => false, "Data" => "Error controlador"));



            }







            break;











            



    }



}