<?php
use SWServices\Stamp\StampService as StampService;
use SWServices\AccountBalance\AccountBalanceService as AccountBalanceService;
use SWServices\Cancelation\CancelationService as CancelationService;
use SWServices\Toolkit\SignService as Sellar;
use \CfdiUtils\XmlResolver\XmlResolver;
use \CfdiUtils\CadenaOrigen\DOMBuilder;
require '../requerimientos/vendorComposer/vendor/autoload.php';
use Luecano\NumeroALetras\NumeroALetras;
include_once 'model/api.modelo.php';

if($_SESSION['Moneda']=='Pesos'){

    $Moneda = 1;

}else{

    $Moneda = 1/$_SESSION['TC'];

}
// require '../requerimientos/vendorComposer/vendor/autoload.php';
// use Luecano\NumeroALetras\NumeroALetras;


class ControladorFactura extends apiModel{


    function autoCompleteOdv($string)
    {

        $json = [];

        $data = [];
        
        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";
        $query = "SELECT * FROM odv WHERE Status=1 AND Id LIKE '%" . (int)$string . "%';";
        // print_r($query);
        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {

                $data[] = ['value' => $datos['Id'], 'data' => $datos['Id']];
            }

            $json['suggestions'] = $data;
            $msj = 'Exito en consulta de datos';
            $success = true;
        } else {
            $json['suggestions'] = [];
            $msj = "Error en la llamada";
            $success = false;
        }


        $mensaje = array("success" => $success, "data" => $json, "message" => $msj);

