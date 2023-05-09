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


class ControladorFactura extends apiModel{
    protected $_db = null;
	public function __construct() {
    	if (!$this->_db) {
    		require_once '../requerimientos/classes/conexionFinanzas.php';
			$this->_db = $db;
			setlocale(LC_TIME, 'es_ES');
        }
    }
    function autoCompleteOdv($string)
    {

        $json = [];

        $data = [];
        
        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";
       // $query = "SELECT * FROM odv WHERE Id LIKE '%" . (int)$string . "%';";
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

        // print_r($data);

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
    function getTabla($razonfactura){
        $success=false;
        $data="Error";
            // $query= 'SELECT * FROM '.$tabla;
            $query= 'SELECT f.id, f.status,  us.Name AS Usuario, m.Nombre AS moneda,cli.Nombre AS cliente, f.total, f.fecha, f.fecha_vencimiento, f.timbrado, f.cancelado, f.enviado, cli.dias_credito
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

                if($datosBD['timbrado'] == 1){
                    $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
    
                                
                                <a type='button'  class='dropdown-item btnDownloadPDFTimbrada'   id='pd" . $datosBD["id"] . "' >Descargar PDF</a>
                                <a type='button'  class='dropdown-item btnViewPDFTimbrada'   id='vd" . $datosBD["id"] . "' >Ver PDF</a>
                                <a type='button'  class='dropdown-item btnPDFDownloadZipTimbrada'   id='zt" . $datosBD["id"] . "' >Descargar PDF/XML</a>
                                <a type='button'  class='dropdown-item btnCancelarTimbrado'   id='ct" . $datosBD["id"] . "' >Cancelar Factura</a>
                            </div>
                    </div>";
                }else{
                    if($datosBD['cancelado'] == 0){
                        $datosBD["acciones"] ="
                        <div class='btn-group'>
                                <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                                <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>
                                <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["id"] . "' >Editar</a>
                                <a type='button'  class='dropdown-item btnFacturaGenerarPDF'   id='gn" . $datosBD["id"] . "' >Descargar PDF</a>
                                <a type='button'  class='dropdown-item btnFacturaVerPDF'   id='gv" . $datosBD["id"] . "' >Ver PDF</a>
                                <a type='button'  class='dropdown-item btnFacturaCancelar'   id='cf" . $datosBD["id"] . "' >Cancelar Factura</a>
                                <a type='button'  class='dropdown-item btnPDFDownloadZip'   id='zp" . $datosBD["id"] . "' >Timbrar 4.0</a>
                                    
                                </div>
                        </div>"; 
                    }else{
                        $datosBD["acciones"] ="
                        <div class='btn-group'>
                                <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                                
                        </div>";
                    }

                }

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
                'Precio' => $precio,
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


    public function pdfFactura($id){
        // Datos de Factura
        $pdfContent = $this->getDataFactura($id);
        include_once '../view/pdf/factura.php';
        $respuesta = pdf($pdfContent['data'],3);
        print_r($respuesta);
        die;
        return $respuesta;
        // // return array("success" => true,"data"=> $respuesta, "message" =>'Timbrado Exitoso');
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
    public function zipFactura($id){
        $msj = "No se encontro Factura";
        $success = false;
        $data = "";
        $file = file_get_contents("../Data/xml/" . $id . "_timbrado.xml");
        $file = json_decode($file);
        $uuid = $file->data->uuid;
        $archivo = $uuid.".zip";
        if(!empty($uuid)){
            $msj = "Descarga correcta.";
            $success = true;
            $data = $archivo;
            // $mensaje = array("success" => $success, "data" => $archivo, "message" => $msj);
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
        // $xmlCfdiRelacionados = '
        //     <cfdi:CfdiRelacionados TipoRelacion="' . $cfdi_relacionado . '">
        //         <cfdi:CfdiRelacionado UUID="' . $uuid . '" />
        //     </cfdi:CfdiRelacionados>
        // ';
                // Datos de Factura
        // $pdfContent = $this->getDataFactura($id);
        // include_once '../view/pdf/factura.php';
        // $respuesta = pdf($pdfContent['data'],1);
        // // return $respuesta;
        // // return array("success" => true,"data"=> $respuesta, "message" =>'Timbrado Exitoso');
    }
    public function descargarVerPDF($id){
        $msj = "No se encontro Factura";
        $success = false;
        $data = "";
        $file = file_get_contents("../Data/xml/" . $id . "_timbrado.xml");
        $file = json_decode($file);
        $uuid = $file->data->uuid;
        $archivo = $uuid.".pdf";
        if(!empty($uuid)){
            $msj = "Descarga correcta.";
            $success = true;
            $data = $archivo;
            // $mensaje = array("success" => $success, "data" => $archivo, "message" => $msj);
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
        // $xmlCfdiRelacionados = '
        //     <cfdi:CfdiRelacionados TipoRelacion="' . $cfdi_relacionado . '">
        //         <cfdi:CfdiRelacionado UUID="' . $uuid . '" />
        //     </cfdi:CfdiRelacionados>
        // ';
                // Datos de Factura
        // $pdfContent = $this->getDataFactura($id);
        // include_once '../view/pdf/factura.php';
        // $respuesta = pdf($pdfContent['data'],1);
        // // return $respuesta;
        // // return array("success" => true,"data"=> $respuesta, "message" =>'Timbrado Exitoso');
    }


    /////////////////////////////////Timbrado 4.0
    public function timbrar4($id)
    {
        // Datos de Factura
        // $pdfContent = $this->getDataFactura($id);
        // include_once '../view/pdf/factura.php';
        // $respuesta = pdf($pdfContent['data'],1);
        // // return $respuesta;
        // // return array("success" => true,"data"=> $respuesta, "message" =>'Timbrado Exitoso');
        // die;
        // die;



        // print_r($id);
        // die;
        $sql = "
        SELECT f.id, u.email AS emailAgente, f.forma_pago, f.moneda, f.cuenta_pago, f.metodo_pago, f.email, f.orden, f.subtotal, f.impuesto, f.impuesto_retenido, f.total, f.id_cliente, f.fecha, f.observaciones, f.tipo_cambio, f.cfdi_relacionado,us.Codigo as uso_cfdi,f.razonfactura
        FROM facturas f
        JOIN user_accounts u
        ON u.id = f.id_usuario
        inner join usocfdi us ON us.id = f.uso_cfdi
        WHERE f.id = ?
        ";
        
        $sth = $this->_db->prepare($sql);
        $sth->bind_param('s', $id);
        if (!$sth->execute()) throw new Exception();
        $result = $sth->get_result();
        $datos = $result->fetch_all(MYSQLI_ASSOC)[0];

        $quienFactura = $datos['razonfactura'];
        $uso_cfdi = $datos['uso_cfdi'];
        $idCliente = $datos['id_cliente'];
        $impuesto = $datos['impuesto'];
        $impuestoRetenido = $datos['impuesto_retenido'];
        $metodoPago = $datos['metodo_pago'];
        if($metodoPago < 10){
            $metodoPago = "0".$metodoPago;
        }else{
            $metodoPago = $metodoPago;
        }
        $fechaCreacion = new DateTime($datos['fecha']);
        $fechaCreacion = $fechaCreacion->format('Y-m-d\TH:i:s');
        $moneda = $datos['moneda'];
        $cfdi_relacionado = $datos['cfdi_relacionado'];
        // print_r($cfdi_relacionado);
        // die;
        if ($datos['forma_pago'] == 1) {
            $formaPago = 'PUE';
        } elseif ($datos['forma_pago'] == 2) {
            $formaPago = 'PPD';
        }
        // print_r($formaPago);
        // die;
        if ($datos['tipo_cambio'] == 1.0000) {
            $tipo_cambio = 1;
        } else {
            $tipo_cambio = $datos['tipo_cambio'];
        }
        if ($moneda == 1) {
            $moneda = 'MXN';
            $tipo_cambio = 1;
        } else {
            $moneda = 'USD';
        }
        // Servicios
        $traslados = '';
        $trasladosConcepto = '';
        $conceptos = '';
        $sumaDeTraslados = '';
        $grupoImpuestosTotales = '';
        $impuestosTrasladados0 = 0;
        $impuestosTrasladados8 = 0;
        $impuestosTrasladados16 = 0;
        $impuestosTrasladados16real = 0;
        $totalImpuestosTrasladados = 0;
        $totalImpuestosTrasladados0SinIva = 0;
        $totalImpuestosTrasladados8 = 0;
        $totalImpuestosTrasladados8SinIva = 0;
        $totalImpuestosTrasladados16 = 0;
        $totalImpuestosTrasladados16SinIva = 0;
        $totalImpuestosRetenidos = 0;
        $subtotal = 0;
        $total = 0;
        $xmlCfdiRelacionados = '';
        $sql = "SELECT * FROM facturas_partes cp WHERE cp.id_factura = ? ORDER BY cp.id ASC";

        $sth = $this->_db->prepare($sql);
        $sth->bind_param('i', $id);
        if (!$sth->execute()) echo mysqli_error($this->_db);
        $result = $sth->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $htmlPartidas = '';

        foreach ($data as $datos2) {
            $xmlRetencion = '';
            // switch ($datos2['clave_unidad']) {
            //     case 'E48':
            //         $claveUnidad = 'UNIDAD DE SERVICIO';
            //         break;
            //     case 'ACT':
            //         $claveUnidad = 'ACTIVIDAD';
            //         break;
            //     case 'E51':
            //         $claveUnidad = 'TRABAJO';
            //         break;
            //     case 'A9':
            //         $claveUnidad = 'TARIFA';
            //         break;
            //     case 'E54':
            //         $claveUnidad = 'VIAJE';
            //         break;
            // }
            $claveUnidad = $datos2['clave_unidad'];
            if ($datos2['por_impuesto'] == .12) {
                $porImpuesto = .16;
            } else {
                $porImpuesto = $datos2['por_impuesto'];
            }

            $descripcion = htmlspecialchars(utf8_encode($datos2['descripcion']), ENT_XML1 | ENT_COMPAT, 'UTF-8');
            $valorUnitario = number_format((float)$datos2['precio'], 5, '.', '');
            $importe = number_format((float)$datos2['precio'] * $datos2['cantidad'], 5, '.', '');
            $importeIva = number_format((float)($datos2['precio'] * $datos2['cantidad']) * $porImpuesto, 5, '.', '');
            $subtotal += ($datos2['precio'] * $datos2['cantidad']);
            if ($datos2['por_impuesto'] == .12) {
                $porImpuesto = .16;
                $impuestosRetenidos = number_format(($datos2['cantidad'] * $datos2['precio']) * .04, 5, '.', '');
                $totalImpuestosRetenidos += ($datos2['cantidad'] * $datos2['precio']) * .04;
                $impuestosTrasladados16 += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto - ($datos2['cantidad'] * $datos2['precio']) * .04;
                $totalImpuestosTrasladados += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto - ($datos2['cantidad'] * $datos2['precio']) * .04;
                $totalImpuestosTrasladados16 += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto - ($datos2['cantidad'] * $datos2['precio']) * .04;
                $trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="' . $importeIva . '" />
				    <cfdi:Retenciones>';
                $xmlRetencion = '<cfdi:Retencion Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.040000" Importe="' . $impuestosRetenidos . '"/>
	                </cfdi:Retenciones>';
                $objetoImp = "02";
            } else {
                if ($datos2['por_impuesto'] == .00) {
                    $porImpuesto = $datos2['por_impuesto'];
                    $impuestosTrasladados0 = 1;
                    $totalImpuestosTrasladados0SinIva += $datos2['cantidad'] * $datos2['precio'];
                    $trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0' . $porImpuesto . '0000" Importe="' . $importeIva . '" />';
                    $objetoImp = "01";
                }
                if ($datos2['por_impuesto'] == .08) {
                    $porImpuesto = $datos2['por_impuesto'];
                    $impuestosTrasladados8 = number_format((float)($datos2['cantidad'] * $datos2['precio']) * $porImpuesto, 5, '.', '');
                    $totalImpuestosTrasladados += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto;
                    $totalImpuestosTrasladados8 += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto;
                    $totalImpuestosTrasladados8SinIva += $datos2['cantidad'] * $datos2['precio'];
                    $trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0' . $porImpuesto . '0000" Importe="' . $impuestosTrasladados8 . '" />';
                    $objetoImp = "02";
                }
                if ($datos2['por_impuesto'] == .16) {
                    $impuestosTrasladados16real = 1;
                    $porImpuesto = $datos2['por_impuesto'];
                    $importeIva = number_format((float)($datos2['cantidad'] * $datos2['precio']) * $porImpuesto, 5, '.', '');
                    $totalImpuestosTrasladados += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto;
                    $totalImpuestosTrasladados16 += ($datos2['cantidad'] * $datos2['precio']) * $porImpuesto;
                    $totalImpuestosTrasladados16SinIva += $datos2['cantidad'] * $datos2['precio'];
                    $trasladosConcepto = '<cfdi:Traslado Base="' . $importe . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0' . $porImpuesto . '0000" Importe="' . $importeIva . '" />';
                    $objetoImp = "02";
                }
            }




            //agregado por Paul para evitar poner el nodo de impuestos si es que el ObjetoImp es = 1
            if ($datos2['por_impuesto'] == .00) {
                $conceptos .= '
				<cfdi:Concepto ClaveProdServ="' . $datos2['clave_prodserv'] . '" NoIdentificacion="' . $datos2['codigo'] . '" Cantidad="' . $datos2['cantidad'] . '" ClaveUnidad="' . $datos2['clave_unidad'] . '" Unidad="' . $claveUnidad . '" Descripcion="' . $descripcion . '" ValorUnitario="' . $valorUnitario . '" Importe="' . $importe . '" ObjetoImp="' . $objetoImp . '">
				</cfdi:Concepto>';
            } else {
                $conceptos .= '
				<cfdi:Concepto ClaveProdServ="' . $datos2['clave_prodserv'] . '" NoIdentificacion="' . $datos2['codigo'] . '" Cantidad="' . $datos2['cantidad'] . '" ClaveUnidad="' . $datos2['clave_unidad'] . '" Unidad="' . $claveUnidad . '" Descripcion="' . $descripcion . '" ValorUnitario="' . $valorUnitario . '" Importe="' . $importe . '" ObjetoImp="' . $objetoImp . '">
					<cfdi:Impuestos>
						<cfdi:Traslados>
						' . $trasladosConcepto . '
						</cfdi:Traslados>
						' . $xmlRetencion . '
					</cfdi:Impuestos>
				</cfdi:Concepto>';
            }
        }
        $subtotal = number_format((float)$subtotal, 2, '.', '');
        $importeTotalIva = number_format((float)$totalImpuestosTrasladados, 2, '.', '');
        if ($impuestosTrasladados0 == 1) {
            // $traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados0SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.000000" Importe="0.00" />';
        }
        if ($totalImpuestosTrasladados8 != 0) {
            $traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados8SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.080000" Importe="' . number_format($totalImpuestosTrasladados8, 2, '.', '') . '" />';
        }
        if ($totalImpuestosTrasladados16 != 0) {
            $traslados .= '<cfdi:Traslado Base="' . number_format($totalImpuestosTrasladados16SinIva, 2, '.', '') . '" Impuesto="002" TipoFactor="Tasa" TasaOCuota="0.160000" Importe="' . number_format($totalImpuestosTrasladados16, 2, '.', '') . '" />';
        }



        // var_dump($impuestosTrasladados8);
        // var_dump($impuestosTrasladados16);
        // var_dump($impuestosTrasladados16real);
        if ($impuestosTrasladados8 != 0 || $impuestosTrasladados16 != 0 || $impuestosTrasladados16real != 0) {
            $sumaDeTraslados = '<cfdi:Traslados>
									' . $traslados . '
								</cfdi:Traslados>';
        } else {
            $sumaDeTraslados = '';
        }

        // var_dump($sumaDeTraslados);




        // Si tiene impuesto retenido
        if ($totalImpuestosRetenidos) {
            $totalImpuestosRetenidos = 'TotalImpuestosRetenidos="' . number_format($totalImpuestosRetenidos, 5, '.', '') . '"';
            $retenciones = '
			    <cfdi:Retenciones>
			        <cfdi:Retencion Impuesto="002" Importe="' . $totalImpuestosRetenidos . '"/>
		        </cfdi:Retenciones>
	        ';
        } else {
            $total = number_format((float)$subtotal + $importeTotalIva, 2, '.', '');
            $totalImpuestosRetenidos = '';
            $retenciones = '';
        }




        // var_dump($retenciones);
        // var_dump($sumaDeTraslados);
        if ($retenciones != '' || $sumaDeTraslados != '') {

            $grupoImpuestosTotales = '<cfdi:Impuestos ' . $totalImpuestosRetenidos . ' TotalImpuestosTrasladados="' . $importeTotalIva . '">
										' . $retenciones . '

										' . $sumaDeTraslados . '


									</cfdi:Impuestos>';
        } else {
            $grupoImpuestosTotales = '';
        }


        // Direccion del cliente
        $sth2 = $this->_db->prepare("
			SELECT RazonSocial, RFC, ContactoPrincipal, Telefono, CorreoElectronico, CalleCliente, CiudadCliente, ColoniaCliente, EstadoCliente, PaisCliente, CPCliente
			FROM clientes
			WHERE id = ?
		");
        $sth2->bind_param('s', $idCliente);
        if (!$sth2->execute()) throw new Exception();
        $result = $sth2->get_result();
        $datos2 = $result->fetch_all(MYSQLI_ASSOC)[0];
        $cliente = mb_strtoupper($datos2['RazonSocial']);
        $clienteRfc = $datos2['RFC'];
        $cpCliente = $datos2['CPCliente'];
        // CFDIs Relacionados
        if ($cfdi_relacionado) {
            $sth2 = $this->_db->prepare("SELECT cfdi_relacionado FROM facturas_relaciones WHERE id_factura = ?");
            $sth2->bind_param('i', $id);
            if (!$sth2->execute()) echo mysqli_error($this->_db);
            $result2 = $sth2->get_result();
            $data2 = $result2->fetch_all(MYSQLI_ASSOC);
            // print_r($data2);
            foreach ($data2 as $datos) {
                // print_r($datos);
                $file = file_get_contents("../Data/xml/" . $datos['cfdi_relacionado'] . "_timbrado.xml");
                $file = json_decode($file);
                $uuid = $file->data->uuid;
                $xmlCfdiRelacionados = '
					<cfdi:CfdiRelacionados TipoRelacion="' . $cfdi_relacionado . '">
					    <cfdi:CfdiRelacionado UUID="' . $uuid . '" />
					</cfdi:CfdiRelacionados>
				';
            }
        }

        // RegimenFiscal
        $sth = $this->_db->prepare("SELECT CPCliente, RegimenFiscal FROM clientes WHERE Id = ?");
        $sth->bind_param('i', $idCliente);
        if (!$sth->execute()) throw new Exception();
        $result = $sth->get_result();
        $datos = $result->fetch_all(MYSQLI_ASSOC)[0];
        $regimenFiscalCliente = $datos['RegimenFiscal'];
        $domicilioFiscalCliente = $datos['CPCliente'];
        // Regimenes fiscales: 601-Moral, 612-ActividadEmpresarial, 621-IncorporacionFiscal
        switch($quienFactura){
            case 1:
                $nombreFiscal = 'IVAN PELAEZ GARCIA';
                $regimenFiscal = '612';
                $expedicion = "22115";
                $rfc = 'PEGI620904SQ1';
                $rutacer = '../requerimientos/vendors/swsmart/cer.pem';
                $rutakey = '../requerimientos/vendors/swsmart/key.pem';
                $certificado = "MIIF2zCCA8OgAwIBAgIUMDAwMDEwMDAwMDA1MDczODQxMDEwDQYJKoZIhvcNAQELBQAwggGEMSAwHgYDVQQDDBdBVVRPUklEQUQgQ0VSVElGSUNBRE9SQTEuMCwGA1UECgwlU0VSVklDSU8gREUgQURNSU5JU1RSQUNJT04gVFJJQlVUQVJJQTEaMBgGA1UECwwRU0FULUlFUyBBdXRob3JpdHkxKjAoBgkqhkiG9w0BCQEWG2NvbnRhY3RvLnRlY25pY29Ac2F0LmdvYi5teDEmMCQGA1UECQwdQVYuIEhJREFMR08gNzcsIENPTC4gR1VFUlJFUk8xDjAMBgNVBBEMBTA2MzAwMQswCQYDVQQGEwJNWDEZMBcGA1UECAwQQ0lVREFEIERFIE1FWElDTzETMBEGA1UEBwwKQ1VBVUhURU1PQzEVMBMGA1UELRMMU0FUOTcwNzAxTk4zMVwwWgYJKoZIhvcNAQkCE01yZXNwb25zYWJsZTogQURNSU5JU1RSQUNJT04gQ0VOVFJBTCBERSBTRVJWSUNJT1MgVFJJQlVUQVJJT1MgQUwgQ09OVFJJQlVZRU5URTAeFw0yMTA1MTMxOTI4MDZaFw0yNTA1MTMxOTI4MDZaMIGpMRswGQYDVQQDExJJVkFOIFBFTEFFWiBHQVJDSUExGzAZBgNVBCkTEklWQU4gUEVMQUVaIEdBUkNJQTEbMBkGA1UEChMSSVZBTiBQRUxBRVogR0FSQ0lBMRYwFAYDVQQtEw1QRUdJNjIwOTA0U1ExMRswGQYDVQQFExJQRUdJNjIwOTA0SEdSTFJWMDQxGzAZBgNVBAsTEkl2YW4gUGVsYWV6IEdhcmNpYTCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAKPwyzmJow587glyYWsA8c3MAt2f039VElJjhG96vdADlgGbhXX5jFEnUifDzgdXD6sRKWKa4lwon421bfjYpScGcIDd3RHo6jbDA3ZT8VMFFTRXZR50po/ugiHyxA9VNfOKVgAVvhE/a/TSa3jz3pJffu5b5p7uIchCn6kBPzj+PVLHdB9gmZLCfdwDQrMEDjSpp/eTujXe4EnykyjyWZMa53sv4N/OGJYUzZTGLbWPZvjd3iWBW1Fa4m1Jvso+d2j1BEEnd5IF8CroHeZVXpFtYDyTDRMBFFVc/DPcrlIb6JrKL1DTTBgfT1HAVlvMnRD4xkKPMo8lqwjz9PkdWtECAwEAAaMdMBswDAYDVR0TAQH/BAIwADALBgNVHQ8EBAMCBsAwDQYJKoZIhvcNAQELBQADggIBAHT0+hbNX1AJAt9xb2iIFzZ0PlerauCO1R8oFsEiTjibHIAnr+2qAOffcgmL1BvqzT2CrkYWmwwCJNj4ghm/OVW+2RBSd6OHjAkzz3GDb8C8/z8fImCfscpIUFUmVZOSwEdMjRXGhYA4yugq6bpp65NzzoIKQeYiBB6shx5AEVJoiXytBsrUkT06ILevvjnnYkOae49ZbcsHSgpGNI180AFKA+44lD1YtdosX9avRygifnwZuVcWWZyV+7AWlhUIClXvrFYsCnAsNpx89KuvBozBb4LG9e4gB5knSPd+GQrW+8GtnrxMAhXQDpOKO8gBK8Zh4KzzEeSilTncvbAG2lvr3092HHc5VugK0P5+ie20QwMyFhYSXqTawW/UIFC137alYeS1y/SmdI7A2wHMFHvLEGEb7jo/QoXutGiVjH7tt098O+eF6qWO0ohNMeEyOmdeM1fVDnt+EReG9Va/Z7xsLQ5M8FGHTT3SlWP1NQHbFHG/Wb0lkCrV9AvPpNHWZCnltkkReChF3Xqi6Uwoi+YFC9n0KHm7pPfpUW6oCayNzwnADRB0l6eV/j9kUDS9e3PpUIzIxKmioKwb9iGdmZUTpxv0yi/cIDzYfIrGsXjY+dgh0aSXmh3N11HwpIQkLUjY9YI88VS+vVX4Sfy8RHj3y1EbVEGLWUypQXiKu14F";
                $nocertificado = '00001000000507384101';
            break;
            case 2:
                $nombreFiscal = 'ALEJANDRA ORTIZ BOWSER';
                $regimenFiscal = '626';
                $expedicion = "22505";
                $rfc = 'OIBA8508205Q0';
                $rutacer = '../requerimientos/vendors/swsmart/cerAle.pem';
                $rutakey = '../requerimientos/vendors/swsmart/keyAle.pem';
                $certificado = 'MIIF6zCCA9OgAwIBAgIUMDAwMDEwMDAwMDA1MTQwNDc2OTIwDQYJKoZIhvcNAQELBQAwggGEMSAwHgYDVQQDDBdBVVRPUklEQUQgQ0VSVElGSUNBRE9SQTEuMCwGA1UECgwlU0VSVklDSU8gREUgQURNSU5JU1RSQUNJT04gVFJJQlVUQVJJQTEaMBgGA1UECwwRU0FULUlFUyBBdXRob3JpdHkxKjAoBgkqhkiG9w0BCQEWG2NvbnRhY3RvLnRlY25pY29Ac2F0LmdvYi5teDEmMCQGA1UECQwdQVYuIEhJREFMR08gNzcsIENPTC4gR1VFUlJFUk8xDjAMBgNVBBEMBTA2MzAwMQswCQYDVQQGEwJNWDEZMBcGA1UECAwQQ0lVREFEIERFIE1FWElDTzETMBEGA1UEBwwKQ1VBVUhURU1PQzEVMBMGA1UELRMMU0FUOTcwNzAxTk4zMVwwWgYJKoZIhvcNAQkCE01yZXNwb25zYWJsZTogQURNSU5JU1RSQUNJT04gQ0VOVFJBTCBERSBTRVJWSUNJT1MgVFJJQlVUQVJJT1MgQUwgQ09OVFJJQlVZRU5URTAeFw0yMjA3MjAxNjIyMjJaFw0yNjA3MjAxNjIyMjJaMIG5MR8wHQYDVQQDExZBTEVKQU5EUkEgT1JUSVogQk9XU0VSMR8wHQYDVQQpExZBTEVKQU5EUkEgT1JUSVogQk9XU0VSMR8wHQYDVQQKExZBTEVKQU5EUkEgT1JUSVogQk9XU0VSMRYwFAYDVQQtEw1PSUJBODUwODIwNVEwMRswGQYDVQQFExJPSUJBODUwODIwTUJDUldMMDMxHzAdBgNVBAsTFkFMRUpBTkRSQSBPUlRJWiBCT1dTRVIwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCHqUHndupeoUW1t0aqMt+ZednLsHyHh8bNLE2Kk/Kl07AoTrFy97gR/V/RK49dPB1KQosjAEkByIw+qTtxGfEC8NgxJdTrgmH08au7WlGAAwBz0ttXiw2BDHrwlbLDNlTLKOKXggRoFozOkqlFu1clwkKXRUsPY/FCXsN/1Q2SIuJY9gZdFDdHvDKrfHiWuhp7ZYSpi33s6o8QbNxB/Puz/m1hPf0p6x3PgeSzrJxKfHBkGWZZYB1j8rIrS04miLsfNVprIeeR1LTp24Kh6ZoJCRkvCrzk9C6dXDYMcM4Nhv3cTTJ7EQxA4NtJO/AhQUQe4itox0bZZr14f0aDbAMBAgMBAAGjHTAbMAwGA1UdEwEB/wQCMAAwCwYDVR0PBAQDAgbAMA0GCSqGSIb3DQEBCwUAA4ICAQA557kRzbQ1oXbrCim8gpyiB3kJjC1AKKJchM5E4/YuKF2e+hKW5EfowFvukPYJDfhXLdyWpWfFTg7GqORYAENHDznJm5cb5A3hw0iWxagWWNQZVgPQf3Doh7qMgKfwCubi31U9Pb08CpS9bl93vclJoo+k0TJX870w3aJGKqye5bSmjYSybHHXMRTIzbsm03AWSH3XwQcG7wYOygwft/J7sfA41iRjue7MpzmkQP++Bqiy5rPc1fJBKQPFfwrekkyYKD08wietVdAkMvubAipQwfHDwNMf0inUCSE/LqlpnVqQECQr5RRQfnq95PE+LGtco5dMfp7+vUhYl1jNb3m5fmFEdf5n7FoX9xhV6O7zd/ghWFONAuSRi70gWXx6vaZVPkU5KHEWo2tlQVeT+ypGZRrNAASKLOkORCUpGdqD8EY23ZUBmGBlnQ3qKahjMsOIhDT0VkYeTgLJ/OTLnmNUIxuZn25G5hcnv8CW8j+vWEsbm0m2z9Zbs1ZwA4rDEWdY7az+DGFLnco8i9eRLUBgZD0cyyqiRnSN05UOVpIyXFNelCTk6GpesCGemFZAtSAiR4cwyLfry6RvnC4/qrBDI21hlnas9cJ6XkoRwllg/u6otQLSsvI2zet2+btwv8TVA1imI26ALSH96hkJFBgi1cn4xcHsfN84DIxROeBYEw==';
                $nocertificado = '00001000000514047692';
            break;
        }


        //////////////////
        // Timbracion //
        //////////////////
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
			<cfdi:Comprobante
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
				LugarExpedicion="'.$expedicion.'"
				MetodoPago="' . $formaPago . '"
				TipoDeComprobante="I"
				Total="' . $total . '"
				TipoCambio="' . $tipo_cambio . '"
				Moneda="' . $moneda . '"
				SubTotal="' . $subtotal . '"
				Exportacion="01"
				Certificado="'.$certificado.'"
				NoCertificado="'.$nocertificado.'"
				FormaPago="' . $metodoPago . '"
				Sello=""
				Fecha="' . $fechaCreacion . '"
				Folio="' . $id . '"
				Version="4.0"
				xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd">
				' . $xmlCfdiRelacionados . '
				<cfdi:Emisor
					RegimenFiscal="' . $regimenFiscal . '"
					Nombre="' . $nombreFiscal . '"
					Rfc="' . $rfc . '" />
				<cfdi:Receptor
					Nombre="' . htmlspecialchars($cliente) . '"
					Rfc="' . htmlspecialchars($clienteRfc) . '"
					UsoCFDI="' . $uso_cfdi . '"
					DomicilioFiscalReceptor="' . $domicilioFiscalCliente . '"
					RegimenFiscalReceptor="' . $regimenFiscalCliente . '" />
				<cfdi:Conceptos>
					' . $conceptos . '
				</cfdi:Conceptos>

				' . $grupoImpuestosTotales . '
			</cfdi:Comprobante>';


        $fp = fopen("../Data/xml/" . $id . ".xml", 'w');
        fwrite($fp, $xml);
        fclose($fp);
        // print_r($xmlContent);

        // Cadena origian Plugin
        // require_once '../../vendors/cfdiutils/vendor/autoload.php';
        require_once '../requerimientos/vendors/cfdiutils/vendor/autoload.php';

        $xmlContent = file_get_contents("../Data/xml/" . $id . ".xml");

        $resolver = new XmlResolver();

        $location = $resolver->resolveCadenaOrigenLocation('4.0');
        
        $builder = new DOMBuilder();

        $cadenaorigen = $builder->build($xmlContent, $location);
        $fp = fopen("../Data/xml/" . $id . ".txt", 'w');
        fwrite($fp, $cadenaorigen);
        fclose($fp);

        // print_r($xml);
        // die;
        // Sellar
        require_once '../requerimientos/vendors/lunasoft/autoload.php';
        $params = array(
            "cadenaOriginal" => "../Data/xml/" . $id . ".txt",
            "archivoKeyPem" => $rutakey,
            "archivoCerPem" => $rutacer
        );
            
        // print_r($params);
        try {
            // Meter sello en XML
            // print_r($params);
            $sello = Sellar::obtenerSello($params);
            // print_r($sello);
            // print_r($params);
            // print_r($xml);
            // die;
            try {
                // print_r($xml);
            // die;
            $xml = '<?xml version="1.0" encoding="UTF-8"?>
			<cfdi:Comprobante
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
				LugarExpedicion="'.$expedicion.'"
				MetodoPago="' . $formaPago . '"
				TipoDeComprobante="I"
				Total="' . $total . '"
				TipoCambio="' . $tipo_cambio . '"
				Moneda="' . $moneda . '"
				SubTotal="' . $subtotal . '"
				Exportacion="01"
				Certificado="'.$certificado.'"
				NoCertificado="'.$nocertificado.'"
				FormaPago="' . $metodoPago . '"
				Sello="'. $sello->sello .'"
				Fecha="' . $fechaCreacion . '"
				Folio="' . $id . '"
				Version="4.0"
				xsi:schemaLocation="http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd">
				' . $xmlCfdiRelacionados . '
				<cfdi:Emisor
					RegimenFiscal="' . $regimenFiscal . '"
					Nombre="' . $nombreFiscal . '"
					Rfc="' . $rfc . '" />
				<cfdi:Receptor
					Nombre="' . htmlspecialchars($cliente) . '"
					Rfc="' . htmlspecialchars($clienteRfc) . '"
					UsoCFDI="' . $uso_cfdi . '"
					DomicilioFiscalReceptor="' . $domicilioFiscalCliente . '"
					RegimenFiscalReceptor="' . $regimenFiscalCliente . '" />
				<cfdi:Conceptos>
					' . $conceptos . '
				</cfdi:Conceptos>

				' . $grupoImpuestosTotales . '
			</cfdi:Comprobante>';
                // print_r($xml);
                // die;
                $fp = fopen("../Data/xml/" . $id . ".xml", 'w');
                fwrite($fp, $xml);
                fclose($fp);


                // echo $xml;
                // echo "se grabó";
                // print_r($xml);
                // die;
                // Timbrar
                try {
                    header('Content-type: application/json');
                    $params = array(
                        "url" => "http://services.sw.com.mx",
                        "token" => "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGR2UmY0MXVVQytiUkxsTnFxdk9xaWwrWWY5N0pYOWJZSTRCSmZyWUQyL0ZtNHdRZlU5OVozREJxSUs2MVptVE9OK3dheW1CV1lnNHJnZnlKSGdkSWVCV3ovWGRJQkZtbTVGQW56WjJIaXB3NzhvL1drSHdrLzZPZTF2ZFlzMDJuUWhpTk9xVlV4ZFh1NGI3RkVQcUJiK0dObVdRb2JmR2xXLzBNM1IzMVhoVHlCbURJK1ZpZG9nS2RESy8xMHhzQTl0VDZLL3grYXBFVjRmZDhneEVYbVZ6aEhST2FFTTNsN2Fyc3d5Vk81U0loQ2gzbmJxZjduRnlUQVM3STZ3MDgvM0NpUGUzTHV5VFpjWDQ1ZmFGMGtFOWdwQ0xxZElpbU5LbFZlejRybFJWeXhiNUNVUTV5alNoNVpleWhDNUVBK2t6MFp0NCttM1lLSFNvV2EzTzZYVHVneXpIT2RFQ3Eyd3ZaOUVFQ3RVTDA0YmFDL1o0aTBQOVFxUytqNUhmeFovY1VwdmpvYzlNdVI3T1dSMGhzUWNnPT0.sWOJOnszkZtWHR47u7KAocG2nqQbGvtN90yTCUF2CMo"
                    );
                    $xml = file_get_contents("../Data/xml/" . $id . ".xml");
                    $stamp = StampService::Set($params);
                    
                    $result = $stamp::StampV4($xml);
                    // print_r($result->message);
                    // print_r($result);
                    if (empty($result->message)) {
                        
                        $fp = fopen("../Data/xml/" . $id . "_timbrado.xml", 'w');
                        fwrite($fp, json_encode($result));
                        fclose($fp);
                        
                        $sth = $this->_db->prepare("UPDATE facturas SET timbrado = 1 WHERE id = ?");
                        $sth->bind_param('i', $id);
                        if (!$sth->execute()) throw new Exception();
                        $this->saveBitacora($id, "Factura", "Timbrado de Factura V 4.0", "Facturación", $_SESSION["id"]);
                        // Creacion XML
                        $file = file_get_contents("../Data/xml/" . $id . "_timbrado.xml");
                        $file = json_decode($file);
                        $uuid = $file->data->uuid;
                        $cfdi = $file->data->cfdi;

                        // print_r("Despues de obtener los datos del xml");
                        // Creacion XML
                        $fp = fopen("../Data/facturas/" . $uuid . ".xml", 'w');
                        fwrite($fp, $cfdi);
                        fclose($fp);
                        $pdfContent = $this->getDataFactura($id);
                        include_once '../view/pdf/factura.php';
                        pdf($pdfContent['data'],1);
                        // print_r('Entra3');
                        
                        // Creacion ZIP
                        $zip = new ZipArchive();
                        $zip_name = "../Data/facturas/$uuid.zip";
                        if ($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE) {
                            echo "* Sorry ZIP creation failed at this time";
                        }
                        $zip->addFile("../Data/facturas/$uuid.xml", "$uuid.xml");
                        $zip->addFile("../Data/facturas/$uuid.pdf", "$uuid.pdf");
                        $zip->close();
                        return array("success" => true,"data"=>$uuid, "message" =>'Timbrado Exitoso');
                        
                    } else {
                        // var_dump($result);
                        // print_r('else');
                        return array("success" => false,"data"=>'', "message" =>'Caught exception: '.$result->message.' '.$result->messageDetail, "\n");

                        die;
                    }
                } catch (Exception $e) {
                    return array("success" => false,"data"=>'', "message" =>'Caught exception: ',  $e->getMessage(), "\n");
                    die;
                }
            } catch (Exception $e) {
                return array("success" => false,"data"=>'', "message" =>'Caught exception: ',  $e->getMessage(), "\n");
                // echo 'Caught exception: ',  $e->getMessage(), "\n";
                die;
            }
        } catch (Exception $e) {
            return array("success" => false,"data"=>'', "message" =>'Caught exception: ',  $e->getMessage(), "\n");
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
            die;
        }
    }
	public function descargarArchivo($ubicacion, $nombre) {
		if( headers_sent() )
		die('Headers Sent');
		if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
		if( file_exists($ubicacion) ) {
			$fsize = filesize($ubicacion);
			$path_parts = pathinfo($ubicacion);
			$ext = strtolower($path_parts["extension"]);
			switch ($ext) {
				case "pdf": $ctype="application/pdf"; break;
				case "exe": $ctype="application/octet-stream"; break;
				case "zip": $ctype="application/zip"; break;
				case "doc": $ctype="application/msword"; break;
				case "xls": $ctype="application/vnd.ms-excel"; break;
				case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpg"; break;
				default: $ctype="application/force-download";
			}
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=\"" . $nombre . "\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$fsize);
			ob_clean();
			flush();
			readfile( $ubicacion );
		} else die('Archivo no encontrado');
	}

    public function getDataFactura($idFactura){


        // print_r(fun);
        $query="SELECT f.id, u.Name AS agente, u.email AS emailAgente,
        mn.Nombre AS moneda, f.cuenta_pago, f.email, f.orden, f.subtotal, f.impuesto, 
        f.impuesto_retenido, f.total, f.id_cliente, f.fecha, f.observaciones,
        fpago.Nombre AS forma_pago,
        CONCAT(mp.Id,' - ',mp.Nombre) AS metodoPago,
        CONCAT(cfdi.Codigo,' - ',cfdi.Nombre) AS uso_cfdi,
        CONCAT(cfdiRc.Id,' - ',cfdiRc.Nombre) AS  cfdi_relacionado,f.razonfactura
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
        $quienFactura = $datos['razonfactura'];
        // print_r($datos);
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
    
            // NOTA VERICAR CLAVE UNIDAD (NOSE ENCUENTRA LA TABLA)
            // switch ($datos2['clave_unidad']) {
            //     case '1': $claveUnidad = 'KGM'; break;
            //     case '2': $claveUnidad = 'LTR'; break;
            //     case '3': $claveUnidad = 'H87'; break;
            //     case '4': $claveUnidad = 'KT'; break;
    
            // }
            $claveUnidad = $datos2['clave_unidad'];
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
            'folio'=>$datos2['id_factura'],
            'codigo'=>$datos2['codigo'],
            'clave_prodserv'=>$datos2['clave_prodserv'],
            'descripcion'=>($datos2['descripcion']),
            'cantidad'=>number_format((float)$datos2['cantidad'], 0, '.', ','),
            'claveUnidadSAT'=>$claveUnidad,
            'claveUnidad'=>$datos2['clave_unidad'],
            'precio'=>'$'.number_format((float)$datos2['precio'], 2, '.', ','),
            'impuesto'=>'$'.number_format((float) ($datos2['cantidad']*$datos2['precio'])*$porImpuesto, 2, '.', ','),
            'importe'=>'$'.number_format((float)$datos2['cantidad']*$datos2['precio'], 2, '.', ','),
            ];

            // print_r($htmlPartidas);

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
        $nombreCalle = ($responseCliente['CalleCliente']);
        $colonia = ($responseCliente['ColoniaCliente']);
        $pais = ($responseCliente['PaisCliente']);
        $estado = ($responseCliente['EstadoCliente']);
        $ciudad = ($responseCliente['CiudadCliente']);
        $cp = ($responseCliente['CPCliente']);
        $clienteDireccion = ("$nombreCalle $colonia $ciudad $estado $pais $cp");
    
        
     switch($quienFactura){
        case 1:

            $name = 'IVAN PELAEZ GARCIA';
            $calle = 'PROLONGACIÓN ELOTE';
            $col = 'COL.CASTRO, NO. EXT.90';
            $estadoMuni = "TIJUANA, BAJA CALIFORNIA, CP. 22115";
            // $direction = 'PROLONGACIÓN ELOTE #90, LAS HUERTAS 1RA SECCIÓN TIJUANA, BAJA CALIFORNIA, CP. 22115';
            $tel = '(664) 689 0345';
            $rfcQuienFactura = 'PEGI620904SQ1';
            $razon = 'IVAN PELAEZ GARCIA';
            $regimenName = "PERSONA FISICA CON ACTIVIDAD EMPRESARIAL Y PROFESIONAL";
            break;

        case 2:
            $name = 'ALEJANDRA ORTIZ BOWSER';
            $calle = 'CALLE LAVA';
            $col = 'COL. PLAYAS DE TIJUANA, NO. EXT.1881';
            $estadoMuni = "TIJUANA, BAJA CALIFORNIA, CP. 22505";
            // $direction = 'CALLE LAVA #1881 COL. PLAYAS DE TIJUANA, TIJUANA, BAJA CALIFORNIA, CP. 22505';
            $tel = '(664) 689 0345';
            $rfcQuienFactura = 'OIBA8508205Q0';
            $razon = 'ALEJANDRA ORTIZ BOWSER';
            $regimenName = " SIMPLIFICADO DE CONFIANZA";
            break;
     }
    
    
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
                'name' => $name,
                // 'direction' => $direction,
                'calle' => $calle,
                'col' => $col,
                'estadoMuni' => $estadoMuni,
                'cell' => $tel,
                'phone' => $tel,
                'rfc' => $rfcQuienFactura,
                'razon' => $razon,
                'regimenName' => $regimenName
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
    
    public function writeToNumber($number, $currency)
    {
        $formatter = new NumeroALetras();
        $decimals = 2;
        return $formatter->toMoney($number, $decimals, $currency, 'CENTAVOS');
    }
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



        $sql = "SELECT p.id, pro.Id AS codigo, 
        pro.ClaveProdServSat AS claveProServ, pro2.Nombre AS descripcion ,vp.Cantidad,vp.Precio_Litro AS precio,vp.Total,vp.IvaPorcentual,
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
    public function saveBitacora($idFactura,$tipo,$accion,$modulo = null,$user){
        // print_r("INSERT INTO `Bitacora_timbre` (`accion`, `user`, `modulo`, `id_factura`, `tipo`,`timestamp`)
        // VALUES ('$accion','$user','$modulo', $idFactura,'$tipo',NOW())");
        $sth = $this->_db->query("
        INSERT INTO `Bitacora_timbre` (`accion`, `user`, `modulo`, `id_factura`, `tipo`,`timestamp`)
        VALUES ('$accion','$user','$modulo', $idFactura,'$tipo',NOW())
		");
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
                $moneda=$dataClientes['moneda'];  //Falta ID moneda

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

                        // $sqlRelaciones= "UPDATE facturas_relaciones 
                        // SET cfdi_relacionado        = $idUser
                        // WHERE id = $idRelacioando;";
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
                    // $sqlRelaciones="INSERT INTO facturas_relaciones (id_factura, cfdi_relacionado, tipo) VALUES (".$folio.",".$cfdi.",'FACTURA')";
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


    
}

