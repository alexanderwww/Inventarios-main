<?php
include_once 'model/api.modelo.php';

class ControladorUsuarios extends apiModel{

    function createUsuario($arrayUsuarios){
        $success=false;
        $data="Error";
        if(is_array($arrayUsuarios)){
            $pass = md5($arrayUsuarios['Password']);
            $query= 'user_accounts (User, Name, Email, Password, Rol) VALUES ';
            $resp= $this->setTableGrl($query );
            if ($resp==true) {
                $success=true;
                $msj='Factura Eliminada';
                $data="success";
            }else{
                $msj='Error al eliminar XML en VR';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function dataUsuariosId($tabla,$Id){
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
    function dataUsuariosTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT u.*, rol.nombre AS nameRol FROM '.$tabla.' u JOIN roles rol ON rol.id = u.Rol';
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                if($datosBD['Status']==1){
                    $datosBD['Status']='Activo';
                }else{
                    $datosBD['Status']='Inactivo';
                }
                if($_SESSION['Rol']['usuarioU']==1){
                    $btns=" <a type='button'  class='dropdown-item btnEditarTabla'  data-bs-toggle='modal' data-bs-target='#editUsuario' id='ed".$datosBD["Id"]."'  >Editar</a>
                    <a type='button'  class='dropdown-item btnView' id='vi".$datosBD["Id"]."'  name='".$datosBD['User']."' >Ver</a>";
                }else{
                    $btns=" <a type='button'  class='dropdown-item btnView' id='vi".$datosBD["Id"]."'  name='".$datosBD['User']."' >Ver</a>";
                }
                $datosBD["acciones"] ="
                <div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                           ".$btns."

                        </div>
                </div>";
                // $datosBD["acciones"] = "<button class='btn btn-warning btn-sm rounded-10 btnEditarTabla' type='button' data-bs-toggle='modal' data-bs-target='#editUsuario' id='ed".$datosBD["Id"]."'  )'><i class='bx bxs-edit'></i></button>";

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
    function dataUsuarios($tabla,$status){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            if($status==2){
                $query= 'SELECT * FROM '.$tabla;
            }else{
                $query= 'SELECT * FROM '.$tabla.' WHERE Active='.$status;
            }
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


    function dataExcelUsuarios($tabla)
    {

        $arrayQuery=$this->getDataUsuarios($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];
                $newRow['Estatus'] = $row['Status']?'Activo':'Inactivo';
                $newRow['Usuario'] = $row['User'];
                $newRow['Nombre'] = $row['Name'];
                $newRow['Correo'] = $row['Email'];
                $newRow['Rol'] = $row['nameRol'];
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


    function getDataUsuarios($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            // $query= 'SELECT * FROM '.$tabla;
            $query='SELECT user_accounts.*, rol.nombre AS nameRol FROM user_accounts JOIN roles rol ON rol.id = user_accounts.Rol ';
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



}
?>