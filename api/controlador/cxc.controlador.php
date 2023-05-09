<?php

include_once 'model/api.modelo.php';

if($_SESSION['Moneda']=='Pesos'){

    $Moneda = 1;

}else{

    $Moneda = 1/$_SESSION['TC'];

}



class ControladorCxC extends apiModel{


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
    function updateFecha($datos,$column){
        $success=false;
        $data="Error";
        // print_r($data);
        if(!empty($datos)){
            // print_r($datos);
            $fecha = date_create_from_format('d/m/Y', $datos['date']);
            $date = $fecha->format('Y-m-d');
            // $date=$datos['date'];
            $id=$datos['id'];
            switch ($column) {
                case 'fecha_promesa':
                    $values="fecha_promesa = '$date'";
                    # code...
                    break;
                case 'fecha_inicioCredito':
                    // $diasCredito= $datos['diasCredito'];
                    $dateVencimiento = date_create_from_format('d/m/Y', $datos['fechaVencimiento']);
                    $fechaVencimineto = $dateVencimiento->format('Y-m-d');
                    // $fechaVencimineto=$datos['fechaVencimiento'];

                    $values="fecha_inicioCredito ='$date', fecha_vencimiento= '$fechaVencimineto'";

                    break;
                
                default:
                    break;
            }
            $query = "UPDATE facturas SET $values WHERE Id =$id";
            // print_r( $query);
            $resp = $this->updateTable($query);
            if ($resp==1) {
                $success=true;
                $msj='Exito al actualizar Fecha';
                $data='Exito';
            }else{
                $msj='Error al actualizar la tabla: ';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
    function createCxC($data){
        if(is_array($data)){
            // print_r($data);
            $flag=0;
            $datacomplementosPago=$data['complementosPago'];
            $datainfo=$data['info'];

            if(!empty($datainfo)){
                $fechaPago=$datainfo['fechaPago'];
                $financiera=$datainfo['financiera'];
                $formaDePago=$datainfo['formaDePago']; 
                $horaPago=$datainfo['horaPago'];
                $moneda=$datainfo['moneda'];  //Falta ID moneda
                if($moneda=='pesos'){
                    $moneda=1;
                }else{
                    $moneda=2;
                }
                $referencia=$datainfo['referencia'];
                $tipoDeCambio=$datainfo['tipoDeCambio'];
                $user=$datainfo['user'];
                $idUser=$datainfo['idUser'];
                $total=25563; // Falta recibir Total
                $now = new DateTime();
                $now = $now->format('Y-m-d H:i:s');
                date_default_timezone_set('UTC');
                $fechaHoraObj = DateTime::createFromFormat('m/d/Y H:i', $fechaPago.$horaPago);
                $datePago = $fechaHoraObj->format('Y-m-d H:i:s');

                // $datePago = DateTime::createFromFormat('d/m/Y', $fechaPago.$horaPago);

            }

               //Opcional
       

       
        $query= 'INSERT INTO pagos (id_agente, fecha_pago, forma_pago, moneda, tipo_cambio, total, referencia, fecha_creacion) 

            VALUES ('.$idUser.',"'.$datePago.'",'.$formaDePago.','.$moneda.','.$tipoDeCambio.','.$total.',
                    '.$referencia.',"'.$now.'")';
            // print_r($query);
            $resp = $this->getAllTableLastID($query);
            
            if(isset($resp['last_id'])&&!empty($resp['last_id'])){
                $idPago=$resp['last_id'];
                foreach($datacomplementosPago as $valueCp){
                    $fecha=$valueCp['Fecha'];
                    $folio=$valueCp['Folio'];
                    $monedaCp=$valueCp['Moneda'];//Falta ID moneda
                    if($monedaCp=='pesos'){
                        $monedaCp=1;
                    }else{
                        $monedaCp=2;
                    }
                    $montoPag=$valueCp['MontoPag'];
                    $noParcialida=$valueCp['NoParcialida'];
                    $saldo=$valueCp['Saldo'];
                    $totalCp=$valueCp['Total'];

                    $sqlRelaciones="INSERT INTO pagos_facturas (id_pago, id_factura, monto, parcialidad) VALUES (".$idPago.",'".$folio."',".$montoPag.",'".$noParcialida."')";
                    $resp = $this->getAllTableLastID($sqlRelaciones);
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
    // function getTabla(){
    //     $success=false;
    //     $data="Error";
    //     $query = "SELECT DISTINCT(Nombre) AS cliente, Id, dias_credito
    //     FROM clientes
    //     ORDER BY cliente ASC";
    //     $resp = $this->getAllTable($query);
    //     $datosClientes = $resp->fetch_all(MYSQLI_ASSOC);
    //     $arrayDatos=[];
    //     foreach($datosClientes as $valueCli){
    //         $idCliente=$valueCli['Id'];
    //         $nameCliente=$valueCli['cliente'];
    //         $diasCredito=$valueCli['dias_credito'];
    //         $sql = "SELECT f.id,
    //         f.total AS totalFactura, DATE(f.fecha) AS fechaFactura, f.moneda
    //         FROM facturas f 
    //         WHERE f.timbrado =1 
    //         AND f.cancelado =0 
    //         AND f.nota_credito = 0
    //         AND f.id_cliente= $idCliente";
    //         // print_r($sql);
    //         $respuestaFac = $this->getAllTable($sql);
    //         $datosFac = $respuestaFac->fetch_all(MYSQLI_ASSOC);
    //         if(!empty($datosFac)){
    //             $arrayCliente=[];
                
    //             $creditoPesos=0;
    //             $creditoDolares=0;
    //             $vencidoPesos=0;
    //             $vencidoDolares=0;
    //             foreach($datosFac as $valueFac){
    //                 $totalPesos=0;
    //                 $totalDolar=0;
    //                 $idFactura=$valueFac['id'];
    //                 if($valueFac['moneda']==1){
    //                     $totalPesos=floatval($valueFac['totalFactura']);
    //                 }else{
    //                     $totalDolar=floatval($valueFac['totalFactura']);
    //                 }
    //                 $sqlCP="SELECT pf.monto AS monto, p.moneda
    //                 FROM pagos_facturas pf
    //                 JOIN pagos p
    //                 ON p.id = pf.id_pago
    //                 WHERE pf.id_factura = $idFactura
    //                 AND p.timbrado = 1
    //                 AND p.cancelado = 0";
    //                 $respuestaCP = $this->getAllTable($sqlCP);
    //                 $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
    //                 $montoPesos=0;
    //                 $montoDolar=0;
    //                 if(!empty($datosCP)){
                        
    //                     foreach($datosCP as $valueCP){
    //                         if($valueCP['moneda']==1){
    //                             $montoPesos+=floatval($valueCP['monto']);
    //                         }else{
    //                             $montoDolar+=floatval($valueCP['monto']);
    //                         }
                            
    //                     }
    //                     $saldoPesos=$totalPesos-$montoPesos;
    //                     $saldoDolares=$totalDolar-$montoDolar;
    //                     $fechaActual=new DateTime();
    //                     $fechaVencimiento = new DateTime($valueFac['fechaFactura']);
    //                     $fechaVencimiento->modify("+".$diasCredito." days");


    //                     $diasVencidos = $fechaVencimiento->diff($fechaActual);
    //                     $diasVencidos = (int)$diasVencidos->format("%r%a");
    //                     if($diasVencidos<=0){
    //                         $creditoPesos += $saldoPesos;
    //                         $creditoDolares += $saldoDolares;
         
    //                             }
    //                             else {
    //                                 $vencidoPesos += $saldoPesos;
    //                                 $vencidoDolares += $saldoDolares;
    //                             }
    //                 }
    //             }
    //             $acccion="<div class='btn-group'>
    //                     <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
    //                     <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

    //                         <a type='button'  class='dropdown-item btnfactura'   id='fa" . $idCliente . "' >Factura</a>

                            
    //                     </div>
    //             </div>";
    //             $arrayCliente = array(
    //                 'acciones' => $acccion,
    //                 'id' => $idCliente,
    //                 'cliente'=> $nameCliente,
    //                 'vencidoMX'=> $vencidoPesos,
    //                 'vencidoUSD'=> $vencidoDolares, 
    //                 'creditoMX'=> $creditoPesos, 
    //                 'creditoUSD'=> $creditoDolares
        
    //             );
    //             $arrayDatos[]=$arrayCliente;

    //         }


    //     }
    //         if ($resp==true) {
    //             $success=true;
    //             $msj='Exito en consulta de datos';
    //             $data=$arrayDatos;
    //         }else{
    //             $msj='Error al consultar la tabla';
    //         }

    //     $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
    //     return $mensaje;

    // }
    

    // function getTabla(){
    //     $success=false;
    //     $data="Error";
    //     $query = "SELECT DISTINCT(Nombre) AS cliente, Id, dias_credito
    //     FROM clientes
    //     ORDER BY cliente ASC";
    //     $resp = $this->getAllTable($query);
    //     $datosClientes = $resp->fetch_all(MYSQLI_ASSOC);
    //     $arrayDatos=[];
    //     foreach($datosClientes as $valueCli){
    //         $idCliente=$valueCli['Id'];
    //         $nameCliente=$valueCli['cliente'];
    //         $diasCredito=$valueCli['dias_credito'];
    //         $sql = "SELECT f.id,
    //         f.total AS totalFactura, DATE(f.fecha) AS fechaFactura, f.moneda
    //         FROM facturas f 
    //         WHERE f.timbrado =1 
    //         AND f.cancelado =0 
    //         AND f.nota_credito = 0
    //         AND f.id_cliente= $idCliente";
    //         // print_r($sql);
    //         $respuestaFac = $this->getAllTable($sql);
    //         $datosFac = $respuestaFac->fetch_all(MYSQLI_ASSOC);
    //         if(!empty($datosFac)){
    //             $arrayCliente=[];
                
    //             $creditoPesos=0;
    //             $creditoDolares=0;
    //             $vencidoPesos=0;
    //             $vencidoDolares=0;
    //             foreach($datosFac as $valueFac){
    //                 $totalPesos=0;
    //                 $totalDolar=0;
    //                 $idFactura=$valueFac['id'];
    //                 if($valueFac['moneda']==1){
    //                     $totalPesos=floatval($valueFac['totalFactura']);
    //                 }else{
    //                     $totalDolar=floatval($valueFac['totalFactura']);
    //                 }
    //                 $sqlCP="SELECT pf.monto AS monto, p.moneda
    //                 FROM pagos_facturas pf
    //                 JOIN pagos p
    //                 ON p.id = pf.id_pago
    //                 WHERE pf.id_factura = $idFactura
    //                 AND p.timbrado = 1
    //                 AND p.cancelado = 0";
    //                 $respuestaCP = $this->getAllTable($sqlCP);
    //                 $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
    //                 $montoPesos=0;
    //                 $montoDolar=0;
    //                 if(!empty($datosCP)){
                        
    //                     foreach($datosCP as $valueCP){
    //                         if($valueCP['moneda']==1){
    //                             $montoPesos+=floatval($valueCP['monto']);
    //                         }else{
    //                             $montoDolar+=floatval($valueCP['monto']);
    //                         }
                            
    //                     }
    //                     $saldoPesos=$totalPesos-$montoPesos;
    //                     $saldoDolares=$totalDolar-$montoDolar;
    //                     $fechaActual=new DateTime();
    //                     $fechaVencimiento = new DateTime($valueFac['fechaFactura']);
    //                     $fechaVencimiento->modify("+".$diasCredito." days");


    //                     $diasVencidos = $fechaVencimiento->diff($fechaActual);
    //                     $diasVencidos = (int)$diasVencidos->format("%r%a");
    //                     if($diasVencidos<=0){
    //                         $creditoPesos += $saldoPesos;
    //                         $creditoDolares += $saldoDolares;
         
    //                             }
    //                             else {
    //                                 $vencidoPesos += $saldoPesos;
    //                                 $vencidoDolares += $saldoDolares;
    //                             }
    //                 }
    //             }
    //             $acccion="<div class='btn-group'>
    //                     <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
    //                     <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

    //                         <a type='button'  class='dropdown-item btnfactura'   id='fa" . $idCliente . "' >Factura</a>

                            
    //                     </div>
    //             </div>";
    //             $arrayCliente = array(
    //                 'acciones' => $acccion,
    //                 'id' => $idCliente,
    //                 'cliente'=> $nameCliente,
    //                 'vencidoMX'=> $vencidoPesos,
    //                 'vencidoUSD'=> $vencidoDolares, 
    //                 'creditoMX'=> $creditoPesos, 
    //                 'creditoUSD'=> $creditoDolares
        
    //             );
    //             $arrayDatos[]=$arrayCliente;

    //         }


    //     }
    //         if ($resp==true) {
    //             $success=true;
    //             $msj='Exito en consulta de datos';
    //             $data=$arrayDatos;
    //         }else{
    //             $msj='Error al consultar la tabla';
    //         }

    //     $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
    //     return $mensaje;

    // }
    function getTabla(){
        $success=false;
        $data="Error";
        $query = "SELECT DISTINCT(Nombre) AS cliente, Id, dias_credito
        FROM clientes
        ORDER BY cliente ASC";
        $resp = $this->getAllTable($query);
        $datosClientes = $resp->fetch_all(MYSQLI_ASSOC);
        $arrayDatos=[];
        foreach($datosClientes as $valueCli){
            $idCliente=$valueCli['Id'];
            $nameCliente=$valueCli['cliente'];
            $diasCredito=$valueCli['dias_credito'];
            $cerradoPesos=0;
            $cerradoDolares=0;
            $noCobradoPesos=0;
            $noCobradoDolares=0;
            ////DATOS FACTURA
            $creditoPesosTotal=0;
            $creditoDolaresTotal=0;
            $vencidoPesosTotal_1=0;
            $vencidoDolaresTotal_1=0;
            $vencidoPesosTotal_2=0;
            $vencidoDolaresTotal_2=0;
            $vencidoPesosTotal_3=0;
            $vencidoDolaresTotal_3=0;
            $total_1=0;
            $total_2=0;
            $total_3=0;
            $total_4=0;
            $arrayCliente=[];

            $queryOdv = "SELECT o.Id, DATE(o.Fecha) AS fecha, o.Subtotal, o.IvaPorcentaje, o.Iva, 
            o.Total, o.Moneda, o.TipoCambio, DATE(o.FechaCerrado) AS fechaCerrado,o.Cobrado
            FROM odv o 
            WHERE o.Status=2
            AND o.Cobrado=0
            AND o.Id_cliente = $idCliente";
            $respuestaOdv = $this->getAllTable($queryOdv);
            $datosOdv = $respuestaOdv->fetch_all(MYSQLI_ASSOC);
            foreach ($datosOdv as $value) {

                $cobrado=$value['Cobrado'];
                $moneda=$value['Moneda'];
                $fechaCerrado=$value['fechaCerrado'];
                $tipoCambio=$value['TipoCambio'];
                if($moneda==1){
                    $noCobradoPesos=$value['Total'];
                }else{
                    $noCobradoDolares=$value['Total'];
                }

                $fechaActual=new DateTime();
                $fechaVencimiento = new DateTime($fechaCerrado);
                $fechaVencimiento->modify("+".$diasCredito." days");


                $diasVencidos = $fechaVencimiento->diff($fechaActual);
                $diasVencidos = (int)$diasVencidos->format("%r%a");
                // if($diasVencidos<=0){
                //     $creditoPesosTotal += $noCobradoPesos;
                //     $creditoDolaresTotal += $noCobradoDolares;

                // }else {
                //     $vencidoPesosTotal += $noCobradoPesos;
                //     $vencidoDolaresTotal += $noCobradoDolares;
                // }


                if ($diasVencidos <= 0) {

                    $creditoPesosTotal += $noCobradoPesos;
                    $creditoDolaresTotal += $noCobradoDolares;
                    if($moneda==2){
                        $total_1+=$noCobradoDolares*$tipoCambio;
                    }else{
                        $total_1+=$noCobradoPesos;
                    }

                } elseif ($diasVencidos >= 1 && $diasVencidos <= 15) {

                    $vencidoPesosTotal_1 += $noCobradoPesos;
                    $vencidoDolaresTotal_1 += $noCobradoDolares;
                    if($moneda==2){
                        $total_2+=$noCobradoDolares*$tipoCambio;
                    }else{
                        $total_2+=$noCobradoPesos;
                    }

                } elseif ($diasVencidos >= 16 && $diasVencidos <= 30) {

                    $vencidoPesosTotal_2 += $noCobradoPesos;
                    $vencidoDolaresTotal_2 += $noCobradoDolares;
                    if($moneda==2){
                        $total_3+=$noCobradoDolares*$tipoCambio;
                    }else{
                        $total_3+=$noCobradoPesos;
                    }

                } elseif ($diasVencidos >= 31) {

                    $vencidoPesosTotal_3 += $noCobradoPesos;
                    $vencidoDolaresTotal_3 += $noCobradoDolares;
                    if($moneda==2){
                        $total_4+=$noCobradoDolares*$tipoCambio;
                    }else{
                        $total_4+=$noCobradoPesos;
                    }
                }
                }
                /////NOTA AGREGAR 0-15///
   
            }
            $accion="<div class='btn-group'>
            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                <a type='button'  class='dropdown-item btnfactura'   id='fa" . $idCliente . "' >Factura</a>

                
            </div>
            </div>";

            // $cerradoPesos=$creditoPesosTotal+$vencidoPesosTotal;
            // $cerradoDolares=$creditoDolaresTotal+$vencidoDolaresTotal;
            // $granTotal=$vencidoPesosTotal+$vencidoDolaresTotal+$creditoPesosTotal+$creditoDolaresTotal+$cerradoPesos+$cerradoDolares;
            $granTotal=$total_1+$total_2+$total_3+$total_4;
            $totalPesos=$creditoPesosTotal+$vencidoPesosTotal_1+$vencidoPesosTotal_2+$vencidoPesosTotal_3;
            $totalDolar=$creditoDolaresTotal+$vencidoDolaresTotal_1+$vencidoDolaresTotal_2+$vencidoDolaresTotal_3;
            if ($granTotal > 0) {
                $arrayCliente = array(
                    'acciones' => $accion,
                    'id' => $idCliente,
                    'cliente'=> $nameCliente,
                    'credito'=> $total_1*$GLOBALS["Moneda"],
                    'dias_15'=> $total_2*$GLOBALS["Moneda"],
                    'dias_16_30'=> $total_3*$GLOBALS["Moneda"],
                    'dias_30'=> $total_4*$GLOBALS["Moneda"],
                    'total'=> $granTotal*$GLOBALS["Moneda"],
                    'totalPesos'=> $totalPesos,
                    'totalDolar'=> $totalDolar,
                    // 'vencidoMX'=> $vencidoPesosTotal,
                    // 'vencidoUSD'=> $vencidoDolaresTotal, 
                    // 'creditoMX'=> $creditoPesosTotal, 
                    // 'creditoUSD'=> $creditoDolaresTotal,
                    // 'montoCerradoMX'=>$cerradoPesos,
                    // 'montoCerradoUSD'=>$cerradoDolares
                );
            $arrayDatos[]=$arrayCliente;

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
    // --------------------------------------------------------------------- 
    function getFacturas($id)
    {
        // $id = 2; //TEST
        $success = false;
        $data = "Error";
        $fechaActual = new DateTime();
        $query = " SELECT f.id, c.Nombre, f.moneda, f.subtotal, f.impuesto,f.impuesto_retenido AS impuestoR, f.total, f.fecha,c.dias_credito, 
        f.nota_credito AS NotaCredito,fecha_promesa AS fechaPromesa,fecha_inicioCredito AS fechaInicio,fecha_vencimiento AS fechaVencimiento, c.RazonSocial
        FROM facturas f
        JOIN clientes c ON c.Id = f.id_cliente
        WHERE f.id_cliente = $id 
        AND f.timbrado = 1 
        AND f.cancelado = 0
        AND f.nota_credito != 1";
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


 

            if ($valueFac['impuestoR'] != '') {
                $impuestoR = $valueFac['impuestoR'];
            } else {
                $impuestoR = 0;
            }
            $totalT =  $valueFac['subtotal'] + $valueFac['impuesto'] - $impuestoR;
            if ($totalT == $valueFac['total']) {
                $discrepancia = 0;
            } else {
                $discrepancia = 1;
            }
            $sqlCP = "SELECT pf.monto AS monto, p.moneda,  p.fecha_creacion AS fecha_pago
            FROM pagos_facturas pf
            JOIN pagos p
            ON p.id = pf.id_pago
            WHERE pf.id_factura = $idFactura
            AND p.timbrado = 1
            AND p.cancelado = 0";
            $respuestaCP = $this->getAllTable($sqlCP);
            $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
            $montoPesos = 0;
            $montoDolar = 0;
            $fechaPago=NULL;
            if(!empty($datosCP)){
                foreach ($datosCP as $valueCP) {
                    if ($valueCP['moneda'] == 1) {
                        $montoPesos += floatval($valueCP['monto']);
                        $totalPagado+=$montoPesos;
                        $fechaPago=$valueCP['fecha_pago'];
                    } else {
                        $montoDolar += floatval($valueCP['monto']);
                        $fechaPago=$valueCP['fecha_pago'];
                        $totalPagado+=$montoDolar;
                        
    
                    }
                }
            }

            // if ($valueFac['moneda'] == 1) {
            //     $saldoFinal = $valueFac['total'] - $montoPesos;
            //     $moneda="Pesos";
            // } else {
            //     $saldoFinal = $valueFac['total'] - $montoDolar;
            //     $moneda="Dolares";
            // }
            $montoNCPesos=0;
            $montoNCDolar=0;
            //Verificamos si tiene nota de credito la factura 
            $sqlNc="SELECT  f.id,f.moneda, f.total AS montoNC FROM facturas f
            JOIN facturas_relaciones fr ON f.id = fr.id_factura
            WHERE fr.tipo = 'NC'
            AND f.nota_credito = 1
            AND f.cancelado=0
            AND fr.cfdi_relacionado=$idFactura";
            $respNC = $this->getAllTable($sqlNc);
            $datosNC = $respNC->fetch_all(MYSQLI_ASSOC);
            if(!empty($datosNC)){
                foreach ($datosNC as $valueNC) {
                    if ($valueNC['moneda'] == 1) {
                        $montoNCPesos += floatval($valueNC['montoNC']);
                        
                    } else {
                        $montoNCDolar += floatval($valueNC['montoNC']);
    
                    }
                }
                // $montoNC=$respNC['totalNC'];
            }
            if ($valueFac['moneda'] == 1) {
                $saldoFinal = $valueFac['total'] - $montoPesos-$montoNCPesos;
                $totalPagado=$montoPesos+$montoNCPesos;
                $moneda="Pesos";
            } else {
                $saldoFinal = $valueFac['total'] - $montoDolar- $montoNCDolar;
                $totalPagado=$montoDolar+$montoNCDolar;
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

            if($valueFac['fechaPromesa']){
                $fechaData = new DateTime($valueFac['fechaPromesa']);
                $valueFac['fechaPromesa']=$fechaData->format('d/m/Y');
            }

            if($valueFac['fecha']){
                $fechaData = new DateTime($valueFac['fecha']);
                $valueFac['fecha'] = $fechaData->format('d/m/Y');    
            }
            if ($totalPagado < $valueFac['total']) {
                if ($diasVencidos >= 0) {
                    $statusVencimiento = 'PENDIENTE';
                    $diasVencidosColor = 'rgba(3, 108, 255, 0.5)';
                } else {
                    $statusVencimiento = 'VENCIDA';
                    $diasVencidosColor = 'rgba(249, 0, 0, 0.5)';
                }
            } else {
                $statusVencimiento = 'COBRADA';
                $diasVencidosColor = 'rgba(0, 255, 58, 0.5)';
            }
            $arreglo = array(
                'tipoFactura' => $tpFactura,
                // 'relacionNC' => $rela, //Relacion de notas de credito
                'id' => $idFactura,
                'cliente' => $valueFac['Nombre'],
                'razonSocial' => $valueFac['RazonSocial'],
                'dias_credito' => $diasCredito,
                'estilo' => $diasVencidosColor,
                'dias_vencidos' => $diasVencidos,
                'moneda' => $moneda,
                'fecha_pago' => $fechaPago,
                'fecha_vencimiento' => $fechaVencimiento,

                'fechaPromesa' => $valueFac['fechaPromesa'],

                'fechaPromesaDePago' => $valueFac['fechaPromesa'],
                'fechaInicioCredito' => $valueFac['fechaInicio'],


                'fechaInicio' => $valueFac['fechaInicio'],
                'fechaVencimiento' => $valueFac['fechaVencimiento'],
                // 'estilo' => $estilo,
                'status' => $statusVencimiento,
                'fecha' => $valueFac['fecha'],
                'subtotal' => $valueFac['subtotal'],
                'impuesto' => $valueFac['impuesto'],
                'impuestoR' => $impuestoR,
                'total' => $valueFac['total'],
                'importe_pagado' => $totalPagado,
                'saldo' => $saldoFinal,
                'discrepancia' => $discrepancia,
                'saldoColor' => $saldoColor
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

    // function getPedidos($id)
    // {
    //     // $id = 2; //TEST
    //     $success = false;
    //     $data = "Error";
    //     $fechaActual = new DateTime();
    //     $query = " SELECT o.Id, DATE(o.Fecha) AS fecha, o.Subtotal, o.IvaPorcentaje, o.Iva, 
    //     o.Total, o.Moneda, o.TipoCambio, DATE(o.FechaCerrado) AS fechaCerrado,o.Cobrado
    //     FROM odv o 
    //     WHERE o.Status=2
    //     AND o.Cobrado=0
    //     AND o.Id_cliente = $id";
    //     $resp = $this->getAllTable($query);
    //     $datosOdv = $resp->fetch_all(MYSQLI_ASSOC);
    //     $arrayDatos = [];
    //     // print_r($datosOdv);
    //     foreach ($datosOdv as $valueOdv) {
    //         $idFactura = $valueOdv['Id'];
    //         $statusVencimiento = '';
    //         $diasVencidosColor = '';
    //         $totalPagado = 0;
    //         $diasCredito=0;
    //         if(!empty($valueOdv['dias_credito'])){
    //             $diasCredito = (int)$valueOdv['dias_credito'];
    //         }
            
    //         $RelacionNC = 2;

    //         $fechaVencimiento = new DateTime($valueOdv['fecha']);
    //         $fechaVencimiento->modify("+$diasCredito days");
    //         $diasVencidos = $fechaActual->diff($fechaVencimiento);
    //         $diasVencidos = (int)$diasVencidos->format("%r%a");
    //         $fechaVencimiento = $fechaVencimiento->format('d/m/Y');


 

    //         // if ($valueOdv['impuestoR'] != '') {
    //         //     $impuestoR = $valueOdv['impuestoR'];
    //         // } else {
    //         //     $impuestoR = 0;
    //         // }
    //         $totalT =  $valueOdv['Subtotal'] + $valueOdv['Iva'] ;
    //         if ($totalT == $valueOdv['Total']) {
    //             $discrepancia = 0;
    //         } else {
    //             $discrepancia = 1;
    //         }
    //         $sqlCP = "SELECT pf.monto AS monto, p.moneda,  p.fecha_creacion AS fecha_pago
    //         FROM pagos_facturas pf
    //         JOIN pagos p
    //         ON p.id = pf.id_pago
    //         WHERE pf.id_factura = $idFactura
    //         AND p.timbrado = 1
    //         AND p.cancelado = 0";
    //         $respuestaCP = $this->getAllTable($sqlCP);
    //         $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
    //         $montoPesos = 0;
    //         $montoDolar = 0;
    //         $fechaPago=NULL;
    //         if(!empty($datosCP)){
    //             foreach ($datosCP as $valueCP) {
    //                 if ($valueCP['moneda'] == 1) {
    //                     $montoPesos += floatval($valueCP['monto']);
    //                     $totalPagado+=$montoPesos;
    //                     $fechaPago=$valueCP['fecha_pago'];
    //                 } else {
    //                     $montoDolar += floatval($valueCP['monto']);
    //                     $fechaPago=$valueCP['fecha_pago'];
    //                     $totalPagado+=$montoDolar;
                        
    
    //                 }
    //             }
    //         }

    //         // if ($valueOdv['moneda'] == 1) {
    //         //     $saldoFinal = $valueOdv['total'] - $montoPesos;
    //         //     $moneda="Pesos";
    //         // } else {
    //         //     $saldoFinal = $valueOdv['total'] - $montoDolar;
    //         //     $moneda="Dolares";
    //         // }
    //         $montoNCPesos=0;
    //         $montoNCDolar=0;
    //         //Verificamos si tiene nota de credito la factura 
    //         $sqlNc="SELECT  f.id,f.moneda, f.total AS montoNC FROM facturas f
    //         JOIN facturas_relaciones fr ON f.id = fr.id_factura
    //         WHERE fr.tipo = 'NC'
    //         AND f.nota_credito = 1
    //         AND f.cancelado=0
    //         AND fr.cfdi_relacionado=$idFactura";
    //         $respNC = $this->getAllTable($sqlNc);
    //         $datosNC = $respNC->fetch_all(MYSQLI_ASSOC);
    //         if(!empty($datosNC)){
    //             foreach ($datosNC as $valueNC) {
    //                 if ($valueNC['moneda'] == 1) {
    //                     $montoNCPesos += floatval($valueNC['montoNC']);
                        
    //                 } else {
    //                     $montoNCDolar += floatval($valueNC['montoNC']);
    
    //                 }
    //             }
    //             // $montoNC=$respNC['totalNC'];
    //         }
    //         if ($valueOdv['Moneda'] == 1) {
    //             $saldoFinal = $valueOdv['Total'] - $montoPesos-$montoNCPesos;
    //             $totalPagado=$montoPesos+$montoNCPesos;
    //             $moneda="Pesos";
    //         } else {
    //             $saldoFinal = $valueOdv['total'] - $montoDolar- $montoNCDolar;
    //             $totalPagado=$montoDolar+$montoNCDolar;
    //             $moneda="Dolares";
    //         }
            
    //         $tpFactura='Factura'; //Temporal en lo que se aplican las notas de credito
    //         $saldoColor = '';
    //         if ($saldoFinal < 0) {
    //             $saldoColor = 'background-color: rgba(249, 0, 0, 0.1)';
    //         }
            
    //         if($valueOdv['fechaInicio']){

    //             $fechaData = new DateTime($valueOdv['fechaInicio']);
    //             $valueOdv['fechaInicio']=$fechaData->format('d/m/Y');
    //         }

    //         if($valueOdv['fechaPromesa']){
    //             $fechaData = new DateTime($valueOdv['fechaPromesa']);
    //             $valueOdv['fechaPromesa']=$fechaData->format('d/m/Y');
    //         }

    //         if($valueOdv['fecha']){
    //             $fechaData = new DateTime($valueOdv['fecha']);
    //             $valueOdv['fecha'] = $fechaData->format('d/m/Y');    
    //         }
    //         if ($totalPagado < $valueOdv['total']) {
    //             if ($diasVencidos >= 0) {
    //                 $statusVencimiento = 'PENDIENTE';
    //                 $diasVencidosColor = 'rgba(3, 108, 255, 0.5)';
    //             } else {
    //                 $statusVencimiento = 'VENCIDA';
    //                 $diasVencidosColor = 'rgba(249, 0, 0, 0.5)';
    //             }
    //         } else {
    //             $statusVencimiento = 'COBRADA';
    //             $diasVencidosColor = 'rgba(0, 255, 58, 0.5)';
    //         }
    //         $arreglo = array(
    //             'tipoFactura' => $tpFactura,
    //             // 'relacionNC' => $rela, //Relacion de notas de credito
    //             'id' => $idFactura,
    //             'cliente' => $valueOdv['Nombre'],
    //             'razonSocial' => $valueOdv['RazonSocial'],
    //             'dias_credito' => $diasCredito,
    //             'estilo' => $diasVencidosColor,
    //             'dias_vencidos' => $diasVencidos,
    //             'moneda' => $moneda,
    //             'fecha_pago' => $fechaPago,
    //             'fecha_vencimiento' => $fechaVencimiento,

    //             'fechaPromesa' => $valueOdv['fechaPromesa'],

    //             'fechaPromesaDePago' => $valueOdv['fechaPromesa'],
    //             'fechaInicioCredito' => $valueOdv['fechaInicio'],


    //             'fechaInicio' => $valueOdv['fechaInicio'],
    //             'fechaVencimiento' => $valueOdv['fechaVencimiento'],
    //             // 'estilo' => $estilo,
    //             'status' => $statusVencimiento,
    //             'fecha' => $valueOdv['fecha'],
    //             'subtotal' => $valueOdv['subtotal'],
    //             'impuesto' => $valueOdv['impuesto'],
    //             // 'impuestoR' => $impuestoR,
    //             'total' => $valueOdv['total'],
    //             'importe_pagado' => $totalPagado,
    //             'saldo' => $saldoFinal,
    //             'discrepancia' => $discrepancia,
    //             'saldoColor' => $saldoColor
    //         );
    //         $arrayDatos[] = $arreglo;
    //     }
    //     if ($resp == true) {
    //         $success = true;
    //         $msj = 'Exito en consulta de datos';
    //         $data = $arrayDatos;
    //     } else {
    //         $msj = 'Error al consultar la tabla';
    //     }

    //     $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

    //     return $mensaje;
    // }

    function getPedidos($id){
        $success=false;
        $data="Error";

        $arrayDatos=[];

            $arrayCliente=[];

            $queryOdv = "SELECT o.Id, DATE(o.Fecha) AS fecha, o.Subtotal, o.IvaPorcentaje, o.Iva, 
            o.Total, o.Moneda, o.TipoCambio, DATE(o.FechaCerrado) AS fechaCerrado,o.Cobrado,
            cli.dias_credito
            FROM odv o
				LEFT JOIN clientes cli ON cli.Id = o.Id_cliente 
            WHERE o.Status=2
            AND o.Cobrado=0
            AND o.Id_cliente = $id";
            // print_r($queryOdv);
            $respuestaOdv = $this->getAllTable($queryOdv);
            $datosOdv = $respuestaOdv->fetch_all(MYSQLI_ASSOC);
            // print_r($datosOdv);
            foreach ($datosOdv as $value) {
                $diasCredito=$value['dias_credito'];
                $cobrado=$value['Cobrado'];
                $moneda=$value['Moneda'];
                $fechaCerrado=$value['fechaCerrado'];
                $tipoCambio=$value['TipoCambio'];
                $id=$value['Id'];
                $noCobradoPesos=0;
                $noCobradoDolares=0;
                if($moneda==1){
                    $noCobradoPesos=$value['Total'];
                }else{
                    $noCobradoDolares=$value['Total'];
                }

                $fechaActual=new DateTime();
                $fechaVencimiento = new DateTime($fechaCerrado);
                $fechaVencimiento->modify("+".$diasCredito." days");


                $diasVencidos = $fechaVencimiento->diff($fechaActual);
                $diasVencidos = (int)$diasVencidos->format("%r%a");
                if($diasVencidos<=0){
                    $creditoPesosTotal = $noCobradoPesos;
                    $creditoDolaresTotal = $noCobradoDolares;

                }else {
                    $vencidoPesosTotal = $noCobradoPesos;
                    $vencidoDolaresTotal = $noCobradoDolares;
                }


                // if ($diasVencidos <= 0) {

                //     $creditoPesosTotal = $noCobradoPesos;
                //     $creditoDolaresTotal = $noCobradoDolares;
                //     if($moneda==2){
                //         $total_1=$noCobradoDolares*$tipoCambio;
                //     }else{
                //         $total_1=$noCobradoPesos;
                //     }

                // } elseif ($diasVencidos >= 1 && $diasVencidos <= 15) {

                //     $vencidoPesosTotal_1 = $noCobradoPesos;
                //     $vencidoDolaresTotal_1 = $noCobradoDolares;
                //     if($moneda==2){
                //         $total_2=$noCobradoDolares*$tipoCambio;
                //     }else{
                //         $total_2=$noCobradoPesos;
                //     }

                // } elseif ($diasVencidos >= 16 && $diasVencidos <= 30) {

                //     $vencidoPesosTotal_2 = $noCobradoPesos;
                //     $vencidoDolaresTotal_2 = $noCobradoDolares;
                //     if($moneda==2){
                //         $total_3=$noCobradoDolares*$tipoCambio;
                //     }else{
                //         $total_3=$noCobradoPesos;
                //     }

                // } elseif ($diasVencidos >= 31) {

                //     $vencidoPesosTotal_3 = $noCobradoPesos;
                //     $vencidoDolaresTotal_3 = $noCobradoDolares;
                //     if($moneda==2){
                //         $total_4=$noCobradoDolares*$tipoCambio;
                //     }else{
                //         $total_4=$noCobradoPesos;
                //     }
                // }

                // $granTotal=$total_1+$total_2+$total_3+$total_4;
                // $totalPesos=$creditoPesosTotal+$vencidoPesosTotal_1+$vencidoPesosTotal_2+$vencidoPesosTotal_3;
                // $totalDolar=$creditoDolaresTotal+$vencidoDolaresTotal_1+$vencidoDolaresTotal_2+$vencidoDolaresTotal_3;
                if (isset($id)) {
                    $arrayCliente = array(
                        // 'acciones' => $accion,
                        'Id' => $id,
                        'Moneda' => $id,
                        'Subtotal'=> $vencidoPesosTotal,
                        'Iva'=> $vencidoDolaresTotal, 
                        'Total'=> $creditoPesosTotal, 
                        'Fecha'=> $creditoDolaresTotal,
                        'Observaciones'=> $creditoDolaresTotal,
                        'Estatus'=> $creditoDolaresTotal,
                    );
                $arrayDatos[]=$arrayCliente;
    
                }
            }
                /////NOTA AGREGAR 0-15///
            // $accion="<div class='btn-group'>
            // <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
            // <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

            //     <a type='button'  class='dropdown-item btnfactura'   id='fa" . $id . "' >Factura</a>

                
            // </div>
            // </div>";

            // $cerradoPesos=$creditoPesosTotal+$vencidoPesosTotal;
            // $cerradoDolares=$creditoDolaresTotal+$vencidoDolaresTotal;
            // $granTotal=$vencidoPesosTotal+$vencidoDolaresTotal+$creditoPesosTotal+$creditoDolaresTotal+$cerradoPesos+$cerradoDolares;

          
        
            if (isset($granTotal)) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$arrayDatos;
            }else{
                $msj='Error al consultar la tabla';
            }

            $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
        
            return $mensaje;

    }
}
