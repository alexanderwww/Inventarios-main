<?php



include_once '../../../requerimientos/classes/Model.php';
// include_once '../../../classes/dbvisormodel.class.php';



class provedoresCtrl extends model{

            

    function getProveedores($tableName){
        if(!is_string($tableName)){
            return false;
        }else{





            $query ="

            SELECT pct.Id,pct.NombreCom,pct.RazonSocial,pct.RFC,pct.ContactoPrincipal,pct.Telefono,pct.Ext,pct.CalleProveedor,pct.CPProveedor,pct.DiasCredito,pct.Status,pct.NoCuenta,pct.MonedaBckup,pct.ColoniaProveedor,

            ciudad.Ciudad,estado.Estado,pais.Pais

			FROM proveedores pct

			LEFT JOIN paises pais ON pct.Id_Pais_Fk = pais.Id_Pais

			LEFT JOIN estados estado ON pct.Id_Estado_Fk = estado.Id_Estado

			LEFT JOIN ciudades ciudad ON pct.Id_Ciudad_Fk = ciudad.Id_Ciudad

            ";
            // print_r($query);
            $db = $this->conecta();
            $Object=$db->query($query);
            // $sth->bind_param('s',$tableName);
            // if(!$sth->execute()) throw New Exception();
            // $resul   = $sth->get_result();
            // $Object = $this->getAllTableP($query,$tableName);
            // $Object = $resul->fetch_all(MYSQLI_ASSOC);
            if(!is_null($Object)){
                $plataforma = [];
                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos
                        $filasDb['Id']=intval($filasDb['Id']);
                        $filasDb['NombreCom']=$this->limpiarString($filasDb['NombreCom']);
                        $filasDb['RazonSocial']=$this->limpiarString($filasDb['RazonSocial']);
                        $filasDb['RFC']=$this->limpiarString($filasDb['RFC']);
                        $filasDb['ContactoPrincipal']=$this->limpiarString($filasDb['ContactoPrincipal']);
                        if($filasDb['Status']==1){
                            $filasDb['Status']='Activo';
                        }else{
                            $filasDb['Status']='Inactivo';
                        }
                        $filasDb['Ext']=$this->limpiarString($filasDb['Ext']);
                        $filasDb['CalleProveedor']= $this->limpiarString($filasDb['CalleProveedor']).' '.$this->limpiarString($filasDb['ColoniaProveedor']).' '.$this->limpiarString($filasDb['Ciudad']).' '.$this->limpiarString($filasDb['Estado']).' '. $this->limpiarString($filasDb['Pais']);
                        $filasDb['CPProveedor']=$this->limpiarString($filasDb['CPProveedor']);
                        $filasDb['DiasCredito']=$this->limpiarString($filasDb['DiasCredito']);
                        $filasDb["btnEditarProvedores"] = "<button class='btn btn-warning btn-sm rounded-10 btnEditarProvedor' type='button' data-bs-toggle='modal' data-bs-target='#modalEditarProvedor' id='ed".$filasDb["Id"]."'  )'><i class='bx bxs-edit'></i></button>";
                        $plataforma[] = $filasDb;
                }
                return  array("Estado" => true, "Data" => $plataforma);
            }else{
                return array("Estado" => true, "Data" => "Error controlador");
            }
        }
    }
    function getOneValue($tableName,$data,$idBuscar){





        if(!is_string($tableName)){

            return false;



        }else{


            if(is_numeric($idBuscar) && $tableName=='estados'){

                $query = "SELECT ".$data." From ".$tableName." where Id_Pais_Fk=".$idBuscar;

            }elseif(is_numeric($idBuscar) && $tableName=='ciudades'){

                $query = "SELECT ".$data." From ".$tableName." where Id_Estado_Fk=".$idBuscar;

            }
            else{

                $query = "SELECT ".$data." From $tableName";

            }
            
            // print_r($query);
            $db = $this->conecta();
            $Object=$db->query($query);
            

            // $Object = $this->getAllTableP($query);



            if(!is_null($Object)){



                $plataforma = [];



                while ($filasDb = $Object->fetch_assoc()) {//Asociar los datos devueltos



                    $plataforma[] = $filasDb;



                }
                // switch ($tableName){



                //     case 'paises':

                //         $vacio=array("Id_Pais"=>'Seleccione uno...', "Pais"=>'Seleccione uno...');

                //         array_unshift($plataforma,$vacio);

                //         break;



                //     case 'estados':

                //         $vacio=array("Id_Estado"=>'Seleccione uno...', "Id_Pais_Fk"=> 'vacio', "Estado"=>'Seleccione uno...');

                //         array_unshift($plataforma,$vacio);



                //         break;





                //     case 'ciudades':

                //         $vacio=array("Id_Estado_Fk"=>'vacio',"Id_Ciudad"=>'Seleccione uno...', 'Ver'=>'1' , "Ciudad"=>'Seleccione uno...');

                //         array_unshift($plataforma,$vacio);

                //         break;



                // }



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



    function altaProvedor($data){



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


            // $query = "INSERT SET " ;
            // $db = $this->conecta();
            // $Object=$db->query($query);

            $result = $this->putValuesTable('proveedores',$values,$indexs);
                // print_r($result);
        }

        else {

        $result=null;

        }



        return $result;



    }



    function GetDataIDEdit($tableName,$id){





        if($tableName != "" && is_int($id)){



            $query = "SELECT * From $tableName where id=$id";



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



    function UpdateProvedor($id,$data){
        $indicesValores="";
        foreach ($data as $ky => $val) {
            $indicesValores.="`".$ky."`='".$val."',";
        }
        $indicesValores= rtrim($indicesValores,",");
        $respUp=$this->updateTableIndex('proveedores',$indicesValores,'Id',$id);
        return $respUp;
    }
    function getDataExcel($tableName){
        if(!is_string($tableName)){
            return false;
        }else{
            $query ="SELECT pct.Id,pct.NombreCom,pct.RazonSocial,pct.RFC,pct.ContactoPrincipal,pct.Telefono,pct.Ext,pct.CalleProveedor,pct.CPProveedor,pct.DiasCredito,pct.Status,pct.NoCuenta,pct.MonedaBckup,pct.ColoniaProveedor,
            ciudad.Ciudad,estado.Estado,pais.Pais
			FROM ".$tableName." pct
			LEFT JOIN paisesCT pais ON pct.Id_Pais_ProFk = pais.Id_Pais
			LEFT JOIN estadosCT estado ON pct.Id_Estado_ProFk = estado.Id_Estado
			LEFT JOIN ciudadesCT ciudad ON pct.Id_Ciudad_ProFk = ciudad.Id_Ciudad";
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





                        $filasExcel['Nombre Comercial']=$this->limpiarString($filasDb['NombreCom']);



                        $filasExcel['Razón Social']=$this->limpiarString($filasDb['RazonSocial']);



                        $filasExcel['RFC']=$this->limpiarString($filasDb['RFC']);



                        $filasExcel['Contacto Principal']=$this->limpiarString($filasDb['ContactoPrincipal']);



                        $filasExcel['Teléfono']=$this->limpiarString($filasDb['Telefono']);



                        $filasExcel['Extensión']=$this->limpiarString($filasDb['Ext']);



                        $filasExcel['Dirección']= $this->limpiarString($filasDb['CalleProveedor']).' '.$this->limpiarString($filasDb['ColoniaProveedor']).' '.$this->limpiarString($filasDb['Ciudad']).' '.$this->limpiarString($filasDb['Estado']).' '. $this->limpiarString($filasDb['Pais']);



                        $filasExcel['CP del Proveedor']=$this->limpiarString($filasDb['CPProveedor']);



                        $filasExcel['Días de crédito']=$this->limpiarString($filasDb['DiasCredito']);



                        $filasExcel['No. de Cuenta']=$filasDb['NoCuenta'];



                        $filasExcel['Moneda']=$filasDb['MonedaBckup'];





                        $plataforma[] = $filasExcel;



                }





                return  array("Estado" => true, "Data" => $plataforma);



            }else{



                return array("Estado" => true, "Data" => "Error controlador");



            }





        }





    }











}

