<?php
include_once 'model/api.modelo.php';


// if($_SESSION['Moneda']=='Pesos'){
//     $Moneda = 1;
// }else{
//     $Moneda = 1/$_SESSION['TipoCambio'];
// }
class ControladorAjustes extends apiModel{

    function dataAjustesTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT a.*,pro.Nombre FROM '.$tabla.' a
            INNER JOIN productos pro ON pro.Id = a.Id_producto';
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                $datosBD['Id']=(int)$datosBD['Id'];
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
            $query= 'SELECT i.Total, i.PrecioLitros FROM '.$tabla.' p 
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
        $success=false;
        $data="Error";
        if($_SESSION['Rol']['ajustesC']==1){
        if(is_array($dataProducto) && isset($tabla)){
                $idUser = $_SESSION['IdUser'];
                $idProducto = $dataProducto['ProductoPrimario_example'];
                $observaciones = $dataProducto['Observaciones'];
                $existente = floatval($dataProducto['Existente']);
                $entrada = floatval($dataProducto['Entrada']);
                $salida = floatval($dataProducto['Salida']);
                $totalDespues = $existente + $entrada - $salida;
                $precio=$dataProducto['Precio'];
                $precioInsert=$dataProducto['Precio'];
                $precioActual=$dataProducto['PrecioActual'];
                // print_r($dataProducto['Despues']."--".$totalDespues);
                if($dataProducto['Despues'] == $totalDespues){
                        if(empty($precioInsert)){
                            $precioInsert=$precioActual;
                        }
                    $query= 'INSERT INTO '.$tabla.' (Id_producto,Id_user, Entrada, Salida, Existencia, ExistenciaDespues, Observaciones,Precio,PrecioDespues) 
                    VALUES ('.$idProducto.','.$idUser.','.$entrada.','.$salida.','.$existente.','.$totalDespues.',"'.$observaciones.'","'.$precioActual.'","'.$precioInsert.'")';
                    $resp = $this->getAllTableLastID($query);
                    if($resp){
                            $this->updateInventario($idProducto,$totalDespues,$precio);
                            $sql ='SELECT Id, Nombre FROM productos WHERE Id='.$idProducto;
                            $nameId = $this->getAllTable($sql);
                            $data=$nameId->fetch_assoc();
                            $success=true;
                            $msj='Ajuste al producto '.$data['Nombre'].' agregado correctamente';
                            $data=$data;
                    }else{
                        $msj='Error al generar ajuste';
                    }
                }else{
                    $msj='Error en datos';
                }
        }else{
            $msj = "No se recibieron datos";
        }
    }else{
        $msj = "Acceso No Permitido";
    }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
        return $mensaje;
    }

    public function updateInventario($idProducto,$total,$precio)
    {
        if(isset($idProducto) && isset($total)){
            if(empty($precio)){
                $sql = 'UPDATE inventario SET Total = '.$total.' WHERE Id_producto = '.$idProducto;
            }else{
                $sql = 'UPDATE inventario SET Total = '.$total.', PrecioLitros= '.$precio.' WHERE Id_producto = '.$idProducto;

            }
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

    function dataExcelAjustes($tabla)
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
                $newRow['Existencia'] = $row['Existencia'];
                $newRow['Entrada'] = $row['Entrada'];
                $newRow['Salida'] = $row['Salida'];
                $newRow['ExistenciaDespues'] = $row['ExistenciaDespues'];
                $newRow['Fecha'] = $this->formatoFechaVista($row['Fecha']);
                $newRow['Observaciones'] = $row['Observaciones'];

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