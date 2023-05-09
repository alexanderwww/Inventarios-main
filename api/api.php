<?php
session_start();
header('Content-Type: application/json');
include "controlador/api.controlador.php";
include "controlador/productos.controlador.php";
include "controlador/proveedores.controlador.php";
include "controlador/usuarios.controlador.php";
include "controlador/bitacora.controlador.php";
include "controlador/odt.controlador.php";
include "controlador/ajustes.controlador.php";
include "controlador/odv.controlador.php";
include "controlador/compras.controlador.php";
include "controlador/inventario.controlador.php";
include "controlador/clientes.controlador.php";
include "controlador/factura.controlador.php";
include "controlador/cp.controlador.php";
include "controlador/cxc.controlador.php";
include "controlador/gastos.controlador.php";
include "controlador/nc.controlador.php";
include "controlador/cxp.controlador.php";



$ObjApi = new ControladorApi();
$ObjProductos = new ControladorProductos();
$ObjProveedores = new ControladorProveedores();
$ObjUsuarios = new ControladorUsuarios();
$ObjBitacora = new ControladorBitacora();
$ObjAjustes = new ControladorAjustes();
$ObjInventario = new ControladorInventario();
$ObjOdt = new ControladorOdt();
$ObjOdv = new ControladorOdv();
$ObjCompras = new ControladorCompras();
$ObjClientes = new ControladorClientes();
$Objfactura = new ControladorFactura();
$ObjCP = new ControladorCP();
$ObjCxC = new ControladorCxC();
$ObjGastos = new ControladorGastos();
$ObjNC = new ControladorNC();
$ObjCxP = new ControladorCxP();



$datos = json_decode(file_get_contents("php://input"), true);
$user=$_SESSION['IdUser'];
$userName=$_SESSION['Nombre'];
if (!empty($datos)) {
    $post = json_decode(file_get_contents("php://input"), true);
    $Accion = $post['Accion'];
}
if(!empty($_POST)) {
    $post= $_POST;
    $Accion = $post['Accion'];
}
if(!empty($_GET)) {
    $Accion = $_GET['Accion'];
    // print_r($_SERVER['REQUEST_METHOD'].' - '.$Accion);
// return;
}
if(!empty($_PUT)) {
    $Accion = $_PUT['Accion'];
}


