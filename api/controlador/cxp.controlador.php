<?php

include_once 'model/api.modelo.php';

if($_SESSION['Moneda']=='Pesos'){

    $Moneda = 1;

}else{

    $Moneda = 1/$_SESSION['TC'];

}



class ControladorCxP extends apiModel{


    function autoCompleteOdv($string)
    {

        $json = [];

        $data = [];

        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";

        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {

                $data[] = ['value' => $datos['Nombre'], 'data' => $datos['Id']];
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
    function updateFecha($data,$column){
        $success=false;
        $data="Error";
        if(!empty($date)){
            $date=$data['date'];
            $id=$data['id'];
            switch ($column) {
                case 'fecha_promesa':
                    $values='fecha_promesa = '.$date;
                    # code...
                    break;
                case 'fecha_inicioCredito':
                    $fechaVencimineto=$data['fechaVencimineto'];

                    $values='fecha_promesa = '.$date.', fechaVencimiento= '.$fechaVencimineto;

                    break;
                
                default:
                    break;
            }
            $query = "UPDATE facturas SET $values WHERE Id =$id";
            $resp = $this->updateTable($query);
            if ($resp==1) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$data;
            }else{
                $msj='Error al consultar la tabla: ';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data['nextID'], "message" => $msj);
    
        return $mensaje;

    }
    function aplicarPago($datos){
        $success=false;
        $data="Error";
        if(is_array($datos)&&!empty($datos)){
            // print_r($data);
            $flag=0;
        
            $fechaPago=date_create_from_format('d/m/Y', $datos['fechaDePago'])->format('Y-m-d');
            $importePagado=(float)$datos['importeDePago'];
            $numBanco=0;
            $numCheque=0;
            $numReferenci=0;
            $referenciaNC=0;
            $TipoPago=$datos['tipoDePago']; //Tipo Pago
            $metodoPago=$datos['metodoPago'];
            $moneda=$datos['moneda'];
            $idUser = $datos['idUser'];
            $idFactura = $datos['folioFactura'];
            $abono=0;
            if($TipoPago==2){
                $abono=$importePagado;
            }
            switch ($metodoPago) {
            //     case 1:
            // $numBanco=0;
            // $numCheque=0;
            // $numReferenci=0;
            // $referenciaNC=0;
            //         break;
                case 7:
            $numBanco=$datos['metodoDePagoInput_1'];
            $numCheque=$datos['metodoDePagoInput_2'];
                    break;
                case 9:
            $numBanco=$datos['metodoDePagoInput_1'];
            $numReferenci=$datos['metodoDePagoInput_2'];
                    break;
                case 10:
            $numBanco=$datos['metodoDePagoInput_1'];
            $numReferenci=$datos['metodoDePagoInput_2'];
                    break;
                case 11:
            $referenciaNC=$datos['metodoDePagoInput_1'];
                    break;
                
                default:
                    # code...
                    break;
            }

            $query= 'INSERT INTO pagoscfdis (id_usuario, fecha_pago, TipoPago, moneda, Abono, total, Num_Banco, Num_Cheque, Num_Referencia, Referencia_NotaCredito, metodoPago) 
                    VALUES ('.$idUser.',"'.$fechaPago.'",'.$TipoPago.','.$moneda.','.$abono.','.$importePagado.',
                    '.$numBanco.','.$numCheque.','.$numReferenci.','.$referenciaNC.','.$metodoPago.')';
            // print_r($query);
            $resp = $this->getAllTableLastID($query);
            // print_r($resp);
            if(isset($resp['last_id'])&&!empty($resp['last_id'])){
                $idPago=$resp['last_id'];
                    $sqlRelaciones="INSERT INTO pagos_cfdis_relacional (id_pago, id_cfdis, monto, tipo) VALUES (".$idPago.",'".$idFactura."',".$importePagado.",".$TipoPago.")";
                    $resp = $this->getAllTableLastID($sqlRelaciones);
                }
                    if($resp){
                        $sqlUpdate="UPDATE cfdis_recibidos SET saldo=saldo-$importePagado, importePagado=importePagado+$importePagado WHERE id=$idFactura";
                        $resp = $this->updateTable($sqlUpdate);
                        if($resp==1){
                            $success=true;
                            $msj='Factura agregado correctamente';
                            $data=$resp['last_id'];
                        }
                           
                    }else{
                        $msj='Error al aplicar pago';
                    }
        }
        
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

        return $mensaje;

    }
    function getTabla(){
        $success=false;
        $data="Error";
        $query = "SELECT DISTINCT(p.Id), p.RazonSocial AS proveedor, p.DiasCredito AS diasCredito
        FROM proveedores p
        ORDER BY p.Id";
        $resp = $this->getAllTable($query);
        $datosClientes = $resp->fetch_all(MYSQLI_ASSOC);
        $arrayDatos=[];
        foreach($datosClientes as $valueCli){
            $idProveedor=$valueCli['Id'];
            $nameProveedor=$valueCli['proveedor'];
            $diasCredito=$valueCli['diasCredito'];
            $sql = "SELECT cr.id AS idFactura, cr.FechaInicioCredito AS fechaInicioCredito, cr.total AS totalCfdi, 
            cr.moneda AS moneda, cr.subtotal AS subtotal, DATE(cr.fecha)AS fechaFactura
            FROM cfdis_recibidos cr
            JOIN cfdis_odc co ON co.id_cfdis = cr.id
            WHERE co.id_proveedor = $idProveedor
            AND cr.estatus =1
            ";
            // print_r($sql);
            $respuestaFac = $this->getAllTable($sql);
            $datosFac = $respuestaFac->fetch_all(MYSQLI_ASSOC);
            if(!empty($datosFac)){
                $arrayCliente=[];
                
                $creditoPesos=0;
                $creditoDolares=0;
                $vencidoPesos=0;
                $vencidoDolares=0;
                $totalPesos=0;
                $totalDolar=0;
                $saldoPesos=0;
                $saldoDolares=0;
                foreach($datosFac as $valueFac){
                  
                    $totalPesos=0;
                    $totalDolar=0;
                    $idFactura=$valueFac['idFactura'];
                    if($valueFac['moneda']==1){
                        $totalPesos=floatval($valueFac['totalCfdi']);
                    }else{
                        $totalDolar=floatval($valueFac['totalCfdi']);
                    }

                    $sqlCP="SELECT p.total AS monto, p.moneda FROM pagoscfdis p
                    JOIN pagos_cfdis_relacional pcr ON p.id = pcr.id_pago
                    WHERE p.timbrado = 1
                    AND p.cancelado = 0
                    AND pcr.id_cfdis =$idFactura";
                    $respuestaCP = $this->getAllTable($sqlCP);
                    $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
                    $montoPesos=0;
                    $montoDolar=0;
                    if(!empty($datosCP)){
  
                        foreach($datosCP as $valueCP){
                            if($valueCP['moneda']==1){
                                $montoPesos+=floatval($valueCP['monto']);
                            }else{
                                $montoDolar+=floatval($valueCP['monto']);
                            }
                            
                        }
                    }

                        $saldoPesos+=$totalPesos-$montoPesos;
                        $saldoDolares+=$totalDolar-$montoDolar;
                        // if($idProveedor==2){
                        //     print_r("TotalPesos: ".$totalPesos);
                        //     print_r("\n");
                        //     print_r("TotalDolar: ".$totalDolar);
                        //     print_r("\n");
                        //     print_r("SaldoPesos: ".$saldoPesos);
                        //     print_r("\n");
                        //     print_r("SaldoDolar: ".$saldoDolares);
                        //     print_r("\n");
                        //     print_r("montoPesos: ".$montoPesos);
                        //     print_r("\n");
                        //     print_r("montoPesos: ".$montoDolar);
                        //     print_r("\n");
                        // }
                  
                        $fechaActual=new DateTime();
                        $fechaVencimiento = new DateTime($valueFac['fechaFactura']);
                        $fechaVencimiento->modify("+".$diasCredito." days");


                        $diasVencidos = $fechaVencimiento->diff($fechaActual);
                        $diasVencidos = (int)$diasVencidos->format("%r%a");
                        if($diasVencidos<=0){
                            $creditoPesos += $saldoPesos;
                            $creditoDolares += $saldoDolares;
         
                                }
                                else {
                                    $vencidoPesos += $saldoPesos;
                                    $vencidoDolares += $saldoDolares;
                                }
                 
                }
                $acccion="<div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                            <a type='button'  class='dropdown-item btnfactura'   id='fa" . $idProveedor . "' >Factura</a>

                            <a type='button'  class='dropdown-item btnPDFDownload'   id='pd" . $idProveedor . "' name='" . $idProveedor . "' >Generar PDF</a>
                            <a type='button'  class='dropdown-item btnPDFView'   id='pw" . $idProveedor . "' name='" . $idProveedor . "' >Ver PDF</a>
            
                            
                        </div>
                </div>";




                $arrayCliente = array(
                    'acciones' => $acccion,
                    'id' => $idProveedor,
                    'proveedor'=> $nameProveedor,
                    'vencidoMX'=> $vencidoPesos,
                    'vencidoUSD'=> $vencidoDolares, 
                    'creditoMX'=> $creditoPesos, 
                    'creditoUSD'=> $creditoDolares
        
                );
                $arrayDatos[]=$arrayCliente;

            }


        }
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$arrayDatos;
            }else{
                $msj='Error al consultar la tabla';
            }

        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function getFacturas($id)
    {
        // $id = 2; //TEST
        $success = false;
        $data = "Error";
        $fechaActual = new DateTime();
        $query = " SELECT cr.id, cr.fecha, cr.total, cr.subtotal, pro.Nombre, cr.iva,cr.retencion_isr AS RetISR, 
        pro.Id, pro.DiasCredito AS dias_credito, cr.retencion_iva AS RetIVA,
        cr.moneda, cr.FechaInicioCredito AS fechaInicio , cr.folio
        FROM cfdis_recibidos cr
        JOIN cfdis_odc co ON co.id_cfdis = cr.id
        JOIN proveedores pro ON pro.Id = co.id_proveedor
        WHERE co.id_proveedor = $id
        AND cancelada =0";
        $resp = $this->getAllTable($query);
        $dataFactura = $resp->fetch_all(MYSQLI_ASSOC);
        $arrayDatos = [];

        foreach ($dataFactura as $valueFac) {
            $idFactura = $valueFac['id'];
            $statusVencimiento = '';
            $diasVencidosColor = '';
            $totalPagado = 0;
            $diasCredito=0;
            if(!empty($valueFac['dias_credito'])){
                $diasCredito = (int)$valueFac['dias_credito'];
            }
            
            $RelacionNC = 2;

            $fechaVencimiento = new DateTime($valueFac['fecha']);
            $fechaVencimiento->modify("+$diasCredito days");
            $diasVencidos = $fechaActual->diff($fechaVencimiento);
            $diasVencidos = (int)$diasVencidos->format("%r%a");
            $fechaVencimiento = $fechaVencimiento->format('d/m/Y');

            // if ($valueFac['impuestoR'] != '') {
            //     $impuestoR = $valueFac['impuestoR'];
            // } else {
            //     $impuestoR = 0;
            // }
            // $totalT =  $valueFac['subtotal'] + $valueFac['impuesto'] - $impuestoR;
            // if ($totalT == $valueFac['total']) {
            //     $discrepancia = 0;
            // } else {
            //     $discrepancia = 1;
            // }
            $sqlCP = "SELECT pc.moneda, pc.total AS monto, pc.fecha_pago, pc.status FROM pagoscfdis pc
            JOIN pagos_cfdis_relacional pcr ON pcr.id_pago = pc.id
            WHERE pcr.id_cfdis = $idFactura
            AND pc.timbrado =1
            AND pc.cancelado=0";
            $respuestaCP = $this->getAllTable($sqlCP);
            $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
            $montoPesos = 0;
            $montoDolar = 0;
            $fechaPago=NULL;
            if(!empty($datosCP)){
                foreach ($datosCP as $valueCP) {
                    if ($valueCP['moneda'] == 1) {
                        $montoPesos += floatval($valueCP['monto']);
                        $fechaPago=$valueCP['fecha_pago'];
                        $importePagado=$montoPesos;
                    } else {
                        $montoDolar += floatval($valueCP['monto']);
                        $fechaPago=$valueCP['fecha_pago'];
                        $importePagado=$montoDolar;

    
                    }
                   if($valueCP['fecha_pago']==1){
                    $status='PENDIENTE';
                   }
                }
            }

            if ($valueFac['moneda'] == 1) {
                $saldoFinal = $valueFac['total'] - $montoPesos;
                $moneda="Pesos";
            } else {
                $saldoFinal = $valueFac['total'] - $montoDolar;
                $moneda="Dolares";
            }
            $tpFactura='Factura'; //Temporal en lo que se aplican las notas de credito
            $saldoColor = '';
            if ($saldoFinal < 0) {
                $saldoColor = 'background-color: rgba(249, 0, 0, 0.1)';
            }
            
            if($valueFac['fechaInicio']){

                $fechaData = new DateTime($valueFac['fechaInicio']);
                $valueFac['fechaInicio']=$fechaData->format('d/m/Y');
            }

            // if($idFactura==2){
            //     print_r($saldoFinal);
            // }
            if($saldoFinal>0){
                $btnPago=" <button id='$idFactura' type='button' class='btn btn-primary btnPago'>Aplicar Pago</button>";

            }else{
            $btnPago=" <button id='$idFactura' type='button' class='btn btn-primary btnPago' disabled>Aplicar Pago</button>";

            }
            $arreglo = array(
                'tipoFactura' => $tpFactura,
                // 'relacionNC' => $rela, //Relacion de notas de credito
                'id' => $idFactura,
                'proveedor' => $valueFac['Nombre'],
                'folio' => $valueFac['folio'],
                'dias_credito' => $diasCredito,
                'dias_vencidos_color' => $diasVencidosColor,
                'dias_vencidos' => $diasVencidos,
                'moneda' => $moneda,
                'fecha_pago' => $fechaPago,
                'fecha_vencimiento' => $fechaVencimiento,

                'fechaInicio' => $valueFac['fechaInicio'],
                // 'estilo' => $estilo,
                'status' => $statusVencimiento,
                'fecha' => $valueFac['fecha'],
                'btnPago' => $btnPago,
                'total' => $valueFac['total'],
                'importe_pagado' => $totalPagado,
                'saldo' => $saldoFinal,
                'importe_pagado' => $importePagado,
                'saldoColor' => $saldoColor,
                'impuesto' => $valueFac['iva'],
                'Impuesto_retenido' => $valueFac['RetIVA'],
                'subTotal' => $valueFac['subtotal'],
            );
            $arrayDatos[] = $arreglo;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            $data = $arrayDatos;
        } else {
            $msj = 'Error al consultar la tabla';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getFacturaPDF($id,$userName)
    {
        $success = false;
        $data = "Error";
        $fechaActual = new DateTime();
        $queryPro="SELECT pro.Id AS idProveedor, pro.Nombre, pro.DiasCredito
        FROM proveedores pro 
        WHERE pro.Id=$id";
        $respPro = $this->getRow($queryPro);
        $diasCredito=$respPro['DiasCredito'];

        $query = " SELECT co.id_cfdis AS noFactura, co.id_odc AS noPedido, cr.fecha, cr.importePagado, cr.saldo ,cr.moneda,
        IF(cr.moneda = 1, cr.total, cr.total / cr.tipo_cambio) AS MN,
        IF(cr.moneda = 1, cr.total * cr.tipo_cambio, cr.total) AS DLLS,
        DATE_ADD(cr.fecha, INTERVAL 15 DAY) AS fechaVencimiento
        FROM cfdis_odc co 
        LEFT JOIN cfdis_recibidos cr ON co.id_cfdis = cr.id
        WHERE co.id_proveedor =$id
        AND cancelada =0";
        // $TC=$_SESSION['TC'];
        $resp = $this->getAllTable($query);
        $dataFactura = $resp->fetch_all(MYSQLI_ASSOC);
        $arrayDatos = [];
        print_r($dataFactura);
        foreach ($dataFactura as $valueFac) {
            $arrayDatos[]=$valueFac;
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            $data = $arrayDatos;
        } else {
            $msj = 'Error al consultar la tabla';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getFacturaId($id){
        $success=false;
        $data="Error";
        if(!empty($id)){
            $sql = "SELECT cr.id, cr.emisor_nombre AS emisor, DATE(cr.fecha) AS fecha, cr.moneda, cr.total FROM cfdis_recibidos cr 
            WHERE cr.id =$id";
            $resp = $this->getAllTable($sql);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()){
                $data=$datosBD;
            }
            if ($resp==true) {
                $success=true;
                $msj='Exito en consulta de datos';
                // $data=$data;
            }else{
                $msj='Error al consultar la factura: '.$id;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    }
    function getMetodoPago()
    {
        $success = false;
        $data = "Error";

        $sql = "SELECT * FROM metodo_pago WHERE Id IN (1,7,9,10,11)";
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


    // function generarDataPDFOdv(){

    // }

}

?>