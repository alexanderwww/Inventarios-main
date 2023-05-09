<?php
include_once 'model/api.modelo.php';

class ControladorApi extends apiModel{
    public function inicio(){
        include "api2.php";
    }
    function bitacora($codigo,$user,$userName,$comentario){
        $sql='bitacora (IdOperacion, IdUser, User, Comentarios) VALUES('.$codigo.','.$user.',"'.$userName.'","'.$comentario.'")';
        $respuesta=$this->setTableGrl($sql);
        
        if($respuesta==true){
            $mensaje= array("success" => true,"data"=>'success', "message" => "Bitacora Guardada");
        }else{
            $mensaje= array("success" => false,"data"=>'Error', "message" => "Error al guardar Bitacora");
        }
    return $mensaje;

    }
    function moneda($moneda,$TC = null){
        $success = false;
        $data = "Error";
        if (isset($moneda)) {
            if($moneda == 1){
                $_SESSION['Moneda']='Dolares';

            }else{
                $_SESSION['Moneda']='Pesos';
            }
            $success = true;
            $msj = 'Cambio de moneda a '.$_SESSION['Moneda'];
            $data = "Correcto";
            if(isset($TC) && $TC!=0 && !empty($TC)){
                $_SESSION['TC'] = $TC; 
            $success = true;
            $msj = 'Cambio de moneda Correcto '.$_SESSION['Moneda'];
            $data = "Correcto";
            }else{
                // $success = false;
                // $msj = 'El tipo de cambio debe ser diferente de 0';
                // $data = "Error";
            }


        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function resetTC($status){
        $success = false;
        $data = "Error";
        if (isset($status)) {
            if(isset($status)){
                if($status == 1){
                    $_SESSION['TC'] = $_SESSION['TipoCambio'];
                }
            }
                $success = true;
                $msj = 'Tipo de cambio actual a '.$_SESSION['TipoCambio'];
                $data = "Correcto";
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $_SESSION['TipoCambio'], "message" => $msj);

        return $mensaje;
    }
    // function TipoCambio($moneda,$TC){
    //     $success = false;
    //     $data = "Error";
    //     if (isset($moneda)) {
    //         if($moneda == 1){
    //             $_SESSION['Moneda']='Dolares';

    //         }else{
    //             $_SESSION['Moneda']='Pesos';
    //         }

    //             $success = true;
    //             $msj = 'Cambio de moneda a '.$_SESSION['Moneda'];
    //             $data = "Correcto";

    //     } else {
    //         $msj = "No se recibieron datos";
    //     }
    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;
    // }
}
?>