<?php
include_once 'model/api.modelo.php';
// if($_SESSION['Moneda']=='Pesos'){
//     $Moneda = 1;
// }else{
//     $Moneda = 1/$_SESSION['TipoCambio'];
// }
class ControladorProveedores extends apiModel{

    function dataProveedores($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT * FROM '.$tabla;
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                if($datosBD['Status']==1){
                    $buttonStatus = "<a type='button'  class='dropdown-item btnDeshabilitarTabla' id='de" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Deshabilitar</a>";
                }else{
                    $buttonStatus = "<a type='button'  class='dropdown-item btnHabilitarTabla' id='de" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Habilitar</a>";

                    // $buttonStatus = " <a type='button'  class='dropdown-item btnHabilitarTabla' attr-status='" . $datosBD["Status"] . "' id='de" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Habilitar</a>";
                    
                }
                if($_SESSION['Rol']['proveedorU']==1){
                    
                    $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                                    
                                ".$buttonStatus."
    
                                <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["Id"] . "'  '  >Editar</a>
                                <a type='button'  class='dropdown-item btnView'   id='vi" . $datosBD["Id"] . "'  '  >Ver</a>

    
                            </div>
                    </div>";
                }else{
                    $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>                                  
    
                                <a type='button'  class='dropdown-item btnView'   id='vi" . $datosBD["Id"] . "'  '  >Ver</a>
    
                            </div>
                    </div>";
                }
              
                if($datosBD['Status']==1){
                    $datosBD['Status']='Activo';
                }else{
                    $datosBD['Status']='Inactivo';
                }
                $datosBD['Id']=(int)$datosBD['Id'];
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
    function dataProveedoresId($tabla,$Id){
        $success=false;
        $data="Error";
        if(!empty($tabla) && !empty($Id)){
            $query= 'SELECT * FROM '.$tabla.' WHERE Id='.$Id;
            $resp= $this->getAllTable($query);
            $data=[];
            $data=$resp->fetch_assoc();
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: '.$tabla;
            }
        }else{
            $msj = "No se recibio ID";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function dataProveedoresIdStatus($tabla,$Id,$status){
        $success=false;
        $data="Error";
        if(!empty($tabla) && !empty($Id)){
            $query= 'SELECT p.*, pais.Pais AS namePais, est.Estado AS nameEstado, cid.Ciudad AS nameCiudad FROM '.$tabla.' p 
            JOIN paises pais ON p.Id_Pais_Fk = pais.Id_Pais
            JOIN estados est ON p.Id_Estado_Fk = est.Id_Estado
            JOIN ciudades cid ON p.Id_Ciudad_Fk = cid.Id_Ciudad WHERE Id='.$Id;
            $resp= $this->getAllTable($query);
            $data=[];
            $data=$resp->fetch_assoc();
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: '.$tabla;
            }
        }else{
            $msj = "No se recibio ID";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }

    function putProveedoresStatus($tabla,$id,$status){
        $success=false;
        $data="Error";
        if(isset($id)&& isset($tabla)&& isset($status)){

            $query = "UPDATE ".$tabla." SET Status = $status WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp==true) {
                $success=true;
                $msj='Proveedor actualizado correctamente';
                $data="Correcto";
            }else{
                $msj='Error al agregar un Proveedor: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }

    function dataExcelProveedores($tabla)
    {

        $arrayQuery=$this->getDataProveedores($tabla);

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

                $newRow['Dirección'] = $row['PaisProveedor'].', '.$row['EstadoProveedor'].', '.$row['CiudadProveedor'].', '.$row['CalleProveedor'];
                
                $newRow['Codigo Postal'] = $row['CPProveedor'];
                $newRow['Días de Credito'] = $row['DiasCredito'];
                $newRow['No. de Cuenta'] = $row['NoCuenta'];
                $newRow['Moneda'] = $row['MonedaBckup'];

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



    function getDataProveedores($tabla)
    {

        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            $query = 'SELECT * FROM ' . $tabla;
            $resp = $this->getAllTable($query);
            $data = [];

            while ($datosBD = $resp->fetch_assoc()) {

                $data[] = $datosBD;
            }

            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);
        return $mensaje;
    }
  
}
?>