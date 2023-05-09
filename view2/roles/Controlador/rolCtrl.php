<?php

// include_once '../../../classes/dbvisormodel.class.php';
include_once '../../../requerimientos/classes/Model.php';
class usuariosCtrl extends model{

    function getDataRol(){

            $query = 'SELECT id,nombre FROM roles';
            $Object = $this->getAllTable($query);
            if(!is_null($Object)){
                $plataforma = [];
                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos
                        if($_SESSION['Rol']['rolU'] == 1){
                       $btnEditar ="<a type='button'  class='dropdown-item btnEditarTabla'  data-bs-toggle='modal' data-bs-target='#editRol' id='ed".$filasDb["id"]."'  >Editar</a>";
                        // $filasDb["editar"] = "<button class='btn btn-warning btn-sm rounded-10 btnEditarTabla' type='button' data-bs-toggle='modal' data-bs-target='#editRol' id='ed".$filasDb["id"]."'  )'><i class='bx bxs-edit'></i></button>";
                        }else{
                            // $filasDb["editar"] = "<button class='btn btn-secondary btn-sm rounded-10 ' type='button'  readonly disabled )'><i class='bx bxs-edit'></i></button>";
                            $btnEditar ="";

                        }
                        if($_SESSION['Rol']['rolD'] == 1){
                            $btnEliminar="<a type='button'  class='dropdown-item btnElimarTabla'  data-bs-toggle='modal' data-bs-target='#btnEliminarRol' id='el".$filasDb["id"]."' name='".$filasDb["nombre"]."'  >Eliminar</a>";
                            // $filasDb["eliminar"] = "<button class='btn btn-danger btn-sm rounded-10 btnElimarTabla' type='button' data-bs-toggle='modal' data-bs-target='#btnEliminarRol' id='el".$filasDb["id"]."' name='".$filasDb["nombre"]."' ><i class='bx bx-trash'></i></button>";
                        }else{
                            // $filasDb["eliminar"] = "<button class='btn btn-secondary btn-sm rounded-10 ' type='button' readonly disabled ><i class='bx bx-trash'></i></button>";
                            $btnEliminar="";

                        }
                        if($_SESSION['Rol']['rolU'] == 1 && $_SESSION['Rol']['rolD'] == 1){
                            // $btns=" <a type='button'  class='dropdown-item btnView'  data-bs-toggle='modal' data-bs-target='#viewRol' id='vi".$filasDb["id"]."'  >Ver</a>";

                            $filasDb["acciones"] ="
                            <div class='btn-group'>
                                    <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='false' aria-expanded='true'>Acción</button>
                                    <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
            
                                       ".$btnEditar."
                                       ".$btnEliminar."
                                       
            
                                    </div>
                            </div>";
                        }else{
                            // $btns=" <a type='button'  class='dropdown-item btnView'  data-bs-toggle='modal' data-bs-target='#viewRol' id='vi".$filasDb["id"]."'  >Ver</a>";
                            $filasDb["acciones"] ="
                            <div class='btn-group'>
                                    <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='false' aria-expanded='true' disabled>Acción</button>
                                    <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
            
                                    </div>
                            </div>";
                        }
                        
                        $plataforma[] = $filasDb;
                }
                return  array("Estado" => true, "Data" => $plataforma);
            }else{
                return array("Estado" => true, "Data" => "Error controlador");
            }
    }

    public function getRolEdit($id)
    {
        if(is_int($id)){
            $query = "SELECT * From roles where id=$id";
            $Object = $this->getAllTable($query);
            if(!is_null($Object)){
                $plataforma = [];
                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos
                        $plataforma[] = $filasDb;
                }
                return  array("Estado" => true, "Data" => $plataforma);
            }else{
                return array("Estado" => true, "Data" => "Error controlador");
            }


        }else{
            return array("Estado" => false, "Data" => "Error en id");
        }
       


    }
    function GetDataIDEdit($tableName,$id){

        if($tableName != "" && is_int($id)){

            $query = "SELECT * From $tableName where id=$id";

            $Object = $this->getAllTableP($query);

            if(!is_null($Object)){

                $row = mysqli_fetch_assoc($Object);

                //Regresa los datos asociados

                return  array("Estado" => true, "Data" => $row);

            }else{

                return array("Estado" => false, "Data" => "Error controlador");

            }

        }else{

            return array("Estado" => False, "Data" => "Datos vacios");

        }

    }

    function cambiarStatusUser($idUser,$data){
        $idString=substr($idUser, 2);
        $id=intval($idString);
        $password=$data['Password'].time();
        $data['Password']=md5($password);
        $indicesValores="";
        foreach ($data as $ky => $val) {
            $indicesValores.="`".$ky."`='".$val."',";
        }
        $indicesValores= rtrim($indicesValores,",");
        // return $indicesValores;
        $respUp=$this->updateTableIndex('cuentasUSUARIOS',$indicesValores,'Id',$id);
        return $respUp;
    }

    function getChecks(){
        
        $rol = $_SESSION['Rol'];
        unset($rol['id']);
        unset($rol['nombre']);
        return $rol;
    }
    function altaRoles($nombre,$roles){
        $forminfoarray = $roles;
        if (is_array($forminfoarray)){
            $indexcoma="`nombre`,";
            $valuecoma="'$nombre',";
            foreach ($forminfoarray as $key => $value) {
                $indexcoma.="`".$value."`,";
                $valuecoma.="'True',";
            }
            $indexs = rtrim($indexcoma,",");
            $values = rtrim($valuecoma,",");
            $result = $this->putValuesTable('roles',$values,$indexs);
        }
        else {
        $result=null;
        }
        return $result;

    }
    function UpdateRol($datos,$id){
        $valuecoma="";
        foreach ($datos as $key => $value) {
          if(!is_array($value)){
            $valuecoma.="`".$key."`='".$value."',";
          }
      }
      $values = rtrim($valuecoma,",");
      $query = "UPDATE roles SET $values WHERE id =$id";
      $Object = $this->updateTable($query);
      return $Object; 
    }
    function deleteRol($id)
    {
        if(!empty($id)){
            if($_SESSION['Rol']['rolD'] == 1){
                $query = "DELETE FROM roles WHERE id =$id";
                $Object = $this->deleteRow($query);
                return array('success' => $Object, 'message' =>'Rol Eliminado de forma correcta');
            }else{
                return array('success' => false, 'message' =>'No tienes permiso para eliminar roles');
            }
        }else{
            return array('success' => false, 'message' =>'Id vacio');
        }
    }

}
// ---999
