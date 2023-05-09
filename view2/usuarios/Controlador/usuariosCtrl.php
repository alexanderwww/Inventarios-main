<?php

// include_once '../../../classes/dbvisormodel.class.php';
include_once '../../../requerimientos/classes/Model.php';
class usuariosCtrl extends model{

    function getAllData($tableName){

        if(!is_string($tableName)){
            return false;

        }else{
            if($tableName == 'cuentasUSUARIOS'){
                $tableName = 'user_accounts';
            }
            $query = 'SELECT * FROM '.$tableName;
            $Object = $this->getAllTable($query);

            if(!is_null($Object)){

                $plataforma = [];

                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos

                    // if($filasDb['Status']==1 && $tableName=='cuentasUSUARIOS'){
                    if($tableName =='user_accounts'){

                        if($filasDb['Status']==1){
                            $filasDb['Status']='Activo';
                        }else{
                            $filasDb['Status']='Inactivo';
                        }
                        $filasDb["acciones"] ="
                        <div class='btn-group'>
                                <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                                <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
        
                                    <a type='button'  class='dropdown-item btnEditarTabla'  data-bs-toggle='modal' data-bs-target='#editUsuario' id='ed".$filasDb["Id"]."'  >Editar</a>
        
                                </div>
                        </div>";
                        // $filasDb["acciones"] = "<button class='btn btn-warning btn-sm rounded-10 btnEditarTabla' type='button' data-bs-toggle='modal' data-bs-target='#editUsuario' id='ed".$filasDb["Id"]."'  )'><i class='bx bxs-edit'></i></button>";

                        // $filasDb["eliminar"] = "<button class='btn btn-danger btn-sm rounded-10 btnElimarTabla' type='button' data-toggle='modal' data-target='#btnEliminarUsuario' id='el".$filasDb["Id"]."' name='".$filasDb["Nombre"]."' ><i class='fa fa-trash'></i></button>";
                        
                        // $filasDb["AsignarUnidades"]="<button class='btn btn-warning btn-sm rounded-10 btnAsignarUnidadeTabla' type='button' data-toggle='modal' data-target='#btnAsignarUsuario' id='un".$filasDb["Id"]."' value='un".$filasDb["Id"]."' name='".$filasDb["Name"]."' ><i class='fa fa-check-square-o'></i></button>";

                        // $filasDb["reset"]="<button class='btn btn-info btn-sm rounded-10 btnResetTabla ' type='button' data-toggle='modal' data-target='#btnResetUsuario' id='un".$filasDb["Id"]."' value='re".$filasDb["Id"]."' name='".$filasDb["Name"]."' ><i class='fa fa-refresh'></i></button>";

                        $plataforma[] = $filasDb;
                    }
                    if($tableName=='unidadesSTATUS'){
                        $plataforma[] = $filasDb;
                    }


                }

                return  array("Estado" => true, "Data" => $plataforma);

            }else{

                return array("Estado" => true, "Data" => "Error controlador");

            }

        }

    }
    function getOneValue($tableName,$data){
        if(!is_string($tableName)){
            return false;
        }else{
            $query = "SELECT ".$data." From $tableName";
            // print_r($query);
            $db = $this->conecta();
            $Object=$db->query($query);
            // $Object = $this->getAllTableP($query);
            if(!is_null($Object)){
                $plataforma = [];
                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos
                    $plataforma[] = $filasDb;
                }
                $vacio=array("id"=>'Seleccione uno...', "nombre"=>'Seleccione uno...');
                array_unshift($plataforma,$vacio);
                return  array("Estado" => true, "Data" => $plataforma);
            }else{
                return array("Estado" => true, "Data" => "Error controlador");
            }
        }
    }
    function getRoles(){
        $file="../../opciones/roles.json";

        if(array_key_exists("id",$_SESSION)){

            if(is_readable($file)){

                $lista= json_decode(file_get_contents($file),true);
                $listNueva=[];

                if (is_array($lista)&&!empty($lista)) {

                    if ($_SESSION['rol']=="monitorista") {
                      $listNueva[]=array("valor"=>"cliente","Text"=>"cliente");
                    }else {
                      foreach ($lista as $ky1 => $val1) {
                          if ($val1["rol"]!="superadmin") {
                              $listNueva[]=array("valor"=>$val1["rol"],"Text"=>$val1["rol"]);
                          }
                      }
                    }

                    $vacio=array("valor"=>"","Text"=>"Seleccione uno...");
                    array_unshift($listNueva,$vacio);

                    return array('Estado'=>true,"Rol"=>$listNueva);

                }
                else{
                    return array('Estado'=>false,"Rol"=>[],"message"=>"Todavía no hay una lista de roles");
                }
            }
            else{
                return array('Estado'=>false,"Rol"=>[],"message"=>"No se pudo accesar a los datos.");
            }
        }
        else{
          return array('Estado'=>false,"Rol"=>[],"message"=>"No has iniciado sesión");
        }
    }

    function GetDataIDEdit($tableName,$id){

        if($tableName != "" && is_int($id)){

            $query = "SELECT * From $tableName where id=$id";

            $Object = $this->getAllTable($query);

            if(!is_null($Object)){

                $row = mysqli_fetch_assoc($Object);

                //Regresa los datos asociados

                // return $row;
                return  array("Estado" => true, "Data" => $row);

            }else{

                return array("Estado" => false, "Data" => "Error controlador");

            }

        }else{

            return array("Estado" => False, "Data" => "Datos vacios");

        }

    }

    function updateUsuario($idUpdate,$data){

        // $idString=substr($idUpdate, 2);
        $id=intval($idUpdate);
        // print_r($data);
        // print_r($idUpdate);

        // die;
        if(gettype($data['Password']) === 'array'){
            $data['Password']=$data['Password'][1];            
        }else{
            // Si el password se modifico se encripta
            $data['Password']=md5($data['Password']);

        }
        $data['Active']=$data['Status'];
        if($data['Status']=='0'){
            $password=$data['Password'].time();
            $data['Password']=md5($password);
        }

        $indicesValores="";
        foreach ($data as $ky => $val) {
            $indicesValores.="`".$ky."`='".$val."',";
        }


        $indicesValores= rtrim($indicesValores,",");
        $respUp=$this->updateTableIndex('user_accounts',$indicesValores,'Id',$id);

        return $respUp;




    }

    function altaUser($data){
        // $data['Equipos']="[]";
        $data['Password']=md5($data['Password']);

        $forminfoarray = $data;

        if (is_array($forminfoarray)){

            $indexcoma="";
            $valuecoma="";
            foreach ($forminfoarray as $key => $value) {

                $indexcoma.="`".$key."`,";
                $valuecoma.="'".$value."',";
            }
            $indexs = rtrim($indexcoma,",");
            $values = rtrim($valuecoma,",");

            $result = $this->putValuesTable('user_accounts',$values,$indexs);
        }
        else {
        $result=null;
        }

        return $result;

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

    function updateAsignarUnidades($idUpdate,$data){

        $idString=substr($idUpdate, 2);
        $id=intval($idString);

        $stringData=implode(",",$data);

        $indicesValores="`Equipos`='[".$stringData."]'";

        // `Nombre`='Lorena Gonzalez',`Email`='lorena.gonzalez@controlterrestre.com'

        $respUp=$this->updateTableIndex('cuentasUSUARIOS',$indicesValores,'Id',$id);

        return $respUp;


    }

    function restablecimiento($id){

        $dat=' `cuentasUSUARIOS` SET `Secret`=NULL WHERE  `Id`='.$id.'';

        $resp=$this->updateTableGrl($dat);
        return $resp;
    }


}



// ---999
