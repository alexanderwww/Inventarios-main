<?php
include_once 'model/api.modelo.php';
if($_SESSION['Moneda']=='Pesos'){
    $Moneda = 1;
}else{
    $Moneda = 1/$_SESSION['TC'];
}
class ControladorInventario extends apiModel{

    function dataAjustesTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT a.*,pro.Nombre, tp.Nombre AS tpNombre FROM '.$tabla.' a
            INNER JOIN productos pro ON pro.Id = a.Id_producto
            JOIN tipounidad tp ON pro.tipoUnidad = tp.Id';
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                $datosBD['Id']=(int)$datosBD['Id'];
                $datosBD['TotalProducto'] = $datosBD['Total'] * $datosBD['PrecioLitros'];
                $datosBD['TotalProducto']=$datosBD['TotalProducto']*$GLOBALS["Moneda"];
                $datosBD['PrecioLitros']=$datosBD['PrecioLitros']*$GLOBALS["Moneda"];
                $data[] = $datosBD;
            //     print_r($data);
            //  die;
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
    function dataProductos($tabla,$status){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT Id, Nombre FROM '.$tabla.' WHERE Formulacion='.$status.' AND Status= 1';
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
        if(!empty($tabla)){
            $query= 'SELECT i.Total FROM '.$tabla.' p 
            INNER JOIN inventario i ON i.Id_producto = p.id
            WHERE p.Id = '.$Id;
            $resp= $this->getAllTable($query);
            $data=[];
            $data=$resp->fetch_assoc();
            
            // print_r($data);
            // die;
            // $sqlItems = 'SELECT * FROM productoformulado WHERE IdCompuesto='.$data['Id'];
            // $respItems= $this->getAllTable($sqlItems);
            // $items= [];
            // while($itemsDB = $respItems->fetch_assoc()){
            //     $items[]=$itemsDB;
            // }
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
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj,"items"=> array());
    
        return $mensaje;

    }
    function createAjuste($dataProducto,$tabla){

        // $this->updateInventario(1,10);
        // die;
        if(is_array($dataProducto) && isset($tabla)){
                $idUser = $_SESSION['IdUser'];
                $idProducto = $dataProducto['ProductoPrimario_example'];
                $observaciones = '';
                $existente = intval($dataProducto['Existente']);
                $entrada = intval($dataProducto['Entrada']);
                $salida = intval($dataProducto['Salida']);
                $totalDespues = $existente + $entrada - $salida;
                
                if($dataProducto['Despues'] == $totalDespues){
                    $query= 'INSERT INTO '.$tabla.' (Id_producto,Id_user, Entrada, Salida, Existencia, ExistenciaDespues, Observaciones) 
                    VALUES ('.$idProducto.','.$idUser.','.$entrada.','.$salida.','.$existente.','.$totalDespues.',"'.$observaciones.'")';
                    $resp = $this->getAllTableLastID($query);
                    if($resp){
                            $this->updateInventario($idProducto,$totalDespues);
                            $success=true;
                            $msj='Ajuste agregado correctamente';
                            $data="Correcto";
                    }else{
                        $success=false;
                        $msj='Error al generar ajuste';
                        $data="Error";
                    }
                }else{
                    $success=false;
                    $msj='Error en datos';
                    $data="Error";
                }
                $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
                return $mensaje;
                // $Nombre = $dataProducto['Nombre'];
                // $Densidad = $dataProducto['Densidad'];
                // $Color = $dataProducto['Color'];
                // $Hazmat = $dataProducto['Hazmat'];
                // $Marca = $dataProducto['Marca'];
                // $Concentracion = $dataProducto['Concentracion'];
                // $Uso = $dataProducto['Uso'];
                // $Formulacion = $dataProducto['Formulacion'];

        //     $query= 'INSERT INTO '.$tabla.' (Nombre, Densidad, Color, Hazmat, Marca, Concentracion, Uso, Formulacion) 
        //             VALUES ("'.$Nombre.'",'.$Densidad.',"'.$Color.'",'.$Hazmat.',"'.$Marca.'",'.$Concentracion.',"'.$Uso.'",'.$Formulacion.')';
        //     $resp= $this->getAllTableLastID($query);

        //     if ($resp==true) {
        //         $success=true;
        //         $msj='Producto agregado correctamente';
        //         $data="Correcto";
        //     }else{
        //         $msj='Error al agregar un producto: '.$tabla;
        //     }
        // }else{
        //     $msj = "No se recibieron datos";
        // }
        // $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        // return $mensaje;
        }
    }
    public function updateInventario($idProducto,$total)
    {
        if(isset($idProducto) && isset($total)){
            $sql = 'UPDATE inventario SET Total = '.$total.' WHERE Id_producto = '.$idProducto;
            $this->updateTable($sql);
        }
    }
    function putProductos($dataProducto,$tabla,$id,$dataItems){
        $success=false;
        $data="Error";
        $actualizacion=0;
        if(is_array($dataProducto)&& isset($tabla)){

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
                    $queryItems = "productoformulado SET Porcentaje= $Porcentaje, IdProducto=$IdProducto, NombreVersion= '$Nombre' WHERE Id =$idItems";
                    $respItems = $this->getUpdateResult($queryItems);
                    if($respItems['affected']==true){
                        $actualizacion=1;
                    }
                }
               
            }
       
            // }
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
    function altaFiles($tabla,$id,$status){
        $success=false;
        $data="Error";
        // if(isset($id)&& isset($tabla)&& isset($status)){

        //     $query = "UPDATE ".$tabla." SET Status = $status WHERE id =$id";
        //     $resp = $this->updateTable($query);
        //     if ($resp==true) {
        //         $success=true;
        
        //         $msj='Producto actualizado correctamente';
        //         $data="Correcto";
        //     }else{
        //         $msj='Error al agregar un producto: '.$tabla;
        //     }
        // }else{
            $msj = "No se recibieron datos";
        // }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }

    function dataExcelInventario($tabla)
    {

        $arrayQuery=$this->dataAjustesTabla($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];

                $newRow['Nombre'] = $row['Nombre'];
                $newRow['Total Litros'] = $row['Total'];
                $newRow['Precio por litros'] = $row['PrecioLitros'];
                $newRow['Valor'] = $row['TotalProducto'];

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
?>