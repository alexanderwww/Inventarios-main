<?php
include_once 'model/api.modelo.php';
if($_SESSION['Moneda']=='Pesos'){
    $Moneda = 1;
}else{
    $Moneda = 1/$_SESSION['TC'];
}

require '../requerimientos/vendorComposer/vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;

class ControladorCompras extends apiModel{

    // function dataComprasTabla($tabla){

    //     $success=false;
    //     $data="Error";
    //     if(!empty($tabla)){

    //         $query = 'SELECT com.*, pro.Nombre AS NombreProveedor, pdt.Nombre AS NombreProducto, mon.Nombre AS NombreMoneda 
    //         FROM compras com
    //         JOIN proveedores pro ON pro.Id= com.IdProveedor
    //         JOIN productos pdt ON pdt.Id=com.IdProducto
    //         JOIN moneda mon ON mon.Id=com.Moneda';
    //         $resp= $this->getAllTable($query);
    //         $data=[];
    //         while ($datosBD = $resp->fetch_assoc()) {

    //             $datosBD['Id']=(int)$datosBD['Id'];
    //             switch ($datosBD['Status']) {
    //                 case 0:

    //                     $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
    //                     $datosBD['Status']="Cancelado";
    //                     break;
    //                     case 1:
    //                         $datosBD['Status']="Habilitado";
    //                         if($_SESSION['Rol']['comprasU']==1){
    //                             $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>";
        
    //                         }else{
    //                         $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
        
    //                             $datosBD["acciones"] ="Sin Acciones";
    //                             $datosBD["flag"] =1;
    //                         }
    //                         break;
    //                         case 2:
    //                             $datosBD['Status']="Pendiente";
    //                             if($_SESSION['Rol']['comprasU']==1){
    //                                 $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>
    //                                 <a type='button'  class='dropdown-item btnAprobarTabla'   id='ap" . $datosBD["Id"] . "'  >Cerrar</a>
    //                                 <a type='button'  class='dropdown-item btnCancelarTabla'   id='ca" . $datosBD["Id"] . "'  >Cancelar</a>";
            
    //                             }else{
    //                             $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
            
    //                                 $datosBD["acciones"] ="Sin Acciones";
    //                                 $datosBD["flag"] =1;
    //                             }
    //                             break;
                    
    //                 default:
    //                     # code...
    //                     break;
    //             }
    //             // if($datosBD['Status']==0){
    //             //     $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
    //             //     $datosBD['Status']="Cancelado";
    //             // }else{
                    
                  
    //             //     $datosBD['Status']="Habilitado";
    //             //     if($_SESSION['Rol']['comprasU']==1){
    //             //         $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>
    //             //         <a type='button'  class='dropdown-item btnCancelarTabla'   id='ca" . $datosBD["Id"] . "'  >Cancelar</a>";

    //             //     }else{
    //             //     $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";

    //             //         $datosBD["acciones"] ="Sin Acciones";
    //             //         $datosBD["flag"] =1;
    //             //     }
                   
    //             // }
    //             $datosBD["acciones"] ="
    //                     <div class='btn-group'>
    //                             <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
    //                             <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                 
    //                                 <a type='button'  class='dropdown-item btnPDFDownload'   id='pd" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar PDF</a>
    //                                 <a type='button'  class='dropdown-item btnPDFView'   id='pw" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver PDF</a>

    //                                 ".$btns."
        
        
    //                             </div>
    //                     </div>";
               
    //             $datosBD['PrecioLitro']=$datosBD['PrecioLitro']*$GLOBALS["Moneda"];
    //             $data[] = $datosBD;

    //         }
    //         if ($resp==true) {
    //             $success=true;
    //             $msj='Exito en consulta de datos';
    //             $data=$data;
    //         }else{
    //             $msj='Error al consultar la tabla: '.$tabla;
    //         }
    //     }else{
    //         $msj = "No se recibieron datos";
    //     }
    //     $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
    //     return $mensaje;

