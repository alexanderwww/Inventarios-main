<?php
include_once 'model/api.modelo.php';
// if($_SESSION['Moneda']=='Pesos'){
//     $Moneda = 1;
// }else{
//     $Moneda = 1/$_SESSION['TipoCambio'];
// }
class ControladorProductos extends apiModel{

    function dataProductosTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            // $query= 'SELECT * FROM '.$tabla;
            $query= 'SELECT p.*, uni.Nombre AS Unidad FROM '.$tabla.' p
            JOIN tipounidad uni ON p.tipoUnidad = uni.Id';
            $resp= $this->getAllTable($query);
            
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                if($datosBD['Formulacion']==0){
                    $datosBD['Formulacion']="<i class='bx bx-x'></i>";
                }else{
                    $datosBD['Formulacion']="<i class='bx bx-check' ></i>";
                }
                if($datosBD['Hazmat']==0){
                    $datosBD['Hazmat']="<i class='bx bx-x'></i>";
                }else{
                    $datosBD['Hazmat']="<i class='bx bx-check' ></i>";
                }
                if($datosBD['Status']==1){
                    $buttonStatus = " <a type='button'  class='dropdown-item btnDeshabilitarTabla' attr-status='" . $datosBD["Status"] . "' id='de" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Deshabilitar</a>";
                }else{
                    $buttonStatus = " <a type='button'  class='dropdown-item btnHabilitarTabla' attr-status='" . $datosBD["Status"] . "' id='de" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Habilitar</a>";
                    
                }
               