if (!empty($Accion)){
    switch ($Accion) {
        case 'bitacora':
           
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){

                $resp=$ObjBitacora->bitacora($post['codigo'],$user,$userName,$post['comentario'],$post['modulo']);

                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){ 

                if(isset($_GET['getDataExcel'])){

                    $resp=$ObjBitacora->dataExcelBitacora($_GET['Tabla']);

                }else{

                    $resp=$ObjBitacora->dataBitacora();

                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj));
            return;

        break;
        case 'productos':
            $items = null;
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
                if(isset($_GET['Id']) && !isset($_GET['Cantidad'])){
                    $resp=$ObjProductos->dataProductosId($_GET['Tabla'],$_GET['Id']);
                    $items = $resp['items'];
                }elseif(isset($_GET['Select'])){

                    if($_GET['Select']=='cotizador'){
                        $resp=$ObjProductos->getDataProductos();
                    }else{
                        $resp=$ObjProductos->dataProductos($_GET['Tabla'],$_GET['Select']);
                    }

                }elseif(isset($_GET['Cantidad'])){
                    $resp=$ObjProductos->contador($_GET['Tabla'],$_GET['Id'],$_GET['Cantidad']);
                }
                elseif(isset($_GET['getDataExcel'])){
                    $resp=$ObjProductos->dataExcelProductos($_GET['Tabla']);
                }
                else{
                    $resp=$ObjProductos->dataProductosTabla($_GET['Tabla']);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }
            elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
                $tabla =$post['Tabla'];
                if(!empty($_FILES)){
                    $id=$post['Id'];
                    $archivos = $_FILES;
                    $resp=$ObjProductos->altaFiles($id,$tabla,$archivos,$userName);
                }else{
                    $dataProducto=$post['Data'];
                    $Items=$post['Items'];
                    $resp=$ObjProductos->createProducto($dataProducto,$tabla,$Items);
                }
              
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
                $tabla =$post['Tabla'];
                $id =$post['Id'];
                if(isset($post['Status'])){
                    $resp=$ObjProductos->putProductosStatus($tabla,$id,$post['Status']);
                }else{
                $dataProducto=$post['Data'];
                $dataItems=$post['Items'];
                $resp=$ObjProductos->putProductos($dataProducto,$tabla,$id,$dataItems);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;

        break;
        case 'proveedores':
           
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
                if(isset($_GET['Id'])){
                    if(isset($_GET['Status'])){
                        $resp=$ObjProveedores->dataProveedoresIdStatus($_GET['Tabla'],$_GET['Id'],$_GET['Status']);
                    }else{
                        $resp=$ObjProveedores->dataProveedoresID($_GET['Tabla'],$_GET['Id']);
                    }
                }
                elseif(isset($_GET['getDataExcel'])){
                    $resp=$ObjProveedores->dataExcelProveedores($_GET['Tabla']);
                }
                else{
                    $resp=$ObjProveedores->dataProveedores($_GET['Tabla']);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];

            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
                $tabla =$post['Tabla'];
                $id =$post['Id'];
                if(isset($post['Status'])){
                    $resp=$ObjProveedores->putProveedoresStatus($tabla,$id,$post['Status']);
                }else{
                $dataProducto=$post['Data'];
                $dataItems=$post['Items'];
                // $resp=$ObjProveedores->putProveedores($dataProducto,$tabla,$id,$dataItems);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj));
            return;

        break;
        case 'usuarios':
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
                // print_r($_GET);
                // return;
                if(isset($_GET['Id'])){
                    $resp=$ObjUsuarios->dataUsuariosId($_GET['Tabla'],$_GET['Id']);
                }
                elseif(isset($_GET['Select'])){
                    $resp=$ObjUsuarios->dataUsuarios($_GET['Tabla'],$_GET['Select']);
                }
                elseif(isset($_GET['getDataExcel'])){
                    $resp=$ObjUsuarios->dataExcelUsuarios($_GET['Tabla']);
                }

                else{
                    $resp=$ObjUsuarios->dataUsuariosTabla($_GET['Tabla']);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];

            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj));
            return;
        break;
        // case 'odt':
        //     $items = null;
        //     if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
        //         if(isset($_GET['Id'])){
        //             if(isset($_GET['subTabla'])){
        //                 $resp=$ObjOdt->dataOdtIdStatus($_GET['Tabla'],$_GET['Id'], $_GET['subTabla']);
        //             }else{
        //                 $resp=$ObjOdt->dataOdtId($_GET['Tabla'],$_GET['Id']);
        //             }
        //             $items = $resp['items'];
        //         }elseif(isset($_GET['Select'])){
        //             $resp=$ObjOdt->dataOdt($_GET['Tabla'],$_GET['Select']);
        //             $items = $resp['items'];
        //         }elseif(isset($_GET['getDataExcel'])){

        //             $resp=$ObjOdt->dataExcelOdt($_GET['Tabla']);
        //         }
        //         else{
        //             $resp=$ObjOdt->dataOdtTabla($_GET['Tabla']);
        //         }
        //         $success=$resp['success'];
        //         $msj=$resp['message'];
        //         $data=$resp['data'];
        //     }
        //     elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
        //         $tabla =$post['Tabla'];
        //             $dataOdt=$post['Data'];
        //             $producto=$post['Producto'];
        //             // $userName=$post['Usuario'];
        //             $resp=$ObjOdt->createOdt($dataOdt,$tabla,$producto,$userName);
              
        //         $success=$resp['success'];
        //         $msj=$resp['message'];
        //         $data=$resp['data'];
        //     }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
        //         $tabla =$post['Tabla'];
        //         $id =$post['Id'];
        //         $resp=$ObjOdt->putOdtStatus($tabla,$id,$post['Status']);
        //         $success=$resp['success'];
        //         $msj=$resp['message'];
        //         $data=$resp['data'];
        //     }else{
        //         $success=false;
        //         $msj="Metodo de envio incorrecto";
        //         $data="Error";
        //     }
            
        //     echo json_encode(
        //         array("success" => $success, 
        //             "data" => $data,
        //             "messenge"=>$msj,
        //             "items"=>$items));
        //     return;
        // break;
        case 'odt':

            $items = null;
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if (isset($_GET['Id'])) {
                        if (isset($_GET['subTabla'])) {
                            $resp = $ObjOdt->dataOdtIdStatus($_GET['Tabla'], $_GET['Id'], $_GET['subTabla']);
                        } else {
                            $resp = $ObjOdt->dataOdtId($_GET['Tabla'], $_GET['Id']);
                        }
                        $items = $resp['items'];
                    } elseif (isset($_GET['Select'])) {
                        $resp = $ObjOdt->dataOdt($_GET['Tabla'], $_GET['Select']);
                        $items = $resp['items'];
                    } elseif (isset($_GET['getDataExcel'])) {
                        $resp = $ObjOdt->dataExcelOdt($_GET['Tabla']);
                    } else {
                        $resp = $ObjOdt->dataOdtTabla($_GET['Tabla']);
                    }
                    $success = $resp['success'];
                    $msj = $resp['message'];
                    $data = $resp['data'];
                    break;
                case 'POST':
                    $tabla = $post['Tabla'];
                    $dataOdt = $post['Data'];
                    $producto = $post['Producto'];
                    // $userName=$post['Usuario'];
                    $resp = $ObjOdt->createOdt($dataOdt, $tabla, $producto, $userName);

                    $success = $resp['success'];
                    $msj = $resp['message'];
                    $data = $resp['data'];
                    break;
                case 'PUT':
                    if(isset($post['Select'])){
                        switch ($post['Select']) {
                            case 'cerrar':
                                $id =$post['Id'];
                                $resp=$ObjOdt->cerrarOdt($id,$post['Status']);
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }else{
                        $tabla = $post['Tabla'];
                        $id = $post['Id'];
                        $resp = $ObjOdt->putOdtStatus($tabla, $id, $post['Status']);
                    }

                    $success = $resp['success'];
                    $msj = $resp['message'];
                    $data = $resp['data'];
                    break;

                default:
                    $success = false;
                    $msj = "Metodo de envio incorrecto";
                    $data = "Error";
                    break;
            }

            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;
        case 'ajustes':

            $items = null;
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
                if(isset($_GET['Id'])){
                    $resp=$ObjAjustes->dataProductosId($_GET['Tabla'],$_GET['Id']);
                    $items = $resp['items'];
                }elseif(isset($_GET['Select'])){
                 $resp=$ObjAjustes->dataProductos($_GET['Tabla'],$_GET['Select']);
                }
                elseif(isset($_GET['getDataExcel'])){

                    $resp=$ObjAjustes->dataExcelAjustes($_GET['Tabla']);

                }
                else{
                 $resp=$ObjAjustes->dataAjustesTabla($_GET['Tabla']);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }
            elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
                $tabla =$post['Tabla'];
                if(!empty($_FILES)){
                    $id=$post['Id'];
                    $archivos = $_FILES;
                    $resp=$ObjAjustes->altaFiles($id,$tabla,$archivos,$userName);
                }else{
                    $dataProducto=$post['Data'];
                    $resp=$ObjAjustes->createAjuste($dataProducto,$tabla);
    
                }
              
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
                $tabla =$post['Tabla'];
                $id =$post['Id'];
                if(isset($post['Status'])){
                    $resp=$ObjAjustes->putProductosStatus($tabla,$id,$post['Status']);
                }else{
                $dataProducto=$post['Data'];
                $dataItems=$post['Items'];
                $resp=$ObjAjustes->putProductos($dataProducto,$tabla,$id,$dataItems);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;

        case 'odv':

            $items = null;
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if(isset ($_GET['proceso'])){
                        switch ($_GET['proceso']) {
                            case 'verificarSaldo':
                                // print_r($_GET);
                                $resp=$ObjOdv-> getStatusClientes($_GET['Id']);
                               
                                break;
                            
                            default:
                                # code...
                                break;
                        }
                    }else{
                        if(isset($_GET['Id'])){
    
                            $resp=$ObjOdv->dataOdvId($_GET['Tabla'],$_GET['Id']);
        
                            $items = $resp['items'];
        
                        }elseif(isset($_GET['NextId'])){
        
                            $resp=$ObjOdv->next_ID($_GET['NextId']);
        
                        }elseif(isset($_GET['Select'])){
        
                         $resp=$ObjOdv->dataOdv($_GET['Tabla'],$_GET['Select']);
        
                        }
        
                        elseif(isset($_GET['getDataExcel'])){
        
                            $resp=$ObjOdv->dataExcelOdv($_GET['Tabla']);
        
                        }// CAMBIO CASE PDF ORDER DE PEDIDO 
                        elseif(isset($_GET['createPDF'])){
                            if($_GET['createPDF']=='orderOrder'){
                                $resp=$ObjOdv->generarDataPDFOdv($_GET['Tabla'],$_GET['pdf']);
                            }
                        }elseif(isset($_GET['countEvidencias'])){
        
                            // CAMBIOS ALEXANDER EVIDENCIAS 
                            $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
        
                        }
                        elseif(isset($_GET['statusEvidencia'])){
                            // $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
                            $resp=$ObjOdv->contador($_GET['Tabla'],$_GET['IdPedido'],$_GET['CantidadPedido']);
        
                        }
        
                        else{
        
                         $resp=$ObjOdv->dataAjustesTabla($_GET['Tabla']);
        
                        }
                    }
                 
    
                    $success=$resp['success'];
    
                    $msj=$resp['message'];
    
                    $data=$resp['data'];
                        break;
                    if(isset($_GET['Id'])){
                        $resp=$ObjOdv->dataOdvId($_GET['Tabla'],$_GET['Id']);
                        $items = $resp['items'];
                    }elseif(isset($_GET['NextId'])){
                        $resp=$ObjOdv->next_ID($_GET['NextId']);
                    }elseif(isset($_GET['Select'])){
                     $resp=$ObjOdv->dataOdv($_GET['Tabla'],$_GET['Select']);
                    }
                    elseif(isset($_GET['getDataExcel'])){
                        $resp=$ObjOdv->dataExcelOdv($_GET['Tabla']);
                    }// CAMBIO CASE PDF ORDER DE PEDIDO 
                    elseif(isset($_GET['createPDF'])){
                        if($_GET['createPDF']=='orderOrder'){
                            $resp=$ObjOdv->generarDataPDFOdv($_GET['Tabla'],$_GET['pdf']);
                        }
                    }elseif(isset($_GET['countEvidencias'])){
                        // CAMBIOS ALEXANDER EVIDENCIAS 
                        $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
                    }
                    elseif(isset($_GET['statusEvidencia'])){
                        // $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
                        $resp=$ObjOdv->contador($_GET['Tabla'],$_GET['IdPedido'],$_GET['CantidadPedido']);
                    }
                    else{
                     $resp=$ObjOdv->dataAjustesTabla($_GET['Tabla']);
                    }
                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                case 'POST':
                    $tabla =$post['Tabla'];
                    if(!empty($post['Data'])){
                        $dataOdv=$post['Data'];
                        $dataItems=$post['Items'];
                        $resp=$ObjOdv->createOdv($dataOdv,$tabla,$dataItems);
                    }
                    // CAMBIOS ALEXANDER EVIDENCIAS 
    
                    if(!empty($_FILES)){
                        $idPedido=$post['Id'];
                        $archivos = $_FILES;
                        $resp=$ObjOdv->altaFiles($idPedido,$tabla,$archivos,$userName);
                    }
                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                case 'PUT':
                    if(isset($post['Select'])){
                        switch ($post['Select']) {
                            case 'cancelar':
                                $tabla =$post['Tabla'];
                                $id =$post['Id'];
                                $resp=$ObjOdv->putOdvStatus($tabla,$id,$post['Status']);
                                break;
                            case 'cerrar':
                                $id =$post['Id'];
                                $resp=$ObjOdv->cerrarOdv($id,$post['Status']);
                                break;
                            
                            default:
                            $success=false;
                            $msj="Accion incorrecta";
                            $data="Error";
                                break;
                        }
                    }else{
                        $tabla =$post['Tabla'];
                        $id =$post['Id'];
                        $dataProducto=$post['Data'];
                        $dataItems=$post['Items'];
                        $resp=$ObjOdv->putOdv($dataProducto,$tabla,$id,$dataItems);
                    }
   

                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                default:
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
                    break;
            }

            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;
        case 'odv2':

            $items = null;
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                if(isset ($_GET['proceso'])){
                    switch ($_GET['proceso']) {
                        case 'verificarSaldo':
                            // print_r($_GET);
                            $resp=$ObjOdv-> getStatusClientes($_GET['Id']);
                           
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }else{
                    if(isset($_GET['Id'])){

                        $resp=$ObjOdv->dataOdvId($_GET['Tabla'],$_GET['Id']);
    
                        $items = $resp['items'];
    
                    }elseif(isset($_GET['NextId'])){
    
                        $resp=$ObjOdv->next_ID($_GET['NextId']);
    
                    }elseif(isset($_GET['Select'])){
    
                     $resp=$ObjOdv->dataOdv($_GET['Tabla'],$_GET['Select']);
    
                    }
    
                    elseif(isset($_GET['getDataExcel'])){
    
                        $resp=$ObjOdv->dataExcelOdv($_GET['Tabla']);
    
                    }// CAMBIO CASE PDF ORDER DE PEDIDO 
                    elseif(isset($_GET['createPDF'])){
                        if($_GET['createPDF']=='orderOrder'){
                            $resp=$ObjOdv->generarDataPDFOdv($_GET['Tabla'],$_GET['pdf']);
                        }
                    }elseif(isset($_GET['countEvidencias'])){
    
                        // CAMBIOS ALEXANDER EVIDENCIAS 
                        $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
    
                    }
                    elseif(isset($_GET['statusEvidencia'])){
                        // $resp = $ObjOdv->countEvidencias($_GET['IdPedido']);
                        $resp=$ObjOdv->contador($_GET['Tabla'],$_GET['IdPedido'],$_GET['CantidadPedido']);
    
                    }
    
                    else{
    
                     $resp=$ObjOdv->dataAjustesTabla($_GET['Tabla']);
    
                    }
                }
             

                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];
                    break;
                case 'POST':
                    $tabla =$post['Tabla'];

                    if(!empty($post['Data'])){
    
                        $dataOdv=$post['Data'];
    
                        $dataItems=$post['Items'];
    
                        $resp=$ObjOdv->createOdv($dataOdv,$tabla,$dataItems);
    
                    }
                    // CAMBIOS ALEXANDER EVIDENCIAS 
    
    
                    if(!empty($_FILES)){
                        $idPedido=$post['Id'];
                        $archivos = $_FILES;
                        $resp=$ObjOdv->altaFiles($idPedido,$tabla,$archivos,$userName);
                    }
                  
    
                    $success=$resp['success'];
    
                    $msj=$resp['message'];
    
                    $data=$resp['data'];
                    break;
                case 'PUT':
                    
                    $tabla =$post['Tabla'];

                    $id =$post['Id'];
    
                    if(isset($post['Status'])){
    
                        $resp=$ObjOdv->putOdvStatus($tabla,$id,$post['Status']);
    
                    }else{
    
                    $dataProducto=$post['Data'];
    
                    $dataItems=$post['Items'];
    
                    $resp=$ObjOdv->putOdv($dataProducto,$tabla,$id,$dataItems);
    
                    }
    
                    $success=$resp['success'];
    
                    $msj=$resp['message'];
    
                    $data=$resp['data'];
                    break;
                default:
                $success=false;

                $msj="Metodo de envio incorrecto";

                $data="Error";
                    break;
            }


            

            echo json_encode(

                array("success" => $success, 

                    "data" => $data,

                    "messenge"=>$msj,

                    "items"=>$items));

            return;

        break;
        case 'factura':
            $items = null;
          
            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                                case 'getFolio':
                                    $resp=$Objfactura->getLastID('facturas');//Pasas el nombre de la tabla que quieres obtener el id
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'regimenFiscal':
                                                                         
                                    $resp=$Objfactura->regimenFiscal();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                                    // $resp['data'] = ;
                                break;
                                case 'cfdi_relacionado':
                                                                         
                                    $resp=$Objfactura->cfdi_relacionado();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                                    // $resp['data'] = ;
                                break;
                                case 'autoCompleteOdv':
                                                                        
                                    $resp=$Objfactura->autoCompleteOdv($_GET['String']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getTabla':
                                                                        
                                    $resp=$Objfactura->getTabla($_GET['razonfactura']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'usoCfdi':
                                                                        
                                    $resp=$Objfactura->getUsoCfdi();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'metodoPago':
                                                                        
                                    $resp=$Objfactura->getMetodoPago();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'formaPago':
                                                                        
                                    $resp=$Objfactura->getFormaPago();
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getfacturaCFDI':

                                    $moneda= $_GET['moneda'];
                                    $idCliente=$_GET['cliente'];              
                                    $resp=$Objfactura->getfacturaCFDI($idCliente,$moneda);
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                           
                                case 'getFactura':
                                    if(isset($_GET['Id'])){
                                        $id=$_GET['Id'];
                                        $resp=$Objfactura->getFactura($id);

                                    }else{
                                        $resp['success']=false;
                                        $resp['message']='No se recibio Id de la factura';
                                        $resp['data']='Error';
                                    }
                                                                        
                                    
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getFacturaId':
                                    if(isset($_GET['Id'])){
                                        $id=$_GET['Id'];
                                        $resp=$Objfactura->getFacturaId($id);

                                    }else{
                                        $resp['success']=false;
                                        $resp['message']='No se recibio Id de la factura';
                                        $resp['data']='Error';
                                    }
                                                                        
                                    
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'createPDF':
                                                                         
                                    $resp=$Objfactura->getDataFactura($_GET['id']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                                break;
                                                                case 'createPDF':
                                                                         
                                    $resp=$Objfactura->getDataFactura($_GET['id']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                                break;


                                default:
        
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':
                        // print_r('algo');
                        // var_dump($_POST);
                        // if(!empty($_POST)){
                        //     $post=$_POST;
                        //     print_r($post);
                        // }
                        // print_r($post);
                        if(isset($post['Select'])){
                            switch($post['Select']){
                                case 'insertFactura':
                                    $data=$post['data'];
                                    $id=$post['id'];
                                    if(!empty($id)){
                                        $resp = $Objfactura->updateFactura($data,$id);

                                    }else{
                                        $resp = $Objfactura->createFactura($data);
                                    }
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                case 'getDataPedido':
                                    // print_r('Datos randons');
                                    $data=$post;
                                    // print_r($data);
                                    $resp = $Objfactura->gePedido($post['Id']);
                                break;
                                case 'deleteCFDIrelacionados':

                                    $resp = $Objfactura->deleteCFDIrelacionados($post['id']);
                                break;
                                case 'cancelarFactura':
                                    // print_r($post);
                                    $resp = $Objfactura->cancelarFactura($post['idFactura']);
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }
                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

  
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'cp':
            $items = null;

            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                               
                              
                                case 'autoCompleteOdv':
                                                                        
                                    $resp=$ObjCP->autoCompleteOdv($_GET['String']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'metodoPago':
                                                                        
                                    $resp=$ObjCP->getMetodoPago();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'formaPago':
                                                                        
                                    $resp=$ObjCP->getFormaPago();
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getTabla':
                                                                        
                                    $resp=$ObjCP->getTabla();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getFactura':
                                    if(isset($_GET['Id'])){
                                        $id=$_GET['Id'];
                                        $resp=$ObjCP->getFactura($id);

                                    }else{
                                        $resp['success']=false;
                                        $resp['message']='No se recibio Id de la factura';
                                        $resp['data']='Error';
                                    }
                                                                        
                                    
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':

                        if(!empty($_POST)){
                            $post=$_POST;
                        }
                        if(isset($post['Select'])){
                            switch($post['Select']){
                                case 'insertCp':
                                    $data=$post['data'];
                                    $resp = $ObjCP->createCp($data);
                                                        
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }


                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

           
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'cxc':
            $items = null;

            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                               
                              
                                case 'autoCompleteOdv':
                                                                        
                                    $resp=$ObjCxC->autoCompleteOdv($_GET['String']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getTabla':
                                                                        
                                    $resp=$ObjCxC->getTabla();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getFacturas':
                                    $id= $_GET['Id'];                      
                                    $resp=$ObjCxC->getPedidos($id);
                                break;
                                case 'getPedidos':
                                    $id= $_GET['Id'];                      
                                    $resp=$ObjCxC->getPedidos($id);                        
                                    // $resp=$ObjCxC->getFacturas($id);                        
                                break;

                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':

                        if(!empty($_POST)){
                            $post=$_POST;
                        }
                        if(isset($post['Select'])){
                            switch($post['Select']){
                                case 'insertCxC':
                                    $data=$post['data'];
                                    $resp = $ObjCxC->createCxC($data);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                case 'fechaPromesaPago':
                                    $data=$post['data'];
                                    $column='fecha_promesa';
                                    // print_r($data);
                                    $resp = $ObjCxC->updateFecha($data,$column);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                case 'fechaInicioCredito':
                                    $data=$post['data'];
                                    $column='fecha_inicioCredito';
                                    // $column='fecha_vencimiento';
                                    $resp = $ObjCxC->updateFecha($data,$column);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }


                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

           
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'cxp':
            $items = null;

            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                               
                              
                                case 'autoCompleteOdv':
                                                                        
                                    $resp=$ObjCxP->autoCompleteOdv($_GET['String']);

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getTabla':
                                                                        
                                    $resp=$ObjCxP->getTabla();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'metodoPago':
                                                                        
                                    $resp=$ObjCxP->getMetodoPago();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'formaPago':
                                                                        
                                    $resp=$ObjCxP->getFormaPago();
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getFacturaId':
                                    $id= $_GET['Id'];                                  
                                    $resp=$ObjCxP->getFacturaId($id);
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                
                                case 'getFacturas':
                                    // print_r($_GET);
                                    $id= $_GET['Id'];                      
                                    $resp=$ObjCxP->getFacturas($id);
                                    // $success=$resp['success'];
                                    // $msj=$resp['message'];
                                    // $data=$resp['data'];

                        
                                break;
                                case 'getFacturaPDF':
                                    $id= $_GET['Id'];                      
                                    $resp=$ObjCxP->getFacturaPDF($id,$userName);

                        
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':

                        if(!empty($_POST)){
                            $post=$_POST;
                        }
                        if(isset($post['Select'])){
                            switch($post['Select']){
                        
                                case 'aplicarPago':
                                    $data=$post['data'];
                                    $data['idUser']=$user;
                                    $resp = $ObjCxP->aplicarPago($data);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                case 'fechaPromesaPago':
                                    $data=$post['data'];
                                    $column='fecha_promesa';
                                    $resp = $ObjCxP->updateFecha($data,$column);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                case 'fechaInicioCredito':
                                    $data=$post['data'];
                                    $column='fecha_inicioCredito';
                                    // $column='fecha_vencimiento';
                                    $resp = $ObjCxP->updateFecha($data,$column);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }


                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

           
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'notaCredito':
            $items = null;

            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                               
                                case 'getFolio':
                                    $resp=$Objfactura->getLastID('facturas');//Pasas el nombre de la tabla que quieres obtener el id
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'autoCompleteFactura':
                                    if(isset($_GET['Id'])&& !empty($_GET['Id'])){
                                        $moneda=$_GET['moneda'];
                                        $resp=$ObjNC->autoCompleteFactura($_GET['String'],$_GET['Id'],$moneda);

                                        $success=$resp['success'];
                                        $msj=$resp['message'];
                                        $data=$resp['data'];
                                    } else{
                                                                
                                        $resp['success']=false;

                                        $resp['message']='No se recibio Id del cliente';

                                        $resp['data']='Error';
                                    }                       
                                   
                        
                                break;
                                case 'getTabla':
                                                                        
                                    $resp=$ObjNC->getTabla();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'usoCfdi':
                                                                        
                                    $resp=$ObjNC->getUsoCfdi();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'metodoPago':
                                                                        
                                    $resp=$ObjNC->getMetodoPago();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'formaPago':
                                                                        
                                    $resp=$ObjNC->getFormaPago();
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                case 'getOdv':
                                    if(isset($_GET['Id'])){
                                        $id=$_GET['Id'];
                                        $resp=$ObjNC->getOdv($id);

                                    }else{
                                        $resp['success']=false;
                                        $resp['message']='No se recibio Id de la factura';
                                        $resp['data']='Error';
                                    }
                                                                           
                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':

                        if(!empty($_POST)){
                            $post=$_POST;
                        }
                        if(isset($post['Select'])){
                            switch($post['Select']){
                                case 'insertNC':
                                    $data=$post['data'];
                                    $resp = $ObjNC->createNC($data);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }


                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

           
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'gastos':
            $items = null;

            if(isset($_SERVER['REQUEST_METHOD'])){

                switch($_SERVER['REQUEST_METHOD']){
                    case 'GET':
                        if(isset($_GET['Select'])){
                            switch($_GET['Select']){
                      
                                case 'getTabla':
                                                                        
                                    $resp=$ObjGastos->getTabla();

                                    $success=$resp['success'];
                                    $msj=$resp['message'];
                                    $data=$resp['data'];
                        
                                break;
                                default:
        
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;

                    case 'POST':

                        if(!empty($_POST)){
                            $post=$_POST;
                        }
                        if(isset($post['Select'])){
                            switch($post['Select']){
                                case 'insertFactura':
                                    $data=$post['data'];
                                    // $resp = $ObjGastos->createFactura($data);
                                    // $resp['message'] = 'Datos guardados de forma correcta';
                                break;
                                default:
                                    print_r('No se encuentra ninguna opción');
                                break;
                            }
                        }else{
                            print_r('Falta opción');
                        }
                    break;
                }
              
                $success=$resp['success'];

                $msj=$resp['message'];

                $data=$resp['data'];

            }

       
            echo json_encode(array("success" => $success,"data" => $data,"messenge"=>$msj,"items"=>$items),JSON_UNESCAPED_UNICODE);

            return;

        break;
        case 'compras':

            $items = null;
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'GET':
                    if(isset($_GET['Id'])){
                        $resp=$ObjCompras->dataComprasId($_GET['Tabla'],$_GET['Id']);
                        // $items = $resp['items'];
                    }elseif(isset($_GET['Select'])){
                     $resp=$ObjCompras->dataProveedor($_GET['Tabla'],$_GET['Select']);
                    }
                    elseif(isset($_GET['getDataExcel'])){
                        $resp=$ObjCompras->dataExcelCompras($_GET['Tabla']);
                    }
    
                    // NOTA Se agrego un nuevo elseif
                    elseif(isset($_GET['createPDF'])){
                        if($_GET['createPDF']=='purchaseOrder'){
                            $resp=$ObjCompras->createPDF($_GET['IdPDF']);
                        }
                    }
    
                    else{
                     $resp=$ObjCompras->dataComprasTabla($_GET['Tabla']);
                    }
                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                case 'POST':
                    $tabla =$post['Tabla'];
                    if(!empty($_FILES)){
                        $id=$post['Id'];
                        $archivos = $_FILES;
                        $resp=$ObjAjustes->altaFiles($id,$tabla,$archivos,$userName);
                    }else{
                        $dataCompras=$post['Data'];
                        $resp=$ObjCompras->createCompras($dataCompras,$tabla);
        
                    }
                  
                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                case 'PUT':
                    if(isset($post['Select']))
                    switch ($post['Select']) {
                        case 'status':
                            $tabla =$post['Tabla'];
                            $id =$post['Id'];
                            $resp=$ObjCompras->putComprasStatus($tabla,$id,$post['Status']);
                            break;
                        
                        default:
                            # code...
                            break;
                    }else{
                        $dataProducto=$post['Data'];
                        $dataItems=$post['Items'];
                        $resp=$ObjAjustes->putProductos($dataProducto,$tabla,$id,$dataItems);
               
                    }
      
                    $success=$resp['success'];
                    $msj=$resp['message'];
                    $data=$resp['data'];
                    break;
                
                default:
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
                    break;
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;
        case 'inventario':

            $items = null;
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){
                if(isset($_GET['Id'])){
                    $resp=$ObjInventario->dataProductosId($_GET['Tabla'],$_GET['Id']);
                    $items = $resp['items'];
                }elseif(isset($_GET['Select'])){
                 $resp=$ObjInventario->dataProductos($_GET['Tabla'],$_GET['Select']);
                }
                elseif(isset($_GET['getDataExcel'])){
                    $resp=$ObjInventario->dataExcelInventario($_GET['Tabla']);
                }
                else{
                 $resp=$ObjInventario->dataAjustesTabla($_GET['Tabla']);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }
            elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
                $tabla =$post['Tabla'];
                if(!empty($_FILES)){
                    $id=$post['Id'];
                    $archivos = $_FILES;
                    $resp=$ObjInventario->altaFiles($id,$tabla,$archivos,$userName);
                }else{
                    $dataProducto=$post['Data'];
                    $resp=$ObjInventario->createAjuste($dataProducto,$tabla);
    
                }
              
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="PUT"){
                $tabla =$post['Tabla'];
                $id =$post['Id'];
                if(isset($post['Status'])){
                    $resp=$ObjInventario->putProductosStatus($tabla,$id,$post['Status']);
                }else{
                $dataProducto=$post['Data'];
                $dataItems=$post['Items'];
                $resp=$ObjInventario->putProductos($dataProducto,$tabla,$id,$dataItems);
                }
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;
        case 'clientes':

            $items = null;
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){

                if(isset($_GET['getDataExcel'])){
                    $resp=$ObjClientes->dataExcelClientes($_GET['Tabla']);
                }

                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;

        break;
        case 'general':
            $items = null;
            if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="POST"){
                if(isset($post['TC'])){
                    $resp=$ObjApi->moneda($post['status'],$post['TC']);
                }else{
                    $resp=$ObjApi->moneda($post['status'],null);
                }
                
                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }elseif(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=="GET"){

                if(isset($_GET['Status'])){
                    $resp=$ObjApi->resetTC($_GET['Status']);
                }

                $success=$resp['success'];
                $msj=$resp['message'];
                $data=$resp['data'];
            }else{
                $success=false;
                $msj="Metodo de envio incorrecto";
                $data="Error";
            }
            
            echo json_encode(
                array("success" => $success, 
                    "data" => $data,
                    "messenge"=>$msj,
                    "items"=>$items));
            return;
        break;

    }
}else{
    echo json_encode(
        array("success" => false, 
            "data" => "Error",
            "messenge"=>"No se recibio ninguna accion"
            ));
    return;
}
?>
