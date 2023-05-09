<?php

include_once 'model/api.modelo.php';

if($_SESSION['Moneda']=='Pesos'){

    $Moneda = 1;

}else{

    $Moneda = 1/$_SESSION['TC'];

}



class ControladorGastos extends apiModel{


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
    function getTabla(){
        $success=false;
        $data="Error";
        $query = "SELECT cr.*, m.Nombre AS nameMoneda
        FROM cfdis_recibidos cr 
        JOIN cfdis_recibidos_conceptos crc ON crc.id_factura = cr.id
        JOIN moneda m ON m.Id = cr.moneda
        GROUP BY cr.id";
        $resp = $this->getAllTable($query);
        $dataCfdi = $resp->fetch_all(MYSQLI_ASSOC);
        $arrayDatos=[];
        foreach($dataCfdi as $valCfdi){
            $idFactura=$valCfdi['id'];

            $sqlConceptos="SELECT cr.id, cr.id_factura, cr.autorizada, cr.evidencia, cr.id_gasto, cr.descripcion, cr.unidad, cr.cantidad, cr.vu, cr.importe, tg.referencia
            FROM cfdis_recibidos_conceptos cr
            LEFT JOIN tipos_gastos tg
            ON tg.id = cr.id_gasto
            WHERE id_factura = 1";
             $respConceptos = $this->getAllTable($sqlConceptos);
             $dataConceptos = $respConceptos->fetch_all(MYSQLI_ASSOC);
            //  $cConceptosArchivados = 0;
            //  $auxReferencia = true;
             foreach ($dataConceptos as $datos2) {
                // if ($datos2['id_gasto'] != 0 && $datos2['evidencia'] != 0) {
                //     $cConceptosArchivados++;
                // } else {
                //     if ($auxReferencia == true) {
                //         if ($datos2["id_referencia"] == "") {
                //             $auxReferencia = false;
                //         }
                //     }
                // }
                $conceptos[] = array(
                    'id' => $datos2['id'],
                    'autorizada' => $datos2['autorizada'],
                    'evidencia' => $datos2['evidencia'],
                    'referencia' => $datos2['referencia'],
                    'id_gasto' => $datos2['id_gasto'],
                    'id_factura' => $datos2['id_factura'],
                    'descripcion' => $datos2['descripcion'],
                    'unidad' => $datos2['unidad'],
                    'cantidad' => $datos2['cantidad'],
                    'vu' => (float)$datos2['vu'],
                    'importe' => (float)$datos2['importe'],
                );
            }
            // print_r($dataConceptos);
            $accion="`<i class='btnView btn btn-primary bx bx-plus'></i>`,";

            $arreglo = array(
                'acciones' => $accion,
                'id' => $valCfdi['id'],
                'autorizada' => $valCfdi['autorizada'],
                'cancelada' => $valCfdi['cancelada'],
                'evidencia' => $valCfdi['evidencia'],
                // 'referencia' => $valCfdi['referencia'],
                'id_gasto' => $valCfdi['id_gasto'],
                'id_referencia' => $valCfdi['id_referencia'],
                'folio' => $valCfdi['folio'],
                'uuid' => $valCfdi['uuid'],
                // 'fecha_timestamp' => $fechaTimestamp,
                'metodo_pago' => $valCfdi['metodo_pago'],
                'emisorRfc' => mb_strtoupper($valCfdi['emisor_rfc']),
                'emisorNombre' => mb_strtoupper($valCfdi['emisor_nombre']),
                // 'fecha' => $fechaFormatted,
                'concepto' => $conceptos,
                'tipo_cambio' => $valCfdi['tipo_cambio'],
                'subtotal' => $valCfdi['subtotal'],
                'iva' => $valCfdi['iva'],
                'total' => $valCfdi['total'],
                'moneda' => $valCfdi['nameMoneda'],
                'notaDeCredito'=>' '
                // 'tipo_comprobante' => $valCfdi['tipo_comprobante'],
                // 'conceptos' => $conceptos,
                // 'utilizado' => $utilizado

            );
            $arrayDatos[]=$arreglo;
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
        $query = " SELECT f.id, c.Nombre, f.moneda, f.subtotal, f.impuesto,f.impuesto_retenido AS impuestoR, f.total, f.fecha,c.dias_credito, f.nota_credito AS NotaCredito,fecha_promesa AS fechaPromesa,fecha_inicioCredito AS fechaInicio,fecha_vencimiento AS fechaVencimiento, c.RazonSocial
        FROM facturas f
        JOIN clientes c ON c.Id = f.id_cliente
        WHERE f.id_cliente = $id AND f.timbrado = 1 AND f.cancelado = 0";
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
            // print_r($datosCP);
            foreach ($datosCP as $valueCP) {
                if ($valueCP['moneda'] == 1) {
                    $montoPesos += floatval($valueCP['monto']);
                    $fechaPago=$valueCP['fecha_pago'];
                } else {
                    $montoDolar += floatval($valueCP['monto']);
                    $fechaPago=$valueCP['fecha_pago'];

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
            $arreglo = array(
                'tipoFactura' => $tpFactura,
                // 'relacionNC' => $rela, //Relacion de notas de credito
                'id' => $idFactura,
                'cliente' => $valueFac['Nombre'],
                'razonSocial' => $valueFac['RazonSocial'],
                'dias_credito' => $diasCredito,
                'dias_vencidos_color' => $diasVencidosColor,
                'dias_vencidos' => $diasVencidos,
                'moneda' => $moneda,
                'fecha_pago' => $fechaPago,
                'fecha_vencimiento' => $fechaVencimiento,

                'fechaPromesa' => $valueFac['fechaPromesa'],
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

}

?>