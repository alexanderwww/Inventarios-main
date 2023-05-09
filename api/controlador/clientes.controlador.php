<?php
include_once 'model/api.modelo.php';

class ControladorClientes extends apiModel{

    function dataClientes($tabla){

        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT * FROM '.$tabla;
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {

                $data[] = $datosBD;
             
            }
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
        
        return $mensaje;

    }

    function dataExcelClientes($tabla)
    {

        $arrayQuery=$this->dataClientes($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];
                $newRow['Estatus'] =$row['Status']?'✓':'X';

                $newRow['Nombre'] = $row['Nombre'];
                $newRow['Teléfono'] = $row['Telefono'];
                $newRow['Correo'] = $row['CorreoElectronico'];
                $newRow['Contacto Principal'] = $row['ContactoPrincipal'];
                $newRow['Razón Social'] = $row['RazonSocial'];
                $newRow['RFC'] = $row['RFC'];

                $newRow['Dirección'] = $row['PaisCliente'].', '.$row['EstadoCliente'].', '.$row['CiudadCliente'].', '.$row['CalleCliente'];
                
                $newRow['Codigo Postal'] = $row['CPCliente'];
                $newRow['Días de Credito'] = $row['dias_credito'];

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


}