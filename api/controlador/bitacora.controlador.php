<?php
include_once 'model/api.modelo.php';

class ControladorBitacora extends apiModel{

    function bitacora($codigo,$user,$userName,$comentario, $modulo){
        $sql='bitacora (IdOperacion, IdUser, User, Comentarios, Modulo) VALUES('.$codigo.','.$user.',"'.$userName.'","'.$comentario.'",'.$modulo.')';
        // print_r($sql);
        $respuesta=$this->setTableGrl($sql);
        
        if($respuesta==true){
            $mensaje= array("success" => true,"data"=>'success', "message" => "Bitacora Guardada");
        }else{
            $mensaje= array("success" => false,"data"=>'Error', "message" => "Error al guardar Bitacora");
        }
    return $mensaje;

    }
    
    function dataBitacora(){
        $sql='SELECT bt.Id, bt.TimeStamp, bt.User, bt.Comentarios, btc.Operacion, mol.Modulo AS Modulo FROM bitacora bt 
        INNER JOIN bitacoracodigo btc ON bt.IdOperacion = btc.Id
        JOIN modulos mol ON mol.Id = bt.Modulo';
        $respuesta=$this->getAllTable($sql);
        $data=[];
        while($bitBD =$respuesta->fetch_assoc()){
            $bitBD['Id']= (int)$bitBD['Id'];
            $data[]= $bitBD;
        }
        
        if($respuesta==true){
            $mensaje= array("success" => true,"data"=>$data, "message" => "Bitacora Guardada");
        }else{
            $mensaje= array("success" => false,"data"=>'Error', "message" => "Error al guardar Bitacora");
        }
    return $mensaje;

    }


    function dataExcelBitacora($tabla)
    {

        $arrayQuery=$this->dataBitacora();

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];

                $newRow['Usuario'] = $row['User'];
                $newRow['Fecha'] = $this->formatoFechaVista($row['TimeStamp']);
                $newRow['Operación'] = $row['Operacion'];
                $newRow['Modulo'] = $row['Modulo'];
                $newRow['Comentarios'] = $row['Comentarios'];

                $arrayDataExcel[] = $newRow;
            };

            $success = true;
            $msj = 'Datos del Excel correctamente';
            $data = $arrayDataExcel;
        } else {
            $msj = "No se recibieron datos";
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    
    function formatoFechaVista($date){
        // $date = '2022-12-16 10:07:21';
        $timestamp = strtotime($date);
        return date('d/m/Y', $timestamp);
        // echo $new_date;  // Imprime "16/12/2022"
    }
}
?>