    // }
    function dataComprasTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){

            $query = 'SELECT com.*, pro.Nombre AS NombreProveedor, pdt.Nombre AS NombreProducto, mon.Nombre AS NombreMoneda 
            FROM compras com
            JOIN proveedores pro ON pro.Id= com.IdProveedor
            JOIN productos pdt ON pdt.Id=com.IdProducto
            JOIN moneda mon ON mon.Id=com.Moneda';
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {

                $datosBD['Id']=(int)$datosBD['Id'];
                switch ($datosBD['Status']) {
                    case 0:

                        $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
                        $datosBD['Status']="Cancelado";
                        break;
                        case 1:
                            $datosBD['Status']="Habilitado";
                            if($_SESSION['Rol']['comprasU']==1){
                                $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>";
        
                            }else{
                            $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
        
                                $datosBD["acciones"] ="Sin Acciones";
                                $datosBD["flag"] =1;
                            }
                            break;
                            case 2:
                                $datosBD['Status']="Pendiente";
                                if($_SESSION['Rol']['comprasU']==1){
                                    $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>
                                    <a type='button'  class='dropdown-item btnAprobarTabla'   id='ap" . $datosBD["Id"] . "'  >Cerrar</a>
                                    <a type='button'  class='dropdown-item btnCancelarTabla'   id='ca" . $datosBD["Id"] . "'  >Cancelar</a>";
            
                                }else{
                                $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
            
                                    $datosBD["acciones"] ="Sin Acciones";
                                    $datosBD["flag"] =1;
                                }
                                break;
                    
                    default:
                        # code...
                        break;
                }
                $datosBD["acciones"] ="
                        <div class='btn-group'>
                                <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                                <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                <a type='button'  class='dropdown-item btnPDFDownload'   id='pd" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar PDF</a>
                                <a type='button'  class='dropdown-item btnPDFView'   id='pw" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver PDF</a>
                                    ".$btns."
        
        
                                </div>
                        </div>";
               
                $datosBD['PrecioLitro']=$datosBD['PrecioLitro']*$GLOBALS["Moneda"];
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
    function dataProveedor($tabla,$status){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT Id, Nombre FROM '.$tabla.' WHERE Status= 1';
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
    function dataComprasId($tabla,$Id){
        $success=false;
        $data="Error";
        if(!empty($tabla)){

            $query = 'SELECT com.*, pro.Nombre AS NombreProveedor, pdt.Nombre AS NombreProducto, mon.Nombre AS NombreMoneda , usr.Name AS NombreUsuario,
                    CASE 
            WHEN com.moneda = 2
            THEN (com.PrecioLitro/com.TipoCambio)
            ELSE com.PrecioLitro 
            END AS totales
            FROM '.$tabla.' com
            JOIN proveedores pro ON pro.Id= com.IdProveedor
            JOIN productos pdt ON pdt.Id=com.IdProducto
            JOIN moneda mon ON mon.Id=com.Moneda 
            JOIN user_accounts usr ON usr.Id = com.IdUser
            WHERE com.Id = '.$Id;
            // print_r($query);
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
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj,"items"=> array());
    
        return $mensaje;

    }
    function createCompras($dataCompras,$tabla){


        if(is_array($dataCompras) && isset($tabla)){
                $idUser = $_SESSION['IdUser'];
                $idProducto = $dataCompras['Producto'];
                $Entrada = $dataCompras['Entrada'];
                $IdProveedor=$dataCompras['Proveedor'];
                $PrecioLitro = (float) $dataCompras['Precio'];
                $Moneda = $dataCompras['Moneda'];
                $TipoCambio = (float) $dataCompras['TpCambio'];
                $NoFactura = $dataCompras['NoFactura'];
                $Observaciones = $dataCompras['Observaciones'];
                $totalDespues=['Despues'];
                if($Moneda==2){
                    $PrecioLitro=$TipoCambio*$PrecioLitro;
                }

                    $query= 'INSERT INTO '.$tabla.' (IdProducto,IdUser,Entrada, IdProveedor, PrecioLitro, Moneda, TipoCambio, NoFactura, Observaciones) 
                    VALUES ('.$idProducto.','.$idUser.','.$Entrada.','.$IdProveedor.','.$PrecioLitro.','.$Moneda.','.$TipoCambio.','.$NoFactura.',"'.$Observaciones.'")';

                    $resp = $this->getAllTableLastID($query);
                    if($resp){

                            $success=true;
                            $msj='Compra agregado correctamente';
                            $data=$resp['last_id'];
                    }else{
                        $success=false;
                        $msj='Error al generar ajuste';
                        $data="Error";
                    }

                $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
                return $mensaje;
            
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
    function putComprasStatus($tabla,$id,$status){
        $success=false;
        $data="Error";
        if(isset($id)&& isset($tabla)&& isset($status)){

            $query = "UPDATE ".$tabla." SET Status = $status WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp==true) {
                $success=true;
                $msj='La Compra cambio de estatus correctamente';
                $data="Correcto";
            }else{
                $msj='Error al cambiar de estatus en compra';
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

    function dataExcelCompras($tabla)
    {

        $arrayQuery=$this->getDataCompras($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];

                $newRow['Fecha'] = $this->formatoFechaVista($row['Fecha']);
                $newRow['Producto'] = $row['NombreProducto'];
                $newRow['Entrada'] = $row['Entrada'];
                $newRow['Precio Litro'] = $row['PrecioLitro'];
                $newRow['Moneda'] = $row['NombreMoneda'];
                $newRow['Tipo de Cambio'] = $row['TipoCambio'];
                $newRow['No. Factura'] = $row['NoFactura'];
                $newRow['Observaciones'] = $row['Observaciones'];
                $newRow['Estatus'] = $row['Status']?'Habilitado':'Cancelado';

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

    function getDataCompras($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){

            $query = 'SELECT com.*, pro.Nombre AS NombreProveedor, pdt.Nombre AS NombreProducto, mon.Nombre AS NombreMoneda 
            FROM compras com
            JOIN proveedores pro ON pro.Id= com.IdProveedor
            JOIN productos pdt ON pdt.Id=com.IdProducto
            JOIN moneda mon ON mon.Id=com.Moneda';
            
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

    function formatoFechaVista($date){
        // $date = '2022-12-16 10:07:21';
        $timestamp = strtotime($date);
        return date('d/m/Y', $timestamp);
        // echo $new_date;  // Imprime "16/12/2022"
    }



    // ---------------------------------------------------PDF 

    function createPDF($id){
        
        $data=$this->getInfoProducto($id);

        $arrayData=[];

        $fechaEmitidad=explode(' ',$data['Fecha']);

        $arrayData['descriptionOrder']=[
                'invoice'=>$data['NoFactura'],
                // 'inssuedDate'=>'03/02/2023 - 03:38 PM',
                'inssuedDate'=>$this->formatoFechaVista($fechaEmitidad[0]).' '.$fechaEmitidad[1],

                'agent'=>$data['NombreUsuario'],

                'currency'=>$data['NombreMoneda'],
                'rfc'=>'NO almacenado',

                'direction'=>'no almacenado',
                'phone'=>'no almacenado'
        ];


        $dataProveedor=$this->getDataProveedor($data['IdProveedor']);


        $arrayData['provider']=[
            'name'=>$dataProveedor['Nombre'],
            'direction'=>$dataProveedor['CalleProveedor']. ' Ext: '.$dataProveedor['Ext'].' Int: NoAlmacenado'.' C.P:'.$dataProveedor['CPProveedor'],
            'rfc'=>$dataProveedor['RFC'],
            'attention'=>'',
            'phone'=>$dataProveedor['Telefono'],
            'mail'=>$dataProveedor['CorreoElectronico']
        ];
    
        
        $totalCompra=floatval($data['PrecioLitro']) * floatval($data['Entrada']);

        $arrayData['purchaseOrder']=[
            [
                'noParte' => $data['IdProducto'],
                'description' => $data['NombreProducto'],
                'lot' => floatval($data['Entrada']),
                'unitMedida' => $data['TipoUnit'],
                'unitPrice' => floatval($data['PrecioLitro']),
                'total' => $totalCompra
            ],

        ];


        $ivaCompra=0;

        $arrayData['balance']=[
            'currency'=>$data['NombreMoneda'],
            'subtotal'=>$totalCompra,
            'iva'=>$ivaCompra,
            'total'=>$totalCompra+$ivaCompra,
            'observations'=>$data['Observaciones'],
            'textTotal'=>$this->writeToNumber($totalCompra,$data['NombreMoneda'])
        ];

        $arrayData['namePDF']='OrdenDeCompra-'.date('d-m-Y', time());

        return array("success" => true,"data"=>$arrayData, "message" =>'Consulta Exitosamente',"items"=>[]);

    }



    function writeToNumber($number, $currency)
    {
        $formatter = new NumeroALetras();
        $decimals = 2;
        return $formatter->toMoney($number, $decimals, $currency, 'CENTAVOS');
    }


    function getDataProveedor($id){

        $query="SELECT * FROM proveedores WHERE id=".$id;

        $resp= $this->getAllTable($query);

        $response=$resp->fetch_assoc();

        return $response;
    }

    function getInfoProducto($idCompra){
        $query="SELECT tu.Nombre AS TipoUnit, com.*, pro.Nombre AS NombreProveedor, pdt.Nombre AS NombreProducto, mon.Nombre AS NombreMoneda , usr.Name AS NombreUsuario
        FROM compras com
        JOIN proveedores pro ON pro.Id= com.IdProveedor
        JOIN productos pdt ON pdt.Id=com.IdProducto
        JOIN moneda mon ON mon.Id=com.Moneda 
        JOIN user_accounts usr ON usr.Id = com.IdUser
        JOIN tipounidad tu ON tu.Id=pdt.tipoUnidad
        WHERE com.Id =".$idCompra;

        $resp= $this->getAllTable($query);

        $response=$resp->fetch_assoc();

        return $response;

    }

}
?>