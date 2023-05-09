<?php

// include_once '../../../classes/dbvisormodel.class.php';
include_once '../../../requerimientos/classes/Model.php';


class clientesCtrl extends model{

    function getClientes($tableName){

        if(!is_string($tableName)){

            return false;

        }else{

          $query="

            SELECT ct.Id,ct.Status,ct.Nombre,ct.RazonSocial,ct.RFC,ct.RegimenFiscal,ct.Telefono,ct.Ext,ct.CalleCliente,ct.CPCliente,
                ct.dias_credito,ct.ColoniaCliente,
                ciudad.Ciudad, estado.Estado,pais.Pais
                FROM clientes ct
                LEFT JOIN paises pais ON ct.Id_Pais_CltFk = pais.Id_Pais
                LEFT JOIN estados estado ON ct.Id_Estado_CltFk = estado.Id_Estado
                LEFT JOIN ciudades ciudad ON ct.Id_Ciudad_CltFk = ciudad.Id_Ciudad
                LEFT JOIN user_accounts usuario ON ct.Ejecutiva = usuario.Id";

            $Object = $this->getAllTable($query);

            if(!is_null($Object)){

                $plataforma = [];

                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos

                        $filasDb['Id']=intval($filasDb['Id']);                        
                        $filasDb['Nombre']=$this->limpiarString($filasDb['Nombre']);
                        $filasDb['RazonSocial']=$this->limpiarString($filasDb['RazonSocial']);
                        $filasDb['RFC']=$this->limpiarString($filasDb['RFC']);
                        $filasDb['Telefono']=$this->limpiarString($filasDb['Telefono']);
                        
                        if($filasDb['Status']==1){
                        
                            $filasDb['Status']='Activo';
                    
                        }else{

                            $filasDb['Status']='Inactivo';


                        }

                        $filasDb['Ext']=$this->limpiarString($filasDb['Ext']);
                        $filasDb['CalleCliente']= $this->limpiarString($filasDb['CalleCliente']).' '.$this->limpiarString($filasDb['ColoniaCliente']).' '.$this->limpiarString($filasDb['Ciudad']).' '.$this->limpiarString($filasDb['Estado']).' '. $this->limpiarString($filasDb['Pais']);
                        $filasDb['CPCliente']=$this->limpiarString($filasDb['CPCliente']);
                        $filasDb['dias_credito']=$this->limpiarString($filasDb['dias_credito']);
                        if($_SESSION['Rol']['clienteU']==1){
                            $filasDb["acciones"] ="
                            <div class='btn-group'>
                                    <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='false' aria-expanded='true'>Acción</button>
                                    <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
            
                                        <a type='button'  class='dropdown-item btnTablaEditar'  data-bs-toggle='modal' data-bs-target='#modalEditar' id='ed".$filasDb["Id"]."'  >Editar</a>
            
                                    </div>
                            </div>";
                        }else{
                            $filasDb["acciones"] ="
                            <div class='btn-group'>
                                    <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='false' aria-expanded='true' disabled>Acción</button>
                                    <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
            
                                        <a type='button'  class='dropdown-item btnTablaView'  id='ed".$filasDb["Id"]."'  >Ver</a>
            
                                    </div>
                            </div>";
                        }
                       
        

                        // $filasDb["acciones"] = "<button class='btn btn-warning btn-sm rounded-10 btnTablaEditar' type='button' data-bs-toggle='modal' data-bs-target='#modalEditar' id='ed".$filasDb["Id"]."'  )'><i class='bx bxs-edit'></i></button>";

                        

                        $plataforma[] = $filasDb;                   



                }              



                return  array("Estado" => true, "Data" => $plataforma);



            }else{



                return array("Estado" => true, "Data" => "Error controlador");



            }





        }





    }



    function getOneValue($tableName,$data){

       
        if($tableName == 'cuentasUSUARIOS'){
            $tableName = 'user_accounts';
        }


        if(!is_string($tableName)){

            return false;



        }else{


            // print_r($tableName);
            $query = "SELECT ".$data." From $tableName";
            // print_r($query);


            $Object = $this->getAllTable($query);



            if(!is_null($Object)){



                $plataforma = [];



                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos

                   



                    if($tableName=='user_accounts'){

                        

                        if($filasDb['Status']==1){



                            if($filasDb['Rol']=='facturacion cobro'){

                             

                                $plataforma[] = $filasDb;

                            

                            }

                        

                        }



                    }else{

                        

                        $plataforma[] = $filasDb;

                    

                    }

                   



                }

               



                switch ($tableName){

                    

                    case 'paises':

                        

                        $vacio=array("Id_Pais"=>'Seleccione uno...', "Pais"=>'Seleccione uno...');



                        array_unshift($plataforma,$vacio);



                        break;

                    

                    case 'estados':



                        $vacio=array("Id_Estado"=>'Seleccione uno...', "Id_Pais_Fk"=> 'vacio', "Estado"=>'Seleccione uno...');



                        array_unshift($plataforma,$vacio);



                        break;





                    case 'ciudades':

                       

                        $vacio=array("Id_Estado_Fk"=>'vacio',"Id_Ciudad"=>'Seleccione uno...', 'Ver'=>'1' , "Ciudad"=>'Seleccione uno...');

                       

                        array_unshift($plataforma,$vacio);

                       

                        break;



                    case 'user_accounts':

                       

                        $vacio=array("Id"=>'Seleccione uno...', "Usuario"=>'Seleccione uno...');

                       

                        array_unshift($plataforma,$vacio);

                       

                        break;

                }



                return  array("Estado" => true, "Data" => $plataforma);



            }else{



                return array("Estado" => true, "Data" => "Error controlador");



            }





        }





    }



    function limpiarString($string){

        $nuevoValue=' ';

        return str_replace(array("/","'",'"'),$nuevoValue, $string );



    }



    function altaCliente($data){



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





            $result = $this->putValuesTable('clientes',$values,$indexs);

        }

        else {

        $result=null;

        }



        return $result;



    }



    function GetDataIDEdit($tableName,$id){





        if($tableName != "" && is_int($id)){



            $query = "SELECT C.*,U.name FROM clientes C LEFT JOIN user_accounts U ON C.Ejecutiva = U.Id WHERE C.Id=".$id;



            $Object = $this->getAllTable($query);



            if(!is_null($Object)){



                $row = mysqli_fetch_assoc($Object);



                return  array("Estado" => true, "Data" => $row);



            }else{



                return array("Estado" => false, "Data" => "Error controlador");



            }



        }else{



            return array("Estado" => False, "Data" => "Datos vacios");



        }



    }



    function updateCliente($id,$data){



        $indicesValores="";

        foreach ($data as $ky => $val) {

            $indicesValores.="`".$ky."`='".$val."',";

        }

     



        $indicesValores= rtrim($indicesValores,",");



        $respUp=$this->updateTableIndex('clientes',$indicesValores,'Id',$id);



        return $respUp;

        

    }

  

    function getDataExcel($tableName){





        if(!is_string($tableName)){

            return false;



        }else{



            $query="

            SELECT ct.Id,ct.Status,ct.Nombre,ct.RazonSocial,ct.RFC,ct.RegimenFiscal,ct.Telefono,ct.Ext,ct.CalleCliente,ct.CPCliente,

                ct.dias_credito,ct.ColoniaCliente,

                ciudad.Ciudad, estado.Estado,pais.Pais,usuario.name AS Ejecutiva

                FROM clientes ct

                LEFT JOIN paises pais ON ct.Id_Pais_CltFk = pais.Id_Pais

                LEFT JOIN estados estado ON ct.Id_Estado_CltFk = estado.Id_Estado

                LEFT JOIN ciudades ciudad ON ct.Id_Ciudad_CltFk = ciudad.Id_Ciudad

                LEFT JOIN user_accounts usuario ON ct.Ejecutiva = usuario.Id";



            $Object = $this->getAllTable($query);



            if(!is_null($Object)){



                $plataforma = [];



                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos

                    

                        $filasExcel=[];

                        

                        $filasExcel['Id']=intval($filasDb['Id']);



                        if($filasDb['Status']==1){

                            $filasExcel['Status']='Activo';

                        }else{

                            $filasExcel['Status']='Inactivo';

                        }



                        

                        $filasExcel['Nombre Comercial']=$this->limpiarString($filasDb['Nombre']);





                        $filasExcel['Razón Social']=$this->limpiarString($filasDb['RazonSocial']);



                        $filasExcel['RFC']=$this->limpiarString($filasDb['RFC']);

                        

                        $filasExcel['Teléfono']=$this->limpiarString($filasDb['Telefono']);

                        

                        $filasExcel['Extensión']=$this->limpiarString($filasDb['Ext']);

                        

                        $filasExcel['Nombre Ejecutiva']=$filasDb['Ejecutiva'];

                        

                        $filasExcel['Dirección']= $this->limpiarString($filasDb['CalleCliente']).' '.$this->limpiarString($filasDb['ColoniaCliente']).' '.$this->limpiarString($filasDb['Ciudad']).' '.$this->limpiarString($filasDb['Estado']).' '. $this->limpiarString($filasDb['Pais']);

                        

                        $filasExcel['CP del Cliente']=$this->limpiarString($filasDb['CPCliente']);

                        

                        $filasExcel['Días de crédito']=$this->limpiarString($filasDb['dias_credito']);



                        $plataforma[] = $filasExcel;

                                         

                }

               



                return  array("Estado" => true, "Data" => $plataforma);



            }else{



                return array("Estado" => true, "Data" => "Error controlador");



            }





        }





    }



}