                if($_SESSION['Rol']['prductoU']==1){
                    $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                                    
                                ".$buttonStatus."
                                <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Editar</a>
                                <a type='button'  class='dropdown-item btnEvidenciasTabla'  id='ev" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Hoja de seguridad</a>
    
                                <a type='button'  class='dropdown-item btnView' id='vi".$datosBD["Id"]."'   name='" . $datosBD["Nombre"] . "' >Ver</a>

                                
                            </div>
                    </div>";
                }else{
                    $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                                    
                                <a type='button'  class='dropdown-item btnView'   id='vi" . $datosBD["Id"] . "' name='" . $datosBD["Nombre"] . "' >Ver</a>
                               

                            </div>
                    </div>";
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

    function getDataProductos()
    {

        $query = "SELECT pd.Id, pd.Nombre, iv.Total, iv.PrecioLitros FROM productos pd INNER JOIN inventario iv ON iv.Id_producto = pd.Id";

        $resp = $this->getAllTable($query);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {

            $data[] = $datosBD;
        }

        $success = true;
        $msj = 'Exito en consulta de datos';
        $data = $data;
        return array("success" => $success, "data" => $data, "message" => $msj);
    }


    function dataProductos($tabla,$status){

        return $this->getDataProductos();

        $success=false;
        $data="Error";
        if(!empty($tabla)){
            if($status==2){
                $query= 'SELECT * FROM '.$tabla.' WHERE Status= 1 AND tipoUnidad=2';
            } elseif($status==3){
                $query= 'SELECT * FROM '.$tabla;
            }else{
                $query= 'SELECT * FROM '.$tabla.' WHERE Formulacion='.$status.' AND Status= 1';
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
    function dataProductosId($tabla,$Id){
        $success=false;
        $data="Error";
        $items=null;
        if(!empty($tabla)){
            $query= 'SELECT * FROM '.$tabla.' WHERE Id='.$Id;
            $resp= $this->getAllTable($query);
            $data=[];
            if($tabla=='archivosproductos'){  
                while($dataDB = $resp->fetch_assoc()){
                    // print_r(__DIR__);
                    // print_r("<br>");
                    // // print_r(__DIR__);
                    /////////////////////////////////////////////////////////////////////////////////////
                    ////////////De esta forma se hace el copiado de url en produccion.//////////////////
                    // $host="visionremota.com.mx";
                    // $urlEvidencias=explode('api',__DIR__)[0];
                    // $urlEvidencias = explode('public_html',$urlEvidencias)[1];
                    // $dataDB['url']=$host.$urlEvidencias;
                    ///////////////////////////////////////////////////////////////////////////////////
                    $urlEvidencias=explode('api',__DIR__)[0];
                    // print_r($urlEvidencias);
                    $dataDB['url']=$urlEvidencias;
                    
                    $data[]=$dataDB;
                }
            }
            if($tabla=='productos'){
                $data=$resp->fetch_assoc();
                $sqlItems = 'SELECT pf.*, pro.Nombre FROM productoformulado pf 
                JOIN productos pro ON pf.IdContenido=pro.Id
                WHERE pf.IdProducto='.$data['Id'];
                // $sqlItems = 'SELECT * FROM productoformulado WHERE IdProducto='.$data['Id'];
                // print_r($sqlItems);
                $respItems= $this->getAllTable($sqlItems);
                $items= [];
                while($itemsDB = $respItems->fetch_assoc()){
                    $items[]=$itemsDB;
                }
            }
            
            if ($resp==true) {
                if(empty($data)){
                    $data=' ';
                }
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj,"items"=>$items);
    
        return $mensaje;

    }
    function createProducto($dataProducto,$tabla,$Items){
        $success=false;
        $data="Error";
        if(is_array($dataProducto)&& isset($tabla)){

                $Nombre = $dataProducto['Nombre'];
                // $Densidad = $dataProducto['Densidad'];
                $Densidad = preg_replace('/[^0-9.]/', '', $dataProducto['Densidad']);
                $Color = $dataProducto['Color']?$dataProducto['Color']:'';
                $Hazmat = $dataProducto['Hazmat'];
                $Marca = $dataProducto['Marca']?$dataProducto['Marca']:'';
                if(empty($dataProducto['Concentracion'])){
                    $Concentracion=0;
                }else{
                    $Concentracion = preg_replace('/[^0-9.]/', '',$dataProducto['Concentracion']);

                }
                $Uso = $dataProducto['Uso']?$dataProducto['Uso']:'';
                $tipoUnidad = $dataProducto['tipoUnidad'];
                $CAS = $dataProducto['CAS']?$dataProducto['CAS']:0;
                $UN = $dataProducto['UN']?$dataProducto['UN']:0;

                $Reactividad = $dataProducto['Reactividad']?$dataProducto['Reactividad']:0;
                $Corrosividad = $dataProducto['Corrosividad']?$dataProducto['Corrosividad']:'';
                $Flameabilidad = $dataProducto['Flameabilidad']?$dataProducto['Flameabilidad']:0;
                $Toxicidad = $dataProducto['Toxicidad']?$dataProducto['Toxicidad']:0;
                $Formulacion = 0;
                //// SE INSERTA CON STATUS 0 HASTA QUE LA FORMULACION SE CIERRE
                $query= 'INSERT INTO '.$tabla.' (Nombre, Densidad, Color, Hazmat, Marca, Concentracion, Uso, Formulacion, tipoUnidad, CAS, UN,Flameabilidad, Reactividad, Toxicidad, Corrosividad) 
                    VALUES ("'.$Nombre.'","'.$Densidad.'","'.$Color.'",'.$Hazmat.',"'.$Marca.'","'.$Concentracion.'","'.$Uso.'",'.$Formulacion.','.$tipoUnidad.','.$CAS.','.$UN.','.$Flameabilidad.','.$Reactividad.','.$Toxicidad.',"'.$Corrosividad.'")';

                //    print_r($query);

            $resp= $this->getAllTableLastID($query);
            $Id=$resp['last_id'];
            if($dataProducto['Formulacion']==1){
                foreach($Items as $valueItem){
                    $IdGrupo = $Id;
                    $IdContenido = $valueItem['Producto'];
                    $Version = 1;
                    $Nombre = $dataProducto['Nombre']."_Ver_1";
                    $Porcentaje = $valueItem['Porcentaje'];
                    $query= 'INSERT INTO productoformulado (Grupo, IdProducto,IdContenido, Version, NombreVersion, Porcentaje) 
                    VALUES ('.$IdGrupo.','.$IdGrupo.','.$IdContenido.','.$Version.',"'.$Nombre.'",'.$Porcentaje.')';
                    // print_r($query);
                    $resp= $this->getAllTable($query);
                }
            }else{
                $Version = 1;
                $Nombre = $dataProducto['Nombre']."_Ver_1";
                $Porcentaje = 100;
                $query= 'INSERT INTO productoformulado (Grupo, IdProducto,IdContenido, Version, NombreVersion, Porcentaje) 
                    VALUES ('.$Id.','.$Id.','.$Id.','.$Version.',"'.$Nombre.'",'.$Porcentaje.')';
                    print_r($query);
                    $resp= $this->getAllTable($query);
            }
            if ($resp==true) {
                $data=[];
                $data['Id']=$Id;
                $data['Nombre']=$dataProducto['Nombre'];
                $success=true;
                $msj='Producto agregado correctamente';
                $data=$data;
            }else{
                $msj='Error al agregar un producto: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function putProductos($dataProducto,$tabla,$id,$dataItems){
        $success=false;
        $data="Error";
        $actualizacion=0;
        if(is_array($dataProducto)&& isset($tabla)){
            $dataProducto['Densidad']=preg_replace('/[^0-9.]/', '', $dataProducto['Densidad']);
            $dataProducto['Concentracion']=preg_replace('/[^0-9.]/', '', $dataProducto['Concentracion']);
            $valuecoma="";
            foreach ($dataProducto as $key => $value) {
                if(!is_array($value)){
                $valuecoma.="`".$key."`='".$value."',";
                }
        
            }
            $values = rtrim($valuecoma,",");
            $query = "UPDATE ".$tabla." SET $values WHERE Id =$id";
            $resp = $this->updateTable($query);
            if($resp==1){
            $actualizacion=1;
            }

            foreach ($dataItems as $key => $valueItems) {

                $idItems=$valueItems['Id'];
                $Porcentaje=$valueItems['Porcentaje'];
                $IdProducto=$valueItems['Producto'];
                $Nombre = $dataProducto['Nombre'];
                if($idItems==NULL){
                    $Version = 1;
                    $sqlItems='productoformulado (IdCompuesto, IdProducto, Version, NombreVersion, Porcentaje) 
                    VALUES ('.$id.','.$IdProducto.','.$Version.',"'.$Nombre.'",'.$Porcentaje.')';
                    $resultInsert = $this->setTableGrl($sqlItems);
                    if($resultInsert ==1){
                        $actualizacion=1;
                    }
                }else{
                    $queryItems = "productoformulado SET NombreVersion= '$Nombre' WHERE Id =$idItems";
                    $respItems = $this->getUpdateResult($queryItems);
                    if($respItems['affected']==true){
                        $actualizacion=1;
                    }
                }
               
            }

            if ($actualizacion==1) {
                $success=true;
                $msj='Producto actualizado correctamente';
                $data="Correcto";
            }elseif($actualizacion==0){
                $msj='No se actualizaron Productos';
            }else{
                $msj='Error al actualizar productos';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }
    function putProductosStatus($tabla,$id,$status){
        $success=false;
        $data="Error";
        if(isset($id)&& isset($tabla)&& isset($status)){

            $query = "UPDATE ".$tabla." SET Status = $status WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp==true) {
                $success=true;
                $msj='Producto actualizado correctamente';
                $data="Correcto";
            }else{
                $msj='Error al agregar un producto: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }
    function altaFiles($id,$tabla,$archivos,$user){
        $success=false;
        $data="Error";
        if(isset($tabla)&& isset($archivos)&& isset($id)&& isset($user)){ 

                $respId = $this->nextID($tabla);
                if($respId['success']==false){
                    $msj = "Error al buscar Id en el controlador";
                }else{
                    $lastId = $respId["Auto_increment"];
                    foreach ($_FILES as $key => $value) {
                        $cantArchivos= count($value["name"]); 
                        if($cantArchivos>=11){
                            $msj = "Limite de archivos recibidos";
                        }else{
                        foreach ($value["name"] as $key2 => $name) {                      
                            $ext = pathinfo($name, PATHINFO_EXTENSION);
                            $archivo = $id . '_' .$lastId. '_evidencia.' . $ext;                       
                            $carpetaSubir = '../Data/EvidenciasProductos/' . basename($archivo);
                            $tmstAltaRegistro = date("Y-m-d H:i:s");
                            $epochAltaEv=time() * 1000;
                            $sqlArchivos=$tabla.' (Id, Tipo, NombreArchivo, Extension,tmstAltaRegistro, epochRegistro,Usuario) 
                            VALUES ('.$id.',"'.$value["type"][$key2].'","'.$archivo.'","'.$ext.'","'.$tmstAltaRegistro.'","'.$epochAltaEv.'","'.$user.'")';
                            $resultInsert = $this->setTableGrl($sqlArchivos);
                            if($resultInsert ==1){
                                if (move_uploaded_file($value["tmp_name"][$key2], $carpetaSubir)) {
                                    $success=true;
                                    $msj='El Archivo se subio Correctamente';
                                    $data="Correcto";
                                }else{
                                    $msj = "No se pudo subir". $name;
                                }
                            }else{
                                $msj = "Error al dar de alta el producto";
                            }
                        
                                $lastId++;
                            }
                        }
                    }
                }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }  
    function contador($tabla,$id,$cantidad){
        $success=false;
        $data="Error";
        if(isset($tabla) && isset($id) && isset($cantidad)){
            $sql = "SELECT COUNT(Id) AS Contador FROM $tabla WHERE Id=".$id;
            $result = $this->getAllTable($sql);
    
            $contador = $result->fetch_all(MYSQLI_ASSOC)[0]['Contador'];
            $total=$cantidad+$contador;
            if ($total<=10) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$cantidad;
            }else{
                $msj='El limite de archivos por producto supera los 10 ';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }


    
    function dataExcelProductos($tabla)
    {

        $arrayQuery=$this->getDataTableProductos($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];
                $newRow['Nombre'] = $row['Nombre'];
                $newRow['Marca'] = $row['Marca'];
                $newRow['Color'] = $row['Color'];

                $newRow['Uso'] = $row['Uso'];

                $newRow['Concentración'] = $row['Concentracion'];
                $newRow['Formulación'] = $row['Formulacion']?'✓':'X';
                $newRow['Hazmat'] = $row['Hazmat']?'✓':'X';
                $newRow['Status'] =$row['Status']?'✓':'X';
                
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

    function getDataTableProductos($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT * FROM '.$tabla;
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {

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

    
}
