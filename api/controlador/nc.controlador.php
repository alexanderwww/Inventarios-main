<?php

include_once 'model/api.modelo.php';

if ($_SESSION['Moneda'] == 'Pesos') {

    $Moneda = 1;
} else {

    $Moneda = 1 / $_SESSION['TC'];
}



class ControladorNC extends apiModel
{


    function autoCompleteFactura($string, $Id, $moneda)
    {

        $json = [];

        $data = [];

        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * FROM facturas WHERE Id LIKE '%" . (int)$string . "%';";
        $query = "SELECT DISTINCT(f.id), f.total, cli.Nombre, cli.RazonSocial, f.moneda FROM facturas f 
        LEFT JOIN clientes cli ON f.id_cliente = cli.Id
		LEFT JOIN pagos_facturas pf ON pf.id_factura = f.id  
        WHERE f.id LIKE '%" . (int)$string . "%' 
        AND f.id_cliente =$Id 
        AND pf.id IS NULL 
        AND f.nota_credito =0
        AND f.moneda=$moneda;";
        // print_r($query);
        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {
                if ($datos['moneda'] == 1) {
                    $moneda = "MXN";
                } else {
                    $moneda = "USD";
                }

                $data[] = ['data' => $datos['id'], 'precio' => $datos['total'], 'value' => "[" . $datos['id'] . "] " . $datos['RazonSocial'] . " - $" . number_format($datos['total'], 2, '.', ',') . $moneda];
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
    function autoCompleteOdv($string)
    {

        $json = [];

        $data = [];

        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";

        $query = "SELECT f.id, f.total, cli.Nombre, cli.RazonSocial, f.moneda FROM facturas f 
        LEFT JOIN clientes cli ON f.id_cliente = cli.IdWHERE Id LIKE '%" . (int)$string . "%';";

        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {
                if ($datos['moneda'] == 1) {
                    $moneda = "MXN";
                } else {
                    $moneda = "USD";
                }

                $data[] = ['value' => $datos['id'], 'precio' => $datos['total'], 'data' => "[" . $datos['id'] . "] " . $datos['RazonSocial'] . " - $" . number_format($datos['total'], 2, '.', ',') . $moneda];
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
    function getFactura($id)
    {

        $query = "	SELECT f.id, f.total, DATE(f.fecha) AS fecha, f.moneda
        FROM facturas f
        WHERE f.status = 1 AND f.timbrado = 1 AND f.cancelado = 0 AND f.id =$id;";

        $resp = $this->getRow($query);
        $idFactura = $resp['id'];
        $totalPago = $resp['total'];
        ///Se buscan los pagos de la factura
        $slqPago = "SELECT SUM(monto) AS pagos
        FROM pagos_facturas pf
        JOIN pagos p
        ON p.id = pf.id_pago
        WHERE pf.id_factura = '$idFactura' AND cancelado = 0";

        $respPago = $this->getRow($slqPago);
        $montoPago = 0;
        if (!empty($respPago['pagos'])) {
            $montoPago = $respPago['pagos'];
        }
        $saldo = (float)$totalPago - (float)$montoPago;

        $fecha = date('d/m/Y', strtotime($resp['fecha']));
        $slqPar = "					SELECT COUNT(pf.id)+1 AS cantidad
        FROM pagos_facturas pf
        JOIN pagos p
        ON p.id = pf.id_pago
        WHERE pf.id_factura = $idFactura AND p.cancelado = 0";

        $resPar = $this->getRow($slqPar);
        $noParcialida = $resPar['cantidad'];
        if (!empty($resp)) {
            if ($resp['moneda'] == 1) {
                $moneda = 'MXN';
            } else {
                $moneda = 'USD';
            }

            // $data[] = $resp;
            $array[] = array(
                'Folio' => $resp['id'],
                'Fecha' => $fecha,
                'Total' => $totalPago,
                'Saldo' =>  $saldo,
                'Moneda' => $moneda,
                'NoParcialidad' => $noParcialida,
                'MontoPago' => $montoPago

            );

            $data = $array;
            $msj = 'Exito en consulta de datos';
            $success = true;
        } else {
            $data = 'Error';
            $msj = "Datos Vacios";
            $success = false;
        }


        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }


    function getLastID($tabla)
    {
        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            $resp = $this->nextID($tabla);
            $data = [];
            $data['nextID'] = $resp['Auto_increment'];
            if ($resp['success'] == 1) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data['nextID'], "message" => $msj);

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
    function createNC($datos)
    {
        $success = false;
        $data = "Error";
        $msj = 'Error al generar';
        // print_r($data);

        if (is_array($datos)) {
            // print_R($data);
            $flag = 0;
            $cfdi = ' ';
            $totalFacturas = 0;
            $dataClientes = $datos['infoCliente'];
            $dataBalanceConceptos = $datos['infoBalanceConceptos'];

            $dataConceptos = $datos['infoConceptos'];
            $datainfoCFDIS = $datos['infoCFDIS'];

            if (!empty($dataClientes)) {
                $idCliente = $dataClientes['cliente'];
                $idUser = $dataClientes['idUser'];
                // $regimenFiscal=$dataClientes['regimenFiscal']; //Es para hacer update al cliente
                $folio = $dataClientes['folio'];
                $moneda = $dataClientes['moneda'];  //Falta ID moneda
                // if ($moneda == 'pesos') {
                //     $moneda = 1;
                // } else {
                //     $moneda = 2;
                // }
                $tipoDeCambio = $dataClientes['tipoDeCambio'];
                $usoCFDI = $dataClientes['usoCFDI'];
                $metodoDePago = $dataClientes['metodoDePago'];
                $formaDePago = $dataClientes['formaDePago'];
            }else{
                $flag=1;
            }

            if (!empty($dataBalanceConceptos)) {
                $observaciones = $dataBalanceConceptos['balanceObservaciones'] ? $dataBalanceConceptos['balanceObservaciones'] : ' ';
                $subtotal = $dataBalanceConceptos['balanceSubtotal'];
                $impuestosRetenidos = $dataBalanceConceptos['balanceImpuestosRetenidos'];
                $impuestoTrasladado = $dataBalanceConceptos['balanceImpuestoTrasladado'];
                $total = $dataBalanceConceptos['balanceTotal'];
            }else{
                $flag=1;
            }

            //Opcional
            if (!empty($datainfoCFDIS)) {
                foreach ($datainfoCFDIS as $valueTotal) {
                    $totalFacturas += (float)$valueTotal['precio'];
                }
            }
            if($flag==0){
                if ($totalFacturas <= $total) {
                    $query = 'INSERT INTO facturas (id_usuario,moneda,metodo_pago,forma_pago,uso_cfdi, tipo_cambio, id_cliente, subtotal, impuesto,
                            impuesto_retenido,total,observaciones,cfdi_relacionado,nota_credito) 
                            VALUES (' . $idUser . ',' . $moneda . ',' . $metodoDePago . ',' . $formaDePago . ',' . $usoCFDI . ',
                                    ' . $tipoDeCambio . ',' . $idCliente . ',' . $subtotal . ',
                                    ' . $impuestoTrasladado . ',' . $impuestosRetenidos . ',' . $total . ',"' . $observaciones . '","' . $cfdi . '",1)';
                    // print_r($query);
                    $resp = $this->getAllTableLastID($query);
                    $idNC=$resp['last_id'];
                    if (!empty($datainfoCFDIS)) {
                        foreach ($datainfoCFDIS as $valueCfdi) {
                            $folio = $valueCfdi['folio']; //Id Factura
    
                            $sqlRelaciones = "INSERT INTO facturas_relaciones (id_factura, cfdi_relacionado, tipo) VALUES (" . $idNC . "," . $folio . ",'NC')";
                            $resp = $this->getAllTableLastID($sqlRelaciones);
                        }
                    }
    
                    if (!empty($dataConceptos)) {
                        foreach ($dataConceptos as $valueConcepto) {
                            $odv = $valueConcepto['odv'];
                            $codigo = $valueConcepto['Codigo'];
                            $claveProdServ = $valueConcepto['ClaveProdServ'];
                            $descripcion = $valueConcepto['Descripcion'];
                            $cantidad = $valueConcepto['Cantidad'];
                            $unidad = $valueConcepto['Unidad'];
                            $precio = $valueConcepto['Precio'];
                            $totalC = $valueConcepto['Total'];
                            // $impuestos=$valueConcepto['Impuestos'];
                            //Falta Folio, Clave Unidad
                            $sqlPartes = "INSERT INTO facturas_odv (id_factura, id_odv, codigo, clave_prodserv, descripcion, clave_unidad, cantidad, precio) 
                            VALUES($folio, '$odv',  '$codigo', '$claveProdServ', '$descripcion', '$unidad', $cantidad, $precio)";
                            $resp = $this->getAllTableLastID($sqlPartes);
                        }
                    }
                    if ($resp) {
                        $success = true;
                        $msj = 'Factura agregado correctamente';
                        $data = $resp['last_id'];
                    } else {
    
                        // $success = false;
                        $msj = 'Error al generar ajuste';
                        // $data = "Error";
                    }
                }
    
            }
    
      
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getTabla()
    {
        $success = false;
        $data = "Error";
        // $query= 'SELECT * FROM '.$tabla;
        $query = '	SELECT f.id, u.`User` AS agente, c.RazonSocial AS cliente, c.Id as id_cliente, m.Nombre AS moneda, 
        f.cuenta_pago, f.orden, f.email, f.total, f.fecha, f.timbrado, f.cancelado, f.folio_seguimiento, c.CorreoElectronico, f.enviado, f.status, c.dias_credito
        FROM facturas f
        JOIN user_accounts u
        ON u.id = f.id_usuario
        LEFT JOIN clientes c ON f.id_cliente = c.id
        LEFT JOIN moneda m ON m.Id = f.moneda
        WHERE f.nota_credito = 1
        ORDER BY id DESC';
        $resp = $this->getAllTable($query);
        $fechaActual = new DateTime();
        $data = [];
        while ($datosBD = $resp->fetch_assoc()) {
            $datosBD['id'] = (int)$datosBD['id'];
            $idFactura = $datosBD['id'];
            //Buscamos las ODV ya relacionadas con alguna factura
            $slqFacRelacionadas = "	SELECT pf.id_factura, pf.id_odv AS odv
            FROM facturas_odv pf
            JOIN facturas f
            ON f.id = pf.id_factura
            WHERE pf.id_factura = $idFactura
            AND f.nota_credito = 1
            ORDER BY pf.id DESC
            LIMIT 1";
            $respFR = $this->getAllTable($slqFacRelacionadas);
            $datosFR = $respFR->fetch_all(MYSQLI_ASSOC);
            $odvRela='';
            foreach($datosFR as $valueRF){
                $odvRela.=$valueRF['odv'].",";
            }
            $odvRela=rtrim($odvRela, ",");
            $idCliente = ' ';
            $cliente = ' ';
            $fechaVencimientoFormatted = ' ';
            $cliente = $datosBD['cliente'];
            $sqlPagos = "SELECT pf.id, pf.id_pago
            FROM pagos_facturas pf
            JOIN pagos p
            ON p.id = pf.id_pago
            WHERE pf.id_factura = $idFactura
            AND p.status = 1 
            AND p.timbrado = 1 
            AND p.cancelado = 0";
            $respPagos = $this->getAllTable($sqlPagos);
            $datosPagos = $respPagos->fetch_all(MYSQLI_ASSOC);
            $folioPago='';
            foreach($datosPagos as $valuePagos){
                $folioPago.='CP-' . $idCliente . '-' . $valuePagos['id_pago'].",";
            }
            // print_r($respCliente);

                $idCliente = $datosBD['id_cliente'];
                $diasCredito = $datosBD['dias_credito'];
                $fechaVencimiento = new DateTime($datosBD['fecha']);
                $fechaVencimiento->modify("+$diasCredito days");
                $fechaVencimientoFormatted = $fechaVencimiento->format('d/m/Y');
                $sqlPago = "SELECT SUM(pf.monto) AS total
					FROM pagos_facturas pf
					JOIN pagos p
					ON p.id = pf.id_pago
					WHERE pf.id_factura = $idFactura AND p.status = 1 AND p.timbrado = 1 AND p.cancelado = 0";
                    // print_r($sqlPago);
                $respPago = $this->getRow($sqlPago);
                $pagos=0;
                if(!empty($respPago['total'])){
                    $pagos = $respPago['total'];

                }
                if ($fechaVencimiento > $fechaActual) {
                    if ($pagos != $datosBD['total']) {
                        $statusPago = 'PENDIENTE';
                    } else {
                        $statusPago = 'PAGADA';
                    }
                } elseif ($fechaVencimiento < $fechaActual) {
                    if ($pagos != $datosBD['total']) {
                        $statusPago = 'VENCIDA';
                    } else {
                        $statusPago = 'PAGADA';
                    }
                }
               
            if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 0) {
                $estilo = '';
                $status = 'PENDIENTE';
            }
            if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 1) {
                $estilo = '#FF9191';
                $status = 'CANCELADA';
            }
            if ($datosBD['timbrado'] == 1 && $datosBD['cancelado'] == 0) {
                $estilo = '#90EE90';
                $status = 'TIMBRADA';
            }
            if ($datosBD['timbrado'] == 1 && $datosBD['cancelado'] == 1) { // Es enviada en vez de cancelada 
                $estilo = '#B5DCE8';
                $status = 'ENVIADA';
            }
            // print_r($datosBD);
            $datosBD["acciones"] = "
                <div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                            <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["id"] . "' >Editar</a>

                            
                        </div>
                </div>";

            $arreglo = array(
                'folioPago' => $folioPago,
                'id' => $datosBD['id'],
                'nameUSer' => $datosBD['agente'],
                'importe' => $datosBD['total'],
                'factura_Relacionadas' => $idFactura,
                // 'razon_social' => $datosBD['RazonSocial'],
                'moneda' => $datosBD['moneda'],
                // 'financiera' => $financiera,
                'status' => $status,
                'estilo' => $estilo,
                'cliente' => $cliente,
                // 'formaPago' => $formaPago,
                // 'cuenta_pago' => $datosBD['cuenta_pago'],
                // 'monto' => (float)$datosBD['monto'],
                'fechaFactura' => $datosBD['fecha'],
                'fechaVencimiento' => $fechaVencimientoFormatted,
                // 'fecha_pago' => $datosBD['fecha_pago'],
                'timbrado' => $datosBD['timbrado'],
                // 'enviado' => $datosBD['enviado'],
                'cancelado' => $datosBD['cancelado'],
                'statusPago' => $statusPago,
                'acciones' => $datosBD['acciones']
            );
            $data[] = $arreglo;
            
         
        }
        if ($resp == true) {
            $success = true;
            $msj = 'Exito en consulta de datos';
            $data = $data;
        } else {
            $msj = 'Error al consultar la tabla';
        }

        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function getOdv($id)
    {

        $query = "SELECT DISTINCT(o.Id), DATE(o.Fecha) AS fecha,o.Total, cli.RFC, vp.IvaPorcentual, cli.Id AS folio FROM odv o
        INNER JOIN clientes cli ON o.Id_cliente = cli.Id
        LEFT JOIN venta_producto vp ON vp.Id_odv = o.Id
        WHERE o.Status=1
        AND o.Id =$id;";
        // print_r($query);

        $resp = $this->getRow($query);
        // print_r($resp);
        if (!empty($resp)) {
            $sql = "SELECT cantidad, Id_producto FROM venta_producto vp LEFT JOIN odv o ON vp.Id_odv=o.Id WHERE o.Status=1 AND o.Id=$id";
            $respOdv = $this->getAllTable($sql);
            $cantidad = 0;
            $productos = '';
            while ($datosOdv = $respOdv->fetch_assoc()) {
                $cantidad += $datosOdv['cantidad'];
                $productos .= $datosOdv['Id_producto'] . ",";
            }


            $claveProdServ = '81141601';
            $descripcion = 'SERVICIO';
            $unidad = rtrim($productos, ",");
            $codigo = 'SLTR - SERVICIO';
            $precio = $resp['Total'];
            $folio = $resp['folio'];
            switch ($resp['IvaPorcentual']) {
                case 0:
                    $impuestos = ".00";
                    break;
                case 1:
                    $impuestos = ".08";
                    break;
                case 2:
                    $impuestos = ".16";
                    break;

                default:
                    $impuestos = ".12";
                    break;
            }


            // $data[] = $resp;
            $array[] = array(
                'Codigo' => $codigo,
                'ClaveProdServ' => $claveProdServ,
                'Descripcion' => $descripcion,
                'Cantidad' =>  $cantidad,
                'Unidad' => $unidad,
                'Precio' => $precio,
                'Impuestos' => $impuestos,
                'Folio' => $folio,

            );

            $data = $array;
            $msj = 'Exito en consulta de datos';
            $success = true;
        } else {
            $data = 'Error';
            $msj = "Datos Vacios";
            $success = false;
        }


        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
}