        return $mensaje;
    }

    function getfacturaCFDI($idCliente, $moneda)
    {


        $success = false;
        $data='Error';
        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";
        $query = "SELECT f.id, c.Nombre AS cliente, f.total, DATE(f.fecha) AS fecha, f.moneda
        FROM facturas f
        JOIN clientes c
        ON c.Id = f.id_cliente
        WHERE f.status = 1 AND f.timbrado = 1 AND f.cancelado = 0
        AND f.moneda= $moneda
        AND f.id_cliente = $idCliente
        ORDER BY f.id DESC
        LIMIT 10;";
        // print_r($query);
        $resp = $this->getAllTable($query);

        if ($resp) {
            $data = [];
            while ($datos = $resp->fetch_assoc()) {
                if($datos['moneda']==1){
                    $moneda='MXN';
                }else{
                    $moneda='USD';
                }
                $datos['id']=$datos['id'];
                $datos['folioFactura']=$datos['id']." ".$datos['cliente']." $".(float)$datos['total']." ".$moneda ;
                $datos['date']=$datos['fecha'];
                $data[]=$datos;
                // $data[] = ['value' => $datos['Id'], 'data' => $datos['Id']];
            }

            
            $msj = 'Exito en consulta de datos';
            $success = true;
        } else {
            
            $msj = "Error en la llamada";
        }


        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }


    function dataDropDown($tabla,$status = null){

        // print_r("controlador");

        $success=false;

        $data="Error";

        if(!empty($tabla)){

            $query= 'SELECT clave as id,Nombre FROM '.$tabla;

            

            $resp= $this->getAllTable($query);

            // print_r($resp);

            $data=[];

            while ($datosBD = $resp->fetch_assoc()) {

                // $array['id'] =$datosBD['id'];

                // $array['nombre'] = mb_convert_encoding($datosBD['nombre'],'UTF-8','ISO-8859-1');

                // print_r($array['nombre']);

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

        print_r($data);

        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

    

        return $mensaje;



    }

    function formatoFechaVista($date){

        // $date = '2022-12-16 10:07:21';

        $timestamp = strtotime($date);

        return date('d/m/Y', $timestamp);

        // echo $new_date;  // Imprime "16/12/2022"

    }

    function getLastID($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $resp= $this->nextID($tabla);
            $data=[];
            $data['nextID']=$resp['Auto_increment'];
            if ($resp['success']==1) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data['nextID'], "message" => $msj);
    
        return $mensaje;

    }
    // function createFactura($data){
    //     if(is_array($data)){
    //         // print_R($data);
    //         $flag=0;
    //         $cfdi=' ';
    //         $dataClientes=$data['infoCliente'];
    //         $dataBalanceConceptos=$data['infoBalanceConceptos'];

    //         $dataConceptos=$data['infoConceptos'];
    //         $datainfoCFDIS=$data['infoCFDIS'];

    //         if(!empty($dataClientes)){
    //             $idCliente=$dataClientes['cliente'];
    //             $idUser=$dataClientes['idUser'];
    //             $regimenFiscal=$dataClientes['regimenFiscal']; //Es para hacer update al cliente
    //             $folio=$dataClientes['folio'];
    //             $idFactura=$dataClientes['folio'];
    //             $moneda=$dataClientes['moneda'];  //Falta ID moneda
    //             $tipoDeCambio=$dataClientes['tipoDeCambio'];
    //             $usoCFDI=$dataClientes['usoCFDI'];
    //             $metodoDePago=$dataClientes['metodoDePago'];
    //             $formaDePago=$dataClientes['formaDePago'];

    //         }
    //         if(!empty($dataBalanceConceptos)){
    //             $observaciones=$dataBalanceConceptos['balanceObservaciones']?$dataBalanceConceptos['balanceObservaciones']:' ';
    //             $subtotal=$dataBalanceConceptos['balanceSubtotal'];
    //             $impuestosRetenidos=$dataBalanceConceptos['balanceImpuestosRetenidos'];
    //             $impuestoTrasladado=$dataBalanceConceptos['balanceImpuestoTrasladado'];
    //             $total=$dataBalanceConceptos['balanceTotal'];

    //         }
    //            //Opcional
    //            if(!empty($datainfoCFDIS)){
    //             foreach($datainfoCFDIS as $valueCfdi){
    //                 $folio=$valueCfdi['folio'];
    //                 // $date=$valueCfdi['date']; //Por el momento no se utiliza la fecha
    //                 $cfdi=$valueCfdi['cfdi'];

    //                 $sqlRelaciones="INSERT INTO facturas_relaciones (id_cfdi, cfdi_relacionado, tipo, id_factura) VALUES (".$folio.",".$cfdi.",'FACTURA',".$idFactura.")";
    //                 $resp = $this->getAllTableLastID($sqlRelaciones);
    //             }
    //             }

       
    //     $query= 'INSERT INTO facturas (id_usuario,moneda,metodo_pago,forma_pago,uso_cfdi, tipo_cambio, id_cliente, subtotal, impuesto,
    //                         impuesto_retenido,total,observaciones,cfdi_relacionado) 

    //         VALUES ('.$idUser.','.$moneda.','.$metodoDePago.','.$formaDePago.','.$usoCFDI.','.$tipoDeCambio.','.$idCliente.','.$subtotal.',
    //                 '.$impuestoTrasladado.','.$impuestosRetenidos.','.$total.',"'.$observaciones.'","'.$cfdi.'")';
    //         // print_r($query);
    //         $resp = $this->getAllTableLastID($query);
    //         // print_r($dataConceptos);
    //         if(!empty($dataConceptos)){
    //             foreach($dataConceptos as $valueConcepto){
    //                 $odv=$valueConcepto['odv'];
    //                 $codigo=$valueConcepto['Codigo'];
    //                 $claveProdServ=$valueConcepto['ClaveProdServ'];
    //                 $descripcion=$valueConcepto['Descripcion'];
    //                 $cantidad=$valueConcepto['Cantidad'];
    //                 $unidad=$valueConcepto['Unidad'];
    //                 $precio=$valueConcepto['Precio'];
    //                 // $totalC=$valueConcepto['Total'];
    //                 $impuestos=$valueConcepto['Impuestos'];
    //                 //Falta Folio, Clave Unidad
    //                 $sqlPartes="INSERT INTO facturas_partes (id_factura, orden_trabajo, codigo, clave_prodserv, descripcion, clave_unidad, cantidad, precio, por_impuesto) 
    //                             VALUES($idFactura, '$odv',  '$codigo', '$claveProdServ', '$descripcion', '$unidad', $cantidad, $precio, $impuestos)";
    //         // print_r($sqlPartes);

    //                 $resp = $this->getAllTableLastID($sqlPartes);
    
    //             }
    //         }
           
    //                 if($resp){
    //                         $success=true;
    //                         $msj='Factura agregado correctamente';
    //                         $data=$resp['last_id'];
    //                 }else{
    //                     $success=false;
    //                     $msj='Error al generar ajuste';
    //                     $data="Error";
    //                 }

    //             $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

    //             return $mensaje;
    //     }

    // }
    function createFactura($data){
        if(is_array($data)){
            // print_R($data);
            date_default_timezone_set('America/Tijuana');
			$now = new DateTime();
			$now = $now->format('Y-m-d H:i:s');
			date_default_timezone_set('UTC');
            $flag=0;
            $cfdi=' ';
            $dataClientes=$data['infoCliente'];
            $dataBalanceConceptos=$data['infoBalanceConceptos'];
            $quienFactura = $dataClientes['quienFactura'];
            $dataConceptos=$data['infoConceptos'];
            $datainfoCFDIS=$data['infoCFDIS'];

            if(!empty($dataClientes)){
                $idCliente=$dataClientes['cliente'];
                $idUser=$dataClientes['idUser'];
                $regimenFiscal=$dataClientes['regimenFiscal']; //Es para hacer update al cliente
                $folio=$dataClientes['folio'];
                $moneda=$dataClientes['moneda'];  //Falta ID moneda
                $idFactura=$dataClientes['folio'];
                // if($moneda=='pesos'){
                //     $moneda=1;
                // }else{
                //     $moneda=2;
                // }
                $tipoDeCambio=$dataClientes['tipoDeCambio'];
                $usoCFDI=$dataClientes['usoCFDI'];
                $metodoDePago=$dataClientes['metodoDePago'];
                $formaDePago=$dataClientes['formaDePago'];

            }
            if(!empty($dataBalanceConceptos)){
                $observaciones=$dataBalanceConceptos['balanceObservaciones']?$dataBalanceConceptos['balanceObservaciones']:' ';
                $subtotal=$dataBalanceConceptos['balanceSubtotal'];
                $impuestosRetenidos=$dataBalanceConceptos['balanceImpuestosRetenidos'];
                $impuestoTrasladado=$dataBalanceConceptos['balanceImpuestoTrasladado'];
                $total=$dataBalanceConceptos['balanceTotal'];

            }
               //Opcional
               if(!empty($datainfoCFDIS)){
                foreach($datainfoCFDIS as $valueCfdi){
                    $folio=$valueCfdi['folio'];
                    // $date=$valueCfdi['date']; //Por el momento no se utiliza la fecha
                    $cfdi=$valueCfdi['cfdi'];
                    


                    $sqlRelaciones="INSERT INTO facturas_relaciones (id_cfdi, cfdi_relacionado, tipo, id_factura) VALUES (".$folio.",".$cfdi.",'FACTURA',".$idFactura.")";
                    // $sqlRelaciones="INSERT INTO facturas_relaciones (id_factura, cfdi_relacionado, tipo) VALUES (".$folio.",".$cfdi.",'FACTURA')";
                    $resp = $this->getAllTableLastID($sqlRelaciones);
                }
                }

       
        $query= 'INSERT INTO facturas (id_usuario,moneda,metodo_pago,forma_pago,uso_cfdi, tipo_cambio,razonfactura, id_cliente, subtotal, impuesto,
                            impuesto_retenido,total,fecha,observaciones,cfdi_relacionado) 

            VALUES ('.$idUser.','.$moneda.','.$metodoDePago.','.$formaDePago.','.$usoCFDI.','.$tipoDeCambio.','.$quienFactura.','.$idCliente.','.$subtotal.',
                    '.$impuestoTrasladado.','.$impuestosRetenidos.','.$total.',"'.$now.'" ,"'.$observaciones.'","'.$cfdi.'")';
            // print_r($query);
            $resp = $this->getAllTableLastID($query);
            
            if(!empty($dataConceptos)){
                foreach($dataConceptos as $valueConcepto){
                    $odv=$valueConcepto['odv'];
                    $codigo=$valueConcepto['Codigo'];
                    $claveProdServ=$valueConcepto['ClaveProdServ'];
                    $descripcion=$valueConcepto['Descripcion'];
                    $cantidad=$valueConcepto['Cantidad'];
                    $unidad=$valueConcepto['Unidad'];
                    $precio=$valueConcepto['Precio'];
                    // $totalC=$valueConcepto['Total'];
                    $impuestos=$valueConcepto['Impuestos'];
                    //Falta Folio, Clave Unidad
                    $sqlPartes="INSERT INTO facturas_partes (id_factura, orden_trabajo, codigo, clave_prodserv, descripcion, clave_unidad, cantidad, precio, por_impuesto) 
                                VALUES($idFactura, '$odv',  '$codigo', '$claveProdServ', '$descripcion', '$unidad', $cantidad, $precio, $impuestos)";
                    $resp = $this->getAllTableLastID($sqlPartes);
    
                }
            }
           
 
         
            


                    if($resp){



                            $success=true;

                            $msj='Factura agregado correctamente';

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
    function createRow($data)
    {
        // 
        // print_r($data);
        $stringRow = '';

        foreach ($data as $value) {

            $stringRow .= '
            <tr>
                <td>' . $value['orden_trabajo'] . '</td>
                <td>' . $value['codigo'] . '</td>

                <td>' . $value['clave_prodserv'] . '</td>
                <td>' . $value['descripcion'] . '</td>

                <td>' . $value['cantidad'] . '</td>
                <td>' . $value['claveUnidadSAT'] . '</td>


                <td>' . $value['precio'] . '</td>
                <td>' . $value['impuesto'] . '</td>
                <td>' . $value['importe'] . '</td>

            </tr>
        ';
        }


        //     $stringRow .= '
        //     <tr>
        //         <td>' . $value['name'] . '</td>
        //         <td>' . number_format($value['lot'], 2) . '</td>

        //         <td>' . number_format($value['clave_unidad'], 2) . '</td>
        //         <td>' . number_format($value['clave_prodserv'], 2) . '</td>



        //         <td>$' . number_format($value['unitPrice'], 2) . '</td>
        //         <td>$' . number_format($value['amount'], 2) . '</td>
        //     </tr>
        // ';

        return $stringRow;
    }
    function updateFactura($data2,$id){
        if(is_array($data2)){
            // print_R($data);
            $success=false;
            $msj='Error al Editar Factura';
            $data="Error";
            $flag=0;
            $cfdi=' ';
            $dataClientes=$data2['infoCliente'];
            $dataBalanceConceptos=$data2['infoBalanceConceptos'];

            $dataConceptos=$data2['infoConceptos'];
            $datainfoCFDIS=$data2['infoCFDIS'];

            if(!empty($dataClientes)){
                $idCliente=$dataClientes['cliente'];
                $idUser=$dataClientes['idUser'];
                $regimenFiscal=$dataClientes['regimenFiscal']; //Es para hacer update al cliente
                $folio=$dataClientes['folio'];
                $moneda=$dataClientes['moneda'];  
                $tipoDeCambio=$dataClientes['tipoDeCambio'];
                $usoCFDI=$dataClientes['usoCFDI'];
                $metodoDePago=$dataClientes['metodoDePago'];
                $formaDePago=$dataClientes['formaDePago'];

            }
            if(!empty($dataBalanceConceptos)){
                $observaciones=$dataBalanceConceptos['balanceObservaciones']?$dataBalanceConceptos['balanceObservaciones']:' ';
                $subtotal=$dataBalanceConceptos['balanceSubtotal'];
                $impuestosRetenidos=$dataBalanceConceptos['balanceImpuestosRetenidos'];
                $impuestoTrasladado=$dataBalanceConceptos['balanceImpuestoTrasladado'];
                $total=$dataBalanceConceptos['balanceTotal'];

            }
               //Opcional
               if(!empty($datainfoCFDIS)){
                foreach($datainfoCFDIS as $valueCfdi){
                    $folio=$valueCfdi['folio'];
                    // $date=$valueCfdi['date']; //Por el momento no se utiliza la fecha
                    $cfdi=$valueCfdi['cfdi'];

                    if(isset($valueCfdi['idRelacional'])){
                    $idRelacioando=$valueCfdi['idRelacional'];

                        $sqlRelaciones= "UPDATE facturas_relaciones 
                        SET cfdi_relacionado        = $cfdi
                        WHERE id = $idRelacioando;";
                        // print_r
                        $resp = $this->updateTable($sqlRelaciones);
                        if($resp==1){
                            $success=true;
                            $msj='Factura agregado correctamente';
                            $data='Exito';
                        }
                    }else{
                    $sqlRelaciones="INSERT INTO facturas_relaciones (id_cfdi, cfdi_relacionado, tipo, id_factura) VALUES (".$folio.",".$cfdi.",'FACTURA',".$id.")";
                    $resp = $this->getAllTableLastID($sqlRelaciones);
                    if($resp){
                        $success=true;
                        $msj='Factura agregado correctamente';
                        $data='Exito';
                    }
                    }
                   
                }
                }      
            // print_r("facturas_relaciones".$resp);

        // $query= 'INSERT INTO facturas (id_usuario,moneda,metodo_pago,forma_pago,uso_cfdi, tipo_cambio, id_cliente, subtotal, impuesto,
        //                     impuesto_retenido,total,observaciones,cfdi_relacionado) 

        //     VALUES ('.$idUser.','.$moneda.','.$metodoDePago.','.$formaDePago.','.$usoCFDI.','.$tipoDeCambio.','.$idCliente.','.$subtotal.',
        //             '.$impuestoTrasladado.','.$impuestosRetenidos.','.$total.',"'.$observaciones.'","'.$cfdi.'")';

                    $query= "UPDATE facturas 
                    SET 
                        id_usuario        = $idUser,
                        moneda            = $moneda,
                        metodo_pago       = $metodoDePago,
                        forma_pago        = $formaDePago,
                        uso_cfdi          = $usoCFDI,
                        tipo_cambio       = $tipoDeCambio,
                        id_cliente        = $idCliente,
                        subtotal          = $subtotal,
                        impuesto          = $impuestoTrasladado,
                        impuesto_retenido = $impuestosRetenidos,
                        total             = $total,
                        observaciones     = '$observaciones',
                        cfdi_relacionado  = '$cfdi'
                    WHERE id = $id;";
            // print_r($query);
            $resp = $this->updateTable($query);
            if($resp==1){
                $success=true;
                $msj='Factura agregado correctamente';
                $data='Exito';
            }
            
            if(!empty($dataConceptos)){
                foreach($dataConceptos as $valueConcepto){
                    $odv=$valueConcepto['odv'];
                    $codigo=$valueConcepto['Codigo'];
                    $claveProdServ=$valueConcepto['ClaveProdServ'];
                    $descripcion=$valueConcepto['Descripcion'];
                    $cantidad=$valueConcepto['Cantidad'];
                    $unidad=$valueConcepto['Unidad'];
                    $precio=$valueConcepto['Precio'];
                    // $totalC=$valueConcepto['Total'];
                    $impuestos=$valueConcepto['Impuestos'];
                    $idFacturaPedido=$valueConcepto['idFacturaPedido'];

                    //Falta Folio, Clave Unidad
                    // $sqlPartes="INSERT INTO facturas_partes (id_factura, orden_trabajo, codigo, clave_prodserv, descripcion, clave_unidad, cantidad, precio, por_impuesto) 
                    //             VALUES($folio, '$odv',  '$codigo', '$claveProdServ', '$descripcion', '$unidad', $cantidad, $precio, $impuestos)";
                    if(!empty($idFacturaPedido)){
                        $sqlPartes="UPDATE facturas_partes 
                        SET 
                            orden_trabajo = '$odv',
                            codigo = '$codigo',
                            clave_prodserv = '$claveProdServ',
                            descripcion = '$descripcion',
                            clave_unidad = '$unidad',
                            cantidad = $cantidad,
                            precio = $precio,
                            por_impuesto = $impuestos
                        WHERE id = $idFacturaPedido;";
                        $resp = $this->updateTable($sqlPartes);
                        if($resp==1){
                            $success=true;
                            $msj='Factura agregado correctamente';
                            $data='Exito';
                        }
                    }else{
                        $sqlPartes="INSERT INTO facturas_partes (id_factura, orden_trabajo, codigo, clave_prodserv, descripcion, clave_unidad, cantidad, precio, por_impuesto) 
                        VALUES($folio, '$odv',  '$codigo', '$claveProdServ', '$descripcion', '$unidad', $cantidad, $precio, $impuestos)";
                        $resp = $this->getAllTableLastID($sqlPartes);
                        if($resp){
                            $success=true;
                            $msj='Factura agregado correctamente';
                            $data='Exito';
                        }
                    }
                    
                  
                }
            }   
           

                $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

                return $mensaje;
        }

    }
    function getTabla($razonfactura){
        $success=false;
        $data="Error";
            // $query= 'SELECT * FROM '.$tabla;
            //// modificación Angel Mercado para mostrar facturas de quienFactura  26/abril/2023
            $query= 'SELECT f.id, f.status,  us.Name AS Usuario, m.Nombre AS moneda,cli.Nombre AS cliente, f.total, f.fecha, f.fecha_vencimiento, f.timbrado, f.cancelado, f.enviado, cli.dias_credito, f.razonfactura
            FROM facturas f
            JOIN user_accounts us ON us.Id = f.id_usuario
            JOIN clientes cli ON cli.Id = f.id_cliente
            JOIN moneda m ON m.Id = f.moneda
            WHERE f.nota_credito != 1
            AND razonfactura='.$razonfactura;
            $resp= $this->getAllTable($query);
            
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                $datosBD['id']=(int)$datosBD['id'];
                $diasCredito=0;
                if(!empty($datosBD['dias_credito'])){
                    $diasCredito=$datosBD['dias_credito'];
                }
                $fechaActual = new DateTime();

                // CAMBIO ALEXANDER 04/19

                $datosBD["acciones"] ="
                <div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                            <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["id"] . "' >Editar</a>

                            <a type='button'  class='dropdown-item btnPDFDownloadZip'   id='zp" . $datosBD["id"] . "' >Descargar</a>


                            <a type='button'  class='dropdown-item btnPDFDownloadZipTimbrada'   id='zt" . $datosBD["id"] . "' >Descargar PDF/XML</a>
                            <a type='button'  class='dropdown-item btnDownloadPDFTimbrada'   id='pd" . $datosBD["id"] . "' >Descargar PDF</a>
                            <a type='button'  class='dropdown-item btnViewPDFTimbrada'   id='vd" . $datosBD["id"] . "' >Ver PDF</a>

                            <a type='button'  class='dropdown-item btnCancelarTimbrado'   id='ct" . $datosBD["id"] . "' >Cancelar Timbrado</a>


                            <a type='button'  class='dropdown-item btnFacturaGenerarPDF'   id='gn" . $datosBD["id"] . "' >Descargar PDF</a>
                            <a type='button'  class='dropdown-item btnFacturaVerPDF'   id='gv" . $datosBD["id"] . "' >Ver PDF</a>
                            <a type='button'  class='dropdown-item btnFacturaCancelar'   id='cf" . $datosBD["id"] . "' >Cancelar Factura</a>

                        </div>
                </div>";
                $sqlOdv="SELECT  DISTINCT fp.orden_trabajo AS odt
                FROM facturas_partes fp
                WHERE fp.id_factura = ".$datosBD['id']." ORDER BY odt DESC";
                 $montoNC=0;
                 //Verificamos si tiene nota de credito la factura 
                 $sqlNc="SELECT SUM(f.total) AS totalNC FROM facturas f
                 JOIN facturas_relaciones fr ON f.id = fr.id_factura
                 WHERE fr.tipo = 'NC'
                 AND f.nota_credito = 1
                 AND f.cancelado=0
                 AND fr.cfdi_relacionado=".$datosBD['id']."";
                 $respNC = $this->getRow($sqlNc);
                 if(!empty($respNC['totalNC'])){
                     $montoNC=$respNC['totalNC'];
                 }
                
                $sqlPagos="SELECT SUM(monto) AS total
                FROM pagos_facturas pf
                JOIN pagos p
                ON p.id = pf.id_pago
                WHERE pf.id_factura = ".$datosBD['id']." AND p.status = 1 AND p.timbrado = 1 AND p.cancelado = 0";
                // print_r($sqlNc);                
                $respPago= $this->getRow($sqlPagos);
                $pagos=0;
                if($respPago['total']!=NULL){
                    $pagos=$respPago['total'];
                }
                // print_r("Id Factura".$datosBD['id']);
                // print_r("\n");
                // print_r("Pagos: ".$pagos);
                // print_r("\n");
                // print_r("Monto NC".$montoNC);
                // print_r("\n");
                // print_r("-------------------------------");
                // print_r("\n");
                $pagos= $pagos+$montoNC;
                // print_r("Pagos2: ".$pagos);
                // print_r("\n");
                    $fechaVencimiento = new DateTime($datosBD['fecha']);
                    $fechaVencimiento->modify("+$diasCredito days");
                    $fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
                    $statusPago = '';
                    if ($fechaVencimiento > $fechaActual) {
                        if ($pagos != $datosBD['total']) {
                            $statusPago = 'PENDIENTE';
                        } else {
                            $statusPago = 'COBRADA';
                        }
                    } elseif ($fechaVencimiento < $fechaActual) {
                        if ($pagos != $datosBD['total']) {
                            $statusPago = 'VENCIDA';
                        } else {
                            $statusPago = 'COBRADA';
                        }
                    }
                $datosBD['fecha_vencimiento']=$fechaVencimientoFormatted;
                $datosBD['statusPago']=$statusPago;
                if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 0) {
                    $estilo = '';
                    $datosBD['status'] = 'PENDIENTE';
                }
                if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 1) {
                    $estilo = '#FF9191';
                    $datosBD['status'] = 'CANCELADA';
                }
                if ($datosBD['timbrado'] == 1 && $datosBD['cancelado'] == 0 && $datosBD['enviado'] == 0) {
                    $estilo = '#90EE90';
                    $datosBD['status'] = 'TIMBRADA';
                }
                if ($datosBD['timbrado'] == 1 && $datosBD['enviado'] == 1) {
                    $estilo = '#B5DCE8';
                    $datosBD['status'] = 'ENVIADA';
                }
                $datosBD['estilo']=$estilo;
                $data[] = $datosBD;
             
            }
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla';
            }

        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function getFactura($id)
    {

        $query = "SELECT o.Id,
        DATE(o.Fecha)AS fecha,
        o.Total,
        cli.RFC,
        vp.IvaPorcentual,
        vp.Cantidad, 
        vp.Precio_Litro,
        p.Nombre AS Descripcion,
        p.ClaveProdServSat AS ClaveSAT, 
        tu.unidadSat AS Unidad,
        p.Id AS Codigo5Producto
        FROM odv o 
        INNER JOIN clientes cli ON o.Id_cliente=cli.Id 
        LEFT JOIN venta_producto vp ON vp.Id_odv=o.Id 
        LEFT JOIN productos p	ON p.Id = vp.VendidoComo
        LEFT JOIN tipounidad tu ON tu.Id = p.tipoUnidad
        WHERE o.Status=1 AND o.Id=$id;";

        $resp = $this->getAllTable($query);
        $array=[];
        if (!empty($resp)) {
            foreach ($resp as $value) {
            $claveProdServ=$value['ClaveSAT'];
            $descripcion=$value['Descripcion'];
            $cantidad=$value['Cantidad'];
            $unidad=$value['Unidad'];
            $codigo=$value['Codigo5Producto'];
            $precio=$value['Total'];
            $precioXLitro=$value['Precio_Litro'];
            switch ($value['IvaPorcentual']) {
                case 0:
                $impuestos=".00";
                break;
                case 1:
                $impuestos=".08";
                break;
                case 2:
                $impuestos=".16";
                break;
                
                default:
                    $impuestos=".12";
                    break;
            }
            
            // $data[] = $value;
            $array[] = array(
                'Codigo' => $codigo,
                'ClaveProdServ' => $claveProdServ,
                'Descripcion' => $descripcion,
                'Cantidad' =>  $cantidad,
                'Unidad' => $unidad,
                // 'Precio' => $precio,
                'PrecioXLitro' => $precioXLitro,
                'Impuestos' => $impuestos
              
            );
            // $datos[]=$array;
            }
            $data = $array;
            $msj = 'Exito en consulta de datos';
            $success = true;
        } else {
            $data='Error';
            $msj = "Datos Vacios";
            $success = false;
        }


        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    // function getFactura($id)
    // {

    //     $query = "SELECT o.Id, DATE(o.Fecha) AS fecha,o.Total, cli.RFC, vp.IvaPorcentual FROM odv o
    //     INNER JOIN clientes cli ON o.Id_cliente = cli.Id
    //     LEFT JOIN venta_producto vp ON vp.Id_odv = o.Id
    //     WHERE o.Status=1
    //     AND o.Id =$id;";

    //     $resp = $this->getRow($query);

    //     if (!empty($resp)) {
    //         $claveProdServ='81141601';
    //         $descripcion='SERVICIO';
    //         $cantidad=0;
    //         $unidad=0;
    //         $codigo='SLTR - SERVICIO';
    //         $precio=$resp['Total'];
    //         switch ($resp['IvaPorcentual']) {
    //             case 0:
    //             $impuestos=".00";
    //             break;
    //             case 1:
    //             $impuestos=".08";
    //             break;
    //             case 2:
    //             $impuestos=".16";
    //             break;
                
    //             default:
    //                 $impuestos=".12";
    //                 break;
    //         }
            

    //         // $data[] = $resp;
    //         $array[] = array(
    //             'Codigo' => $codigo,
    //             'ClaveProdServ' => $claveProdServ,
    //             'Descripcion' => $descripcion,
    //             'Cantidad' =>  $cantidad,
    //             'Unidad' => $unidad,
    //             'Precio' => $precio,
    //             'Impuestos' => $impuestos
              
    //         );

    //         $data = $array;
    //         $msj = 'Exito en consulta de datos';
    //         $success = true;
    //     } else {
    //         $data='Error';
    //         $msj = "Datos Vacios";
    //         $success = false;
    //     }


    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;
    // }
    // function getFacturaId($id)
    // {

    //     $query = "SELECT f.id, u.Name AS agente, u.email AS emailAgente,
    //     mn.Nombre AS moneda, f.cuenta_pago, f.email, f.orden, f.subtotal, f.impuesto, 
    //     f.impuesto_retenido, f.total, f.id_cliente, f.fecha, f.observaciones,
    //     fpago.Nombre AS forma_pago,
    //     CONCAT(mp.Id,' - ',mp.Nombre) AS metodoPago,
    //     CONCAT(cfdi.Codigo,' - ',cfdi.Nombre) AS uso_cfdi,
    //     CONCAT(cfdiRc.Id,' - ',cfdiRc.Nombre) AS  cfdi_relacionado,
    //     CONCAT(cli.RFC,' - ',cli.RazonSocial ) AS  nombreCliente,
    //     CASE f.`status`
    //   		WHEN 1 THEN 'Pendiente'
    //   		WHEN 2 THEN 'Cobrada'
	// 	  END AS estatus
    //                 FROM facturas f
    //                 LEFT JOIN user_accounts u ON u.Id = f.id_usuario
    //                 LEFT JOIN forma_pago fpago ON fpago.Id = f.forma_pago
    //                 LEFT JOIN metodo_pago mp ON mp.Id = f.metodo_pago
    //                 LEFT JOIN usocfdi cfdi ON cfdi.Id = f.uso_cfdi
    //                 LEFT JOIN cfdi_relacionado cfdiRc ON cfdiRc.Id = f.cfdi_relacionado 
    //                 LEFT JOIN moneda mn ON mn.Id = f.moneda
    //                 LEFT JOIN clientes cli ON cli.Id = f.id_cliente
    //   	WHERE f.cancelado =0 
    //   	AND f.timbrado=1
    //     AND f.id =$id;";

    //     $resp = $this->getRow($query);

    //     if (empty($resp)) {

    //         $data='Error';
    //         $msj = "Datos Vacios";
    //         $success = false;
            
    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;
    //     } 
    //     $data = $resp;
    //     $msj = 'Exito en consulta de datos';
    //     $success = true;

    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;
    // }
    // function getFacturaId($id)
    // {
    //     if(!empty($id)){
    //         $success = false;
    //         $data = "Error";
    
    //         $sql = "SELECT c.Id AS idCliente, c.RegimenFiscal AS regimenFiscal, f.Moneda AS moneda, f.tipo_cambio AS tc, f.id AS idFactura, 
    //                        f.uso_cfdi AS usocfdi, f.metodo_pago AS metodoPago, f.forma_pago AS formaPago, f.observaciones, f.subtotal, f.impuesto_retenido, f.impuesto, f.total
    //                 FROM facturas f
    //                 INNER JOIN clientes c ON c.Id = f.Id_cliente
    //                 WHERE f.Id =".$id;
    //         $resp = $this->getRow($sql);
    //         $arrayCliente= array(
    //             'idCliente'     => $resp['idCliente'    ],
    //             'regimenFiscal' => $resp['regimenFiscal'],
    //             'moneda'        => $resp['moneda'       ],
    //             'tc'            => $resp['tc'           ],
    //             'usoCFDI'       => $resp['usocfdi'      ],
    //             'metodoPago'    => $resp['metodoPago'   ],
    //             'formaPago'     => $resp['formaPago'    ],
    //             'folio'         => $resp['idFactura'    ]
    //         );
    //         $arrayTotales=array(
    //             "observaciones"     => $resp['observaciones'    ],
    //             "subtotal"          => $resp['subtotal'         ],
    //             "impuestoRetenido"  => $resp['impuesto_retenido'],
    //             "impuestoTrasladado"=> $resp['impuesto'         ],
    //             "total"             => $resp['total'            ]
    //         );
    //         $data = [];
    //         // $data = $resp;
    //         $data['cliente'] = $arrayCliente;
    //         $data['totales'] = $arrayTotales;
    //         $data['pedidos'] = array()          ;
    //         $data['cfdis'  ] = array()          ; 
    //         $sql = "SELECT fp.orden_trabajo AS pedido, CONCAT('SLTR - SERVICIO') AS codigo, 
    //         CONCAT('81141601') AS claveProServ, fp.descripcion AS descripcion ,fp.cantidad AS cantidad,fp.precio AS precio,(fp.cantidad* fp.precio) AS total,
    //         fp.por_impuesto AS impuesto, fp.clave_unidad AS unidad, fp.id AS idFacturaPedido
    //         FROM facturas_partes fp
    //         WHERE fp.id_factura = $id 
    //         GROUP BY (fp.orden_trabajo)";
    //         // print_r($sql);
    //         $resp2 = $this->getAllTable($sql);
    //         $cantidad=$resp2->num_rows;
    //         // print_R($cantidad);
    //         if($cantidad>0){
    //             while ($datosBD = $resp2->fetch_assoc()) {
    //                 switch ($datosBD['impuesto']) {
    //                     case '0':
    //                     $datosBD['impuesto']=".00";
    //                     break;
    //                     case '0.08':
    //                     $datosBD['impuesto']=".08";
    //                     break;
    //                     case '0.16':
    //                     $datosBD['impuesto']=".16";
    //                     break;
    //                     case '0.12':
    //                         $datosBD['impuesto']=".12";
    //                     break;

    //                 }
    //                 $data2[] = $datosBD;
    //             }
    //         $data['pedidos'] = $data2;

    //         }

   
    //         $sqlCfdi="SELECT fr.id AS idRelacional, fr.id_factura AS folio, fr.cfdi_relacionado AS cfdiRelacionado, DATE(f.fecha) AS date,
    //         CONCAT(fr.id_factura, ' ',cli.Nombre,' $',f.total,' ',CASE f.moneda WHEN 1 THEN 'MXN'
    //                       WHEN 2 THEN 'USD' END) AS text
    //         FROM facturas_relaciones fr
    //         JOIN facturas f ON f.id=fr.id_factura
    //         JOIN clientes cli ON cli.Id = f.id_cliente
    //         WHERE f.id=$id";
    //         $respCfdi = $this->getAllTable($sqlCfdi);
    //         $cantidadCfdi=$respCfdi->num_rows;
    //         if($cantidadCfdi>0){
    //             while ($dataCfdi = $respCfdi->fetch_assoc()) {
    //                 $data3[]=$dataCfdi;
    //             }
    //         $data['cfdis'  ] = $data3;

    //         }
 
    //         if ($resp == true) {
    //             $success = true;
    //             $msj = 'Exito en consulta de datos';
    //             // $data=$data;
    //         } else {
    //             $msj = 'Error al consultar regimen_fiscal';
    //         }
    
    //         $mensaje = array("success" => $success, "data" => $data, "message" => $msj);
    
    //         return $mensaje;
    
    //        }
    // }
    function getFacturaId($id)
    {
        if(!empty($id)){
            $success = false;
            $data = "Error";
    
            $sql = "SELECT c.Id AS idCliente,f.id , c.RegimenFiscal AS regimenFiscal, f.Moneda AS moneda, f.tipo_cambio AS tc, f.id AS idFactura, 
                           f.uso_cfdi AS usocfdi, f.metodo_pago AS metodoPago, f.forma_pago AS formaPago, f.observaciones, f.subtotal, f.impuesto_retenido, f.impuesto, f.total,f.razonfactura
                    FROM facturas f
                    INNER JOIN clientes c ON c.Id = f.Id_cliente
                    WHERE f.Id =".$id;
            $resp = $this->getRow($sql);
            $arrayCliente= array(
                'idCliente'     => $resp['idCliente'    ],
                'regimenFiscal' => $resp['regimenFiscal'],
                'moneda'        => $resp['moneda'       ],
                'tc'            => $resp['tc'           ],
                'usoCFDI'       => $resp['usocfdi'      ],
                'metodoPago'    => $resp['metodoPago'   ],
                'formaPago'     => $resp['formaPago'    ],
                'razonfactura'     => $resp['razonfactura'    ],
                'folio'     => $resp['id'    ]
            );
            $arrayTotales=array(
                "observaciones"     => $resp['observaciones'    ],
                "subtotal"          => $resp['subtotal'         ],
                "impuestoRetenido"  => $resp['impuesto_retenido'],
                "impuestoTrasladado"=> $resp['impuesto'         ],
                "total"             => $resp['total'            ]
            );
            $data = [];
            // $data = $resp;
            $data['cliente'] = $arrayCliente;
            $data['totales'] = $arrayTotales;
            $data['pedidos'] = array()          ;
            $data['cfdis'  ] = array()          ; 
            // $sql = "SELECT p.id AS pedido, CONCAT('SLTR - SERVICIO') AS codigo, 
            // CONCAT('81141601') AS claveProServ, pro2.Nombre AS descripcion ,vp.Cantidad AS cantidad,vp.Precio_Litro AS precio,vp.Total AS total,
            // vp.IvaPorcentual AS impuesto,p.observaciones, tpu.unidadSat AS unidad, fp.id AS idFacturaPedido
            // FROM odv p
            // INNER JOIN venta_producto vp ON vp.Id_odv = p.Id
            // INNER JOIN productos pro ON pro.Id = vp.Id_producto
            // INNER JOIN tipounidad tpu ON tpu.Id = pro.tipoUnidad
            // INNER JOIN productos pro2 ON pro2.Id= vp.VendidoComo
            // LEFT JOIN facturas_partes fp ON fp.orden_trabajo = p.Id
            // WHERE fp.id_factura = $id
            // GROUP BY (p.id)";
             $sql = "SELECT fp.orden_trabajo AS pedido,
            fp.codigo AS codigo,
            fp.clave_prodserv  AS claveProServ,
            fp.descripcion AS descripcion,
            fp.cantidad AS cantidad,
            fp.precio AS precio,
            (fp.cantidad*fp.precio)AS total,
            fp.por_impuesto AS impuesto,
            fp.clave_unidad AS unidad,
            fp.id AS idFacturaPedido FROM facturas_partes fp WHERE fp.id_factura= $id";
            // print_r($sql);
            $resp2 = $this->getAllTable($sql);
            $cantidad=$resp2->num_rows;
            // print_R($cantidad);
            if($cantidad>0){
                while ($datosBD = $resp2->fetch_assoc()) {
                    switch ($datosBD['impuesto']) {
                        case '0':
                        $datosBD['impuesto']=".00";
                        break;
                        case '0.08':
                        $datosBD['impuesto']=".08";
                        break;
                        case '0.16':
                        $datosBD['impuesto']=".16";
                        break;
                        case '0.12':
                            $datosBD['impuesto']=".12";
                        break;

                    }
                    $data2[] = $datosBD;
                }
            $data['pedidos'] = $data2;

            }

   
            $sqlCfdi="SELECT fr.id AS idRelacional, fr.id_factura AS folio, fr.cfdi_relacionado AS cfdiRelacionado, DATE(f.fecha) AS date,
            CONCAT(fr.id_factura, ' ',cli.Nombre,' $',f.total,' ',CASE f.moneda WHEN 1 THEN 'MXN'
                          WHEN 2 THEN 'USD' END) AS text
            FROM facturas_relaciones fr
            JOIN facturas f ON f.id=fr.id_factura
            JOIN clientes cli ON cli.Id = f.id_cliente
            WHERE f.id=$id";
            $respCfdi = $this->getAllTable($sqlCfdi);
            $cantidadCfdi=$respCfdi->num_rows;
            if($cantidadCfdi>0){
                while ($dataCfdi = $respCfdi->fetch_assoc()) {
                    $data3[]=$dataCfdi;
                }
            $data['cfdis'  ] = $data3;

            }
 
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                // $data=$data;
            } else {
                $msj = 'Error al consultar regimen_fiscal';
            }
    
            $mensaje = array("success" => $success, "data" => $data, "message" => $msj);
    
            return $mensaje;
    
           }
    }
    function getUsoCfdi()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM usocfdi";
        $resp = $this->getAllTable($sql);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $data[] = $datosBD;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar usocfdi ';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function deleteCFDIrelacionados($id)
    {
        $success = false;
        $data = "Error";

        $sql = "DELETE FROM facturas_relaciones WHERE id=$id";
        $resp = $this->deleteRow($sql);

        if ($resp == true) {
            $success = true;
            $msj = 'Exito al eliminar de datos';
            $data='Exito';
        } else {
            $msj = 'Error al eliminar usocfdi ';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function cfdi_relacionado()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM cfdi_relacionado";
        $resp = $this->getAllTable($sql);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $data[] = $datosBD;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar cfdi_relacionado ';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getMetodoPago()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM metodo_pago";
        $resp = $this->getAllTable($sql);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $data[] = $datosBD;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar metodo_pago ';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getFormaPago()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM forma_pago";
        $resp = $this->getAllTable($sql);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $data[] = $datosBD;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar forma_pago';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function regimenFiscal()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM regimen_fiscal";
        $resp = $this->getAllTable($sql);
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $data[] = $datosBD;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar regimen_fiscal';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }

    // function gePedido($idPedido)
    // {
    //    if(!empty($idPedido)){
    //     $success = false;
    //     $data = "Error";

    //     $sql = "SELECT c.Id AS idCliente, c.RegimenFiscal, p.Moneda,p.TipoCambio FROM odv p
    //     INNER JOIN clientes c ON c.Id = p.Id_cliente
    //     WHERE p.Id =".$idPedido;
    //     $resp = $this->getRow($sql);
        
    //     $data = [];
    //     $data = $resp;
    //     $data['pedidos'] = 'pedidos'; 
    //     $sql = "SELECT p.id, CONCAT('SLTR - SERVICIO') AS codigo, CONCAT('81141601') AS claveProServ, p.Observaciones AS descripcion ,vp.Cantidad,vp.Precio_Litro AS precio,vp.Total,vp.IvaPorcentual,tpu.unidadSat AS unidad,p.observaciones
    //     FROM odv p
    //     INNER JOIN venta_producto vp ON vp.Id_odv = p.Id
    //     INNER JOIN productos pro ON pro.Id = vp.Id_producto
    //     INNER JOIN tipounidad tpu ON tpu.Id = pro.tipoUnidad
    //     WHERE p.Id=".$idPedido;
    //     $resp2 = $this->getAllTable($sql);
    //     while ($datosBD = $resp2->fetch_assoc()) {
    //         switch ($datosBD['IvaPorcentual']) {
    //             case 0:
    //             $datosBD['IvaPorcentual']=".00";
    //             break;
    //             case 1:
    //             $datosBD['IvaPorcentual']=".08";
    //             break;
    //             case 2:
    //             $datosBD['IvaPorcentual']=".16";
    //             break;
                
    //             default:
    //             $datosBD['IvaPorcentual']=".12";
    //             break;
    //         }
    //         $data2[] = $datosBD;
    //     }
    //     $data['pedidos'] = $data2;
    //     if ($resp == true) {
    //         $success = true;
    //         $msj = 'Exito en consulta de datos';
    //         // $data=$data;
    //     } else {
    //         $msj = 'Error al consultar regimen_fiscal';
    //     }

    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;

    //    }
    // }
    function gePedido($idPedido)
    {
       if(!empty($idPedido)){
        $success = false;
        $data = "Error";

        $sql = "SELECT c.Id AS idCliente, c.RegimenFiscal, p.Moneda,p.TipoCambio 
        FROM odv p
        INNER JOIN clientes c ON c.Id = p.Id_cliente
        WHERE p.Id =".$idPedido;
        $resp = $this->getRow($sql);
        
        $data = [];
        $data = $resp;
        $data['pedidos'] = 'pedidos'; 
        $sql = "SELECT p.id, CONCAT('SLTR - SERVICIO') AS codigo, 
        CONCAT('81141601') AS claveProServ, pro2.Nombre AS descripcion ,vp.Cantidad,vp.Precio_Litro AS precio,vp.Total,vp.IvaPorcentual,
        tpu.unidadSat AS unidad,p.observaciones
        FROM odv p
        INNER JOIN venta_producto vp ON vp.Id_odv = p.Id
        INNER JOIN productos pro ON pro.Id = vp.Id_producto
        INNER JOIN tipounidad tpu ON tpu.Id = pro.tipoUnidad
        INNER JOIN productos pro2 ON pro2.Id= vp.VendidoComo
        WHERE p.Id=".$idPedido;
        $resp2 = $this->getAllTable($sql);
        while ($datosBD = $resp2->fetch_assoc()) {
            switch ($datosBD['IvaPorcentual']) {
                case 0:
                $datosBD['IvaPorcentual']=".00";
                break;
                case 1:
                $datosBD['IvaPorcentual']=".08";
                break;
                case 2:
                $datosBD['IvaPorcentual']=".16";
                break;
                
                default:
                $datosBD['IvaPorcentual']=".12";
                break;
            }
            $data2[] = $datosBD;
        }
        $data['pedidos'] = $data2;
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            // $data=$data;
        } else {
            $msj = 'Error al consultar regimen_fiscal';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;

       }
    }

    // ---------------------------- 

    function getDataFactura($idFactura){


        // $idFactura  =18;
 
        $query="SELECT f.id, u.Name AS agente, u.email AS emailAgente,
        mn.Nombre AS moneda, f.cuenta_pago, f.email, f.orden, f.subtotal, f.impuesto, 
        f.impuesto_retenido, f.total, f.id_cliente, f.fecha, f.observaciones,
        fpago.Nombre AS forma_pago,
        CONCAT(mp.Id,' - ',mp.Nombre) AS metodoPago,
        CONCAT(cfdi.Codigo,' - ',cfdi.Nombre) AS uso_cfdi,
        CONCAT(cfdiRc.Id,' - ',cfdiRc.Nombre) AS  cfdi_relacionado
                    FROM facturas f
                    LEFT JOIN user_accounts u ON u.Id = f.id_usuario
                    LEFT JOIN forma_pago fpago ON fpago.Id = f.forma_pago
                    LEFT JOIN metodo_pago mp ON mp.Id = f.metodo_pago
                    LEFT JOIN usocfdi cfdi ON cfdi.Id = f.uso_cfdi
                    LEFT JOIN cfdi_relacionado cfdiRc ON cfdiRc.Id = f.cfdi_relacionado 
                    LEFT JOIN moneda mn ON mn.Id = f.moneda
                    WHERE f.id = $idFactura";

        $datos= $this->getAllTable($query);
        $datos=$datos->fetch_assoc();

        // FORMA DE PAGO  OK
        if ($datos['forma_pago'] == 'PARCIALIDADES O DIFERIDO') {
			$forma_pago = 'PPD - PAGO PARCIALIDADES O DIFERIDO';
		} elseif ($datos['forma_pago'] == 'UNA SOLA EXHIBICION') {
			$forma_pago = 'PUE - PAGO EN UNA SOLA EXHIBICION';
		}
        // --------------------- 
        $cuenta_pago = $datos['cuenta_pago']; //ok (En mi bdd vine vacio)

        $metodoPago=$datos['metodoPago'];

        $usoCfdi=$datos['uso_cfdi'];

        // --------------------------------------------------Verifacar si trae CFDIS RELACIONADOS
        $htmlCfdisRelacionados='';
        if($datos['cfdi_relacionado']){

            $htmlPartidasCfdis='';

            $cfdiRelacionadoMotivo=$datos['cfdi_relacionado'];

            $queryCFDISRelacionados="SELECT cfdi_relacionado FROM facturas_relaciones WHERE id_factura =$idFactura";
            $responseCFDISRelacionados= $this->getAllTable($queryCFDISRelacionados);

            foreach ($responseCFDISRelacionados as $datos2) {

                $file = file_get_contents("../../data/xml/" . $datos2['cfdi_relacionado'] . "_timbrado.xml");
				$file = json_decode($file);
				$uuid = $file->data->uuid;
				$htmlPartidasCfdis .= '
				    <tr>
						<td style="text-align: center;">' . $cfdiRelacionadoMotivo . '</td>
						<td style="text-align: center;">' . strtoupper($uuid) . '</td>
			        </tr>
			    ';

            }


            $htmlCfdisRelacionados = '
            <br />
            <table style="font-size: 7px; text-align: left;" border="0" cellpadding="2" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="width: 100%; text-align: center; background-color: #B7360D; font-family: \'Futura\'; font-weight: bold; color: #FFF; font-size: 9px;"><h5>CFDIs Relacionados</h5></td>
                    </tr>
                    <tr>
                        <td style="width: 50%; text-align: center; background-color: #D8572A; font-family: \'Futura\'; font-weight: bold; color: #FFF;">Tipo de Relación</td>
                        <td style="width: 50%; text-align: center; background-color: #D8572A; font-family: \'Futura\'; font-weight: bold; color: #FFF;">UUID</td>
                    </tr>
                    ' . $htmlPartidasCfdis . '
                </tbody>
            </table>
            <br />
        ';

        }
        

        // ------------------------------------------------------ 



        $email = $datos['email']; //OK
		$orden = $datos['orden']; //No necesario
		$folio = $datos['id']; //Ok 

		$fechaCreacion = $datos['fecha'];
		
        // Usuario 
        $agente = $datos['agente'];
		$agenteCorreo = $datos['emailAgente'];




        //------------------- Balance 
		$moneda = $datos['moneda'];
		$impuestoRetenido = number_format($datos['impuesto_retenido'], 2, '.', ',');
		$subtotal = number_format($datos['subtotal'], 2, '.', ',');
		$impuesto = number_format($datos['impuesto'], 2, '.', ',');
		$total = number_format($datos['total'], 2, '.', ',');

        $totalLetras = $this->writeToNumber($datos['total'], $moneda);

        // ------------------- 
        

        $idCliente = $datos['id_cliente'];
		
        // $nombreSolicitante = $datos['nombre']; // VEFICAR PORQUE EN EL ARCHIVO ESTA MAL ESCRITO;

		// $telefonoSolicitante = $datos['telefono']; //VEFICAR PORQUE EN EL ARCHIVO ESTA MAL ESCRITO Y NO APARECE EN EL QUERY;
		// $correoSolicitante = $datos['correo'];//VEFICAR PORQUE EN EL ARCHIVO ESTA MAL ESCRITO Y NO APARECE EN EL QUERY;

        // -------------------VERIFICAR PORQUE FACTURACION NO TRAE ODT

        if (empty($orden)) {
			$observaciones = $datos['observaciones'];
		} else {
			$observaciones = 'REFERENCIA CLIENTE: ' . $orden;
			if (!empty($datos['observaciones'])) {
				 $observaciones .= ' | NOTAS ADICIONALES: ' . $datos['observaciones'];
			}
		}


        // -----------------------------------API ARRAY DATA 


        // -------------------------- CONCEPTOS

        $queryConceptos="SELECT * FROM facturas_partes cp
        WHERE cp.id_factura = $idFactura ORDER BY cp.id ASC";

        $responseConceptos= $this->getAllTable($queryConceptos);

        $impuestosTrasladados0 = 0;
		$impuestosTrasladados8 = 0;
		$impuestosTrasladados16 = 0;
		$impuestosRetenidos = 0;
		$htmlPartidas = [];

        foreach ($responseConceptos as $datos2) {

            // NOTA VERICAR CLAVE UNIDAD (NOSE ENCUENTRA LA TABLA) (si se encuentra con otro nombre)
            switch ($datos2['clave_unidad']) {
				case '1': $claveUnidad = 'KGM'; break;
                case '2': $claveUnidad = 'LTR'; break;
				case '3': $claveUnidad = 'H87'; break;
				case '4': $claveUnidad = 'KT'; break;

			}

            // $claveUnidad = 'VERIFICAR'
            if ($datos2['por_impuesto'] == .12) {
				$porImpuesto = .16;
				$impuestosRetenidos += ($datos2['cantidad']*$datos2['precio'])*.04;
				$impuestosTrasladados16 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto - ($datos2['cantidad']*$datos2['precio'])*.04;
			} else {
				if ($datos2['por_impuesto'] == .00) {
					$porImpuesto = $datos2['por_impuesto'];
					$impuestosTrasladados0 = 1;
				}
				if ($datos2['por_impuesto'] == .08) {
					$porImpuesto = $datos2['por_impuesto'];
					$impuestosTrasladados8 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
				}
				if ($datos2['por_impuesto'] == .16) {
					$porImpuesto = $datos2['por_impuesto'];
					$impuestosTrasladados16 += ($datos2['cantidad']*$datos2['precio'])*$porImpuesto;
				}
			}

            // --- 

			// $htmlPartidas .= '<tr>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $datos2['orden_trabajo'] . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $datos2['folio'] . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $datos2['codigo'] . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $datos2['clave_prodserv'] . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . utf8_encode($datos2['descripcion']) . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . number_format((float)$datos2['cantidad'], 0, '.', ',') . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $claveUnidad . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">' . $datos2['clave_unidad'] . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">$ ' . number_format((float)$datos2['precio'], 2, '.', ',') . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">$ ' . number_format((float) ($datos2['cantidad']*$datos2['precio'])*$porImpuesto, 2, '.', ',') . '</td>';
			// $htmlPartidas .= '<td style="text-align: center;">$ ' . number_format((float)$datos2['cantidad']*$datos2['precio'], 2, '.', ',') . '</td>';
			// $htmlPartidas .= '</tr>';
            // -- 

            $htmlPartidas[]=[
            'orden_trabajo'=>$datos2['orden_trabajo'],
            'folio'=>$datos2['folio'],
            'codigo'=>$datos2['codigo'],
            'clave_prodserv'=>$datos2['clave_prodserv'],
            'descripcion'=>utf8_encode($datos2['descripcion']),
            'cantidad'=>number_format((float)$datos2['cantidad'], 0, '.', ','),
            'claveUnidadSAT'=>$claveUnidad,
            'claveUnidad'=>$datos2['clave_unidad'],
            'precio'=>'$'.number_format((float)$datos2['precio'], 2, '.', ','),
            'impuesto'=>'$'.number_format((float) ($datos2['cantidad']*$datos2['precio'])*$porImpuesto, 2, '.', ','),
            'importe'=>'$'.number_format((float)$datos2['cantidad']*$datos2['precio'], 2, '.', ','),

            ];
        };







        // -------------------------------------------------------------------- 
        

        // Desglose de impuestos
		$infoImpuestos = [];
		// $htmlPartidasImpuestos = '';
		$letraFlag = 0;
		if ($impuestosRetenidos != 0) {
			if ($letraFlag == 0) {
				$totalLetrasHtml = $totalLetras;
				$letraFlag = 1;
			}
			// $infoImpuestos .= '
			// 	<tr>
			// 		<td style="font-size: 9px; width: 375px; text-align: center;">
			// 		' . $totalLetrasHtml . '
			// 		</td>
			// 		<td style="font-size: 9px; text-align: right; width: 100px; background-color: #B7360D; color: #FFF;">
			// 			<span style="font-weight: bold;">4% Retención:</span>
			// 		</td>
			// 		<td style="font-size: 9px; width: 60px; text-align: right;">
			// 			$ ' . number_format((float)$impuestosRetenidos, 2, '.', ',') . '
			// 		</td>
			// 	</tr>
			// ';

            $infoImpuestos[]=[
                "importeLetra"=>$totalLetrasHtml,
                "title"=>"4% Retención:",
                "cantidad"=> "$". number_format((float)$impuestosRetenidos, 2, '.', ',')
            ];


		}
		if ($impuestosTrasladados0 == 1) {
			if ($letraFlag == 0) {
				$totalLetrasHtml = $totalLetras;
				$letraFlag = 1;
			}
			// $infoImpuestos .= '
			//     <tr>
			// 		<td style="font-size: 9px; width: 375px; text-align: center;">
			// 		' . $totalLetrasHtml . '
			// 		</td>
			// 		<td style="font-size: 9px; text-align: right; width: 100px; background-color: #B7360D; color: #FFF;">
			// 			<span style="font-weight: bold;">IVA 0% Traslado:</span>
			// 		</td>
			// 		<td style="font-size: 9px; width: 60px; text-align: right;">
			// 			$ 0.00
			// 		</td>
			// 	</tr>
			// ';

            $infoImpuestos[]=[
                "importeLetra"=>$totalLetrasHtml,
                "title"=>"IVA 0% Traslado:",
                "cantidad"=> "$ 0.00"
            ];

            
 
        }
		if ($impuestosTrasladados8 != 0) {
			if ($letraFlag == 0) {
				$totalLetrasHtml = $totalLetras;
				$letraFlag = 1;
			}
			// $infoImpuestos .= '
			//     <tr>
			// 		<td style="font-size: 9px; width: 375px; text-align: center;">
			// 		' . $totalLetrasHtml . '
			// 		</td>
			// 		<td style="font-size: 9px; text-align: right; width: 100px; background-color: #B7360D; color: #FFF;">
			// 			<span style="font-weight: bold;">IVA 8% Traslado:</span>
			// 		</td>
			// 		<td style="font-size: 9px; width: 60px; text-align: right;">
			// 			$ ' . number_format((float)$impuestosTrasladados8, 2, '.', ',') . '
			// 		</td>
			// 	</tr>
			// ';
		

            
            $infoImpuestos[]=[
                "importeLetra"=>$totalLetrasHtml,
                "title"=>"IVA 8% Traslado:",
                "cantidad"=>"$".number_format((float)$impuestosTrasladados8, 2, '.', ',')
            ];

        }
		if ($impuestosTrasladados16 != 0) {
			if ($letraFlag == 0) {
				$totalLetrasHtml = $totalLetras;
				$letraFlag = 1;
			}

            $infoImpuestos[]=[
                "importeLetra"=>$totalLetrasHtml,
                "title"=>"IVA 16% Traslado:",
                "cantidad"=>"$".number_format((float)$impuestosTrasladados16, 2, '.', ',')
            ];

			// $infoImpuestos .= '
			//     <tr>
			// 		<td style="font-size: 9px; width: 375px; text-align: center;">
			// 		' . $totalLetrasHtml . '
			// 		</td>
			// 		<td style="font-size: 9px; text-align: right; width: 100px; background-color: #B7360D; color: #FFF;">
			// 			<span style="font-weight: bold;">IVA 16% Traslado:</span>
			// 		</td>
			// 		<td style="font-size: 9px; width: 60px; text-align: right;">
			// 			$ ' . number_format((float)$impuestosTrasladados16, 2, '.', ',') . '
			// 		</td>
			// 	</tr>
			// ';
		}




        // -------------------------------------------------------------------Datos Cliente



        $queryCliente="SELECT RazonSocial, RFC, RegimenFiscal, ContactoPrincipal, Telefono, CorreoElectronico, CalleCliente, CiudadCliente, ColoniaCliente, EstadoCliente, PaisCliente, CPCliente
        FROM clientes
        WHERE id = $idCliente";



        $responseCliente= $this->getAllTable($queryCliente);
        $responseCliente=$responseCliente->fetch_assoc();

        $cliente = $responseCliente['RazonSocial'];
		$rfc = $responseCliente['RFC'];
		$regimenfiscalCode = $responseCliente['RegimenFiscal'];
		$nombreCalle = utf8_decode($responseCliente['CalleCliente']);
		$colonia = utf8_decode($responseCliente['ColoniaCliente']);
		$pais = utf8_decode($responseCliente['PaisCliente']);
		$estado = utf8_decode($responseCliente['EstadoCliente']);
		$ciudad = utf8_decode($responseCliente['CiudadCliente']);
		$cp = utf8_decode($responseCliente['CPCliente']);
		$clienteDireccion = utf8_encode("$nombreCalle $colonia $ciudad $estado $pais $cp");

        



        $arrayData=[
            "forma_pago"=>$forma_pago,
            "cuenta_pago"=>$cuenta_pago,
            "metodoPago"=>$metodoPago,
            "usoCfdi"=>$usoCfdi,
            "htmlCfdisRelacionados"=>$htmlCfdisRelacionados,
            "email"=>$email,
            "orden"=>$orden,
            "folio"=>$folio,
            "fechaCreacion"=>$fechaCreacion,
            "agente"=>$agente,
            "agenteCorreo"=>$agenteCorreo,

            "balance"=>[
                "moneda"=>$moneda,
                "impuestoRetenido"=>$impuestoRetenido,
                "subtotal"=>$subtotal,
                "impuesto"=>$impuesto,
                "total"=>$total,
                "totalLetras"=>$totalLetras
            ],

            "idCliente"=>$idCliente,
            // "nombreSolicitante"=>$nombreSolicitante,
            // "telefonoSolicitante"=>$telefonoSolicitante,
            // "correoSolicitante"=>$correoSolicitante,
            
            "observaciones"=>$observaciones,

            "products"=>$htmlPartidas,

            "statusInpuestos"=>$infoImpuestos,

            "datosCliente"=>[
                "cliente"=>$cliente,
                "rfc"=>$rfc,
                "regimenfiscalCode"=>$regimenfiscalCode,
                "nombreCalle"=>$nombreCalle,
                "colonia"=>$colonia,
                "pais"=>$pais,
                "estado"=>$estado,
                "ciudad"=>$ciudad,
                "cp"=>$cp,
                "clienteDireccion"=>$clienteDireccion,    
            ],

            "company"=>[
                'name' => '5INCO TRADING',
                'direction' => 'PROLONGACIÓN ELOTE #90, LAS HUERTAS 1RA SECCIÓN
                TIJUANA, BAJA CALIFORNIA, CP. 22116',
                'cell' => '664 396 0114',
                'phone' => '689 0345'
            ],

            "namePDF"=>'Fatura-'.date('d-m-Y', time())
    
            
        ];

        // print_r($arrayData);


        return array("success" => true,"data"=>$arrayData, "message" =>'Consulta Exitosamente',"items"=>[]);

        // TRAE SUS CONCEPTOS 
        // $queryFactura="SELECT 
        // ft.id,ft.subtotal,ft.total as totolGeneral, ft.impuesto, ft.impuesto_retenido,
        // ( ft.impuesto - ft.impuesto_retenido) AS impuestoTotal , pt.cantidad as lot,
        // pt.precio as unitPrice, ft.tipo_cambio, ft.id_cliente, ft.fecha,
        // pt.clave_prodserv,pt.clave_unidad, ft.moneda
        // FROM facturas ft 
        // INNER JOIN facturas_partes pt ON pt.id_factura = ft.id  
        // WHERE ft.id=$idFactura";


        // $responseFactura= $this->getAllTable($queryFactura);

        // $arrayData=[];

        // while ($item = $responseFactura->fetch_assoc()) {

        //     $item['name']='Verificar';
        //     // $item['um']='Verificar';


        //     $item['amount']=$item['lot']*$item['unitPrice'];
            
        //     $arrayData[] = $item;

        // };


        // // print_r($arrayData);
        // // return;
        // //         [
        // //             'name' => 'THINNER LACQUER 020320',
        // //             'lot' => 1000,
        // //             'um' => 'CUBETA',
        // //             'unitPrice' => 2500,
        // //             'amount' => 50000
        //         //     'iva'=>333
        // //         ],
        // //**********Balance

        // $importe = $arrayData[0]['totolGeneral'];
        // $subTotal = $arrayData[0]['subtotal'];
        // $impuestoTotal = $arrayData[0]['impuestoTotal'];
        // $moneda=$arrayData[0]['moneda']==1?'Pesos':'Dolares';

        // $arrayBalance = [
        //     'total' => floatval($importe),
        //     'subtotal' => floatval($subTotal),
        //     'iva' => floatval($impuestoTotal),
        //     'totalLetra' =>$this->writeToNumber(floatval($importe),$moneda)

        // ];

        // //**********

        // // 'tc'=>floatval($arrayData[0]['tipo_cambio']),      
        // $arrayHeader=[
        //     'serie'=>'Preguntar',
        //     'expedido'=>'Preguntar',
        //     'date'=>($this->formatoFechaVista(explode(' ',$arrayData[0]['fecha'])[0] )),
        // ];

            
        // $arrayCompany=[
        //     'name' => '5INCO TRADING',
        //     'direction' => 'PROLONGACIÓN ELOTE #90, LAS HUERTAS 1RA SECCIÓN
        //     TIJUANA, BAJA CALIFORNIA, CP. 22116',
        //     'cell' => '664 396 0114',
        //     'phone' => '689 0345'
        // ];



        // $idCliente=$arrayData[0]['id_cliente'];

        // // DATOS CLIENTE 
        // $queryCliente="SELECT 
        // ct.Nombre,ct.RFC, ct.PaisCliente AS pais,
        // ct.EstadoCliente AS estado, ct.CiudadCliente AS ciudad,
        // ct.ColoniaCliente AS colonia, ct.CalleCliente AS calle, ct.CPCliente, ct.Ext
        // FROM facturas ft INNER JOIN clientes ct ON ct.Id = ft.id_cliente
        // WHERE ft.id=".$idCliente;


        // $responseCliente= $this->getAllTable($queryCliente);
        // $responseCliente=$responseCliente->fetch_assoc();

        // $arrayCliente=[
        //     'name'=>$responseCliente['Nombre'],
        //     'direction'=>$responseCliente['calle']. ' Ext: '.$responseCliente['Ext'].' Int: NoAlmacenado'.' C.P:'.$responseCliente['CPCliente'],
        //     'rfc'=>$responseCliente['RFC']
        // ];



        // $arrayHeader['company']=$arrayCompany;
        // $arrayHeader['client']=$arrayCliente;

        // $arrayHeader['products']=$arrayData;

        // $arrayHeader['balance']=$arrayBalance;

        // $arrayHeader['namePDF']='Fatura-'.date('d-m-Y', time());


        // return array("success" => true,"data"=>$arrayHeader, "message" =>'Consulta Exitosamente',"items"=>[]);
    }

    function writeToNumber($number, $currency)
    {
        $formatter = new NumeroALetras();
        $decimals = 2;
        return $formatter->toMoney($number, $decimals, $currency, 'CENTAVOS');
    }
    public function cancelarFactura($id){
        // Datos de Factura
        $success = false;
        $data = "Error";
        $msj = '';
        $sql = "UPDATE facturas set  cancelado = 1 where id =".$id;
        // print_r($sql);
        $resp = $this->updateTable($sql);
        if($resp == 1){
            $msj = 'Exito en consulta de datos';
            $data = true;
            $success = true;
        }else{
            $msj = "Fallo en la actualización";
        }
        // while ($datosBD = $resp->fetch_assoc()) {
        //     $data[] = $datosBD;
        // }
        // if ($resp == true) {
        //     $success = true;
        //     $msj = 'Exito en consulta de datos';
        //     // $data=$data;
        // } else {
        //     $msj = 'Error al consultar regimen_fiscal';
        // }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
        // // return array("success" => true,"data"=> $respuesta, "message" =>'Timbrado Exitoso');
    }
	public function cancelar($id, $motivo = null, $facturaRelacionada = null) {
		try {
            die;
			$sth = $this->_db->prepare("SELECT timbrado FROM facturas WHERE id = ?");
			$sth->bind_param('i', $id);
			if(!$sth->execute()) throw New Exception();
			$result = $sth->get_result();
			$timbrado = $result->fetch_all(MYSQLI_ASSOC)[0]['timbrado'];
			if ($timbrado == 1) {
				$file = file_get_contents("../Data/xml/" . $id . "_timbrado.xml");
				$file = json_decode($file);
				$uuid = $file->data->uuid;
			    require_once '../requerimientos/vendors/lunasoft/autoload.php';
			    if ($facturaRelacionada) {
			    	$file = file_get_contents("../Data/xml/" . $facturaRelacionada . "_timbrado.xml");
					$file = json_decode($file);
					$folioSustitucion = $file->data->uuid;
			    } else {
			    	$folioSustitucion = null;
			    }
	        	$params = array(
			        "url" => "http://services.sw.com.mx",
			        "token" => "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGR2UmY0MXVVQytiUkxsTnFxdk9xaWwrWWY5N0pYOWJZSTRCSmZyWUQyL0ZtNHdRZlU5OVozREJxSUs2MVptVE9OK3dheW1CV1lnNHJnZnlKSGdkSWVCV3ovWGRJQkZtbTVGQW56WjJIaXB3NzhvL1drSHdrLzZPZTF2ZFlzMDJuUWhpTk9xVlV4ZFh1NGI3RkVQcUJiK0dObVdRb2JmR2xXLzBNM1IzMVhoVHlCbURJK1ZpZG9nS2RESy8xMHhzQTl0VDZLL3grYXBFVjRmZDhneEVYbVZ6aEhST2FFTTNsN2Fyc3d5Vk81U0loQ2gzbmJxZjduRnlUQVM3STZ3MDgvM0NpUGUzTHV5VFpjWDQ1ZmFGMGtFOWdwQ0xxZElpbU5LbFZlejRybFJWeXhiNUNVUTV5alNoNVpleWhDNUVBK2t6MFp0NCttM1lLSFNvV2EzTzZYVHVneXpIT2RFQ3Eyd3ZaOUVFQ3RVTDA0YmFDL1o0aTBQOVFxUytqNUhmeFovY1VwdmpvYzlNdVI3T1dSMGhzUWNnPT0.sWOJOnszkZtWHR47u7KAocG2nqQbGvtN90yTCUF2CMo",
			        "uuid" => $uuid,
			        "password" => 'Temp1234',
		        	"rfc" => "CKE141124LV7",
					"motivo" => $motivo,
					"folioSustitucion" => $folioSustitucion,
			        "cerB64" => base64_encode(file_get_contents("../../vendors/swsmart/cer.cer")),
			        "keyB64" => base64_encode(file_get_contents("../../vendors/swsmart/key.key"))
		        );
			    try {
			        $cancelationService = CancelationService::Set($params);
			        $result = $cancelationService::CancelationByCSD();
			        if ($result->status == 'error') {
			        	echo json_encode($result);
			        	die;
			        } else {
			        	$xmlCancelado = fopen("../Data/xml_cancelados/$id.xml", "w") or die("No se puede crear el archivo XML");
			        	fwrite($xmlCancelado, $result->data->acuse);
			        	fclose($xmlCancelado);
			        	date_default_timezone_set('America/Mexico_City');
						$now = new DateTime();
						$now = $now->format('Y-m-d H:i:s');
						date_default_timezone_set('UTC');
						// Cambio de status en Orden de Trabajo
						$sth = $this->_db->prepare("
							SELECT cp.orden_trabajo
							FROM facturas_partes cp
							WHERE cp.id_factura = ?
						");
						$sth->bind_param('i', $id);
						if(!$sth->execute()) echo mysqli_error($this->_db);
						$result = $sth->get_result();
						$data = $result->fetch_all(MYSQLI_ASSOC);
						foreach ($data as $datos2) {
							$sth = $this->_db->prepare("UPDATE odvCT SET Factura = 'FactCancelada' WHERE Idauto = ?");
							$sth->bind_param('i', $datos2['orden_trabajo']);
							if(!$sth->execute()) throw New Exception();
						}
						$sth = $this->_db->prepare("UPDATE facturas SET timbrado = 0, cancelado = 1, acuse_cancelacion = ?, fecha_cancelacion = ? WHERE id = ?");
						$sth->bind_param('sss', $uuid, $now, $id);
						if(!$sth->execute()) throw New Exception();
						$this->saveBitacora($id,"Factura/NotaDeCredito","Cancelacion de Timbre","Cancelación",$_SESSION["id"]);

						// PDF
						$sth = $this->_db->prepare("
							SELECT c.rfc, f.fecha_cancelacion
							FROM facturas f
							LEFT JOIN clientesCT c
							ON c.id = f.id_cliente
							WHERE f.id = ?
						");
						$sth->bind_param('i', $id);
						if(!$sth->execute()) throw New Exception();
						$result = $sth->get_result();
						$datos = $result->fetch_all(MYSQLI_ASSOC)[0];
						$rfcReceptor = $datos['rfc'];
						$fechaCancelacion = $this->formatearFechaHora($datos['fecha_cancelacion']);
						require_once('../../vendors/tcpdf/tcpdf.php');
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$pdf->SetCreator(PDF_CREATOR);
						$pdf->SetAuthor('Vision Remota');
						$pdf->SetTitle('Cancelacion - Vision Remota');
						$pdf->SetSubject('Cancelacion - Vision Remota');
						$pdf->SetKeywords('Vision Remota, Cancelacion');
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$pdf->SetFont('helvetica', '', 10);
						$pdf->AddPage();
						// Extraccion del xml timbrado
						$file = file_get_contents("../../data/xml_cancelados/" . $id . ".xml");
						preg_match('#<\s*?SignatureValue\b[^>]*>(.*?)</SignatureValue\b[^>]*>#s', $file, $match);
						$signatureValue = $match[1];
						$stasis = STASIS;
						TCPDF_FONTS::addTTFfont('../../vendors/tcpdf/fonts/Futura-Bold.ttf', 'TrueTypeUnicode', '', 96);
						TCPDF_FONTS::addTTFfont('../../vendors/tcpdf/fonts/Futura-Regular.ttf', 'TrueTypeUnicode', '', 96);
						$html = <<<EOF
						<html>
						<head>
						<style type="text/css">
						body {
							font-family: Futura;
						}
						.titulo {
							font-size: 10px;
							font-family: "FuturaBook";
						}
						.sinBorde {
							border-collapse: collapse;
							border: none;
						}
						.sinBorde tr td {
							font-size: 8px;
							border: none;
						}
						#partidas {
							border-collapse: collapse;
							font-size: 6px;
						}
						#partidas tr td {
							font-size: 6px;
						}
						</style>
						</head>
						<body>
						<table>
							<tr>
								<td style="width: 170px;">
									<img src="../../build/images/hacienda.png" width="170" alt="">
								</td>
								<td style="width: 360px; text-align: center;">
									<span style="font-family: 'Futura'; font-weight: bold; font-size: 18px;">
										Acuse de Cancelación de CFDI</span><br />
									<span style="font-family: 'Futura'; font-size: 12px;">
										Servicio de Administración Tributaria
									</span>
								</td>
							</tr>
						</table><br /><br /><br />
						<table style="width: 700px;">
							<tr>
								<td style="width:15%"></td>
								<td style="font-weight: bold;">
									Fecha de Proceso de Cancelación:
								</td>
								<td>
									$fechaCancelacion
								</td>
							</tr>
							<tr>
								<td style="width:15%"></td>
								<td style="font-weight: bold;">
									<br /><br />
									RFC Receptor:
								</td>
								<td>
									<br /><br />
									$rfcReceptor
								</td>
							</tr>
						</table>
						<table style="width: 700px;">
							<tr>
								<td style="width:15%"></td>
								<td>
									<br /><br />
									<b>Sello Digital SAT:</b><br />
									$signatureValue
								</td>
							</tr>
						</table>
						<br /><br /><br />
						<table style="width: 700px;" cellpadding="4">
							<thead>
								<tr>
									<th style="width:15%"></th>
									<th style="width:32%" border="1"><b>Folio Fiscal:</b></th>
									<th style="width:18%" border="1"><b>Estado CFDI:</b></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width:15%"></td>
									<td style="width:32%" border="1">
										$uuid
									</td>
									<td style="width:18%" border="1">
										Aceptación Cancelación
									</td>
								</tr>
							</tbody>
						</table>
						</body></html>
EOF;
						$pdf->writeHTML($html, true, false, true, false, '');
						$pdf->lastPage();
						$pdf->Output('Cancelacion_' . $id . '.pdf', 'I');

						die;
				  	}
			    } catch(Exception $e) {
			        echo 'Caught exception: ',  $e->getMessage(), "\n";
			        die;
			    }
			} else {
				date_default_timezone_set('America/Mexico_City');
				$now = new DateTime();
				$now = $now->format('Y-m-d H:i:s');
				date_default_timezone_set('UTC');
				$sth = $this->_db->prepare("UPDATE facturas SET timbrado = 0, cancelado = 1, fecha_cancelacion = ? WHERE id = ?");
				$sth->bind_param('si', $now, $id);
				if(!$sth->execute()) throw New Exception();
				$this->saveBitacora($id,"Factura/NotaDeCredito","Cancelacion sin Timbre","Cancelación",$_SESSION["id"]);

				header('Location:facturacion.php');
			}
		} catch (Exception $e) {
			echo $e->getMessage();
			die;
		}
	}

}
