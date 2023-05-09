<?php

include_once 'model/api.modelo.php';

if($_SESSION['Moneda']=='Pesos'){

    $Moneda = 1;

}else{

    $Moneda = 1/$_SESSION['TC'];

}



class ControladorCP extends apiModel{


    function autoCompleteFactura($string)
    {

        $json = [];

        $data = [];

        // NOTA: CAMBIAR LLAMADA DE LA TABLA, ESTA ES UNA PRUBA 
        // $query = "SELECT * From clientes WHERE Ext LIKE '%" . $string . "%' ";

        $query = "SELECT f.id, f.total, cli.Nombre, cli.RazonSocial, f.moneda FROM facturas f 
        LEFT JOIN clientes cli ON f.id_cliente = cli.Id WHERE Id LIKE '%" . (int)$string . "%';";

        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {
                if($datos['moneda']==1){
                    $moneda="MXN";
                }else{
                    $moneda="USD";
                }

                $data[] = ['value' => $datos['id'], 'precio' => $datos['total'], 'data' => "[".$datos['id']."] ".$datos['RazonSocial']." - $".number_format($datos['total'], 2, '.', ',').$moneda];
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
        // $query = "SELECT * FROM facturas WHERE Id LIKE '%" . (int)$string . "%';";
        $query = "SELECT f.*, cli.Nombre AS nombreCliente  
        FROM facturas f 
        LEFT JOIN clientes cli ON cli.Id = f.id_cliente 
        WHERE f.id LIKE '%" . (int)$string . "%' 
        AND f.status = 1 
        AND f.timbrado = 1 
        AND f.cancelado = 0 LIMIT 10;";
        // print_r($query);
        $resp = $this->getAllTable($query);

        if ($resp) {

            while ($datos = $resp->fetch_assoc()) {
                $idFactura=$datos['id'];
                $cliente =$datos['nombreCliente'];
                //verificamos el monto de la factura
                $slqPago = "SELECT SUM(monto) AS pagos
                FROM pagos_facturas pf
                JOIN pagos p
                ON p.id = pf.id_pago
                WHERE pf.id_factura = '$idFactura' AND cancelado = 0";
        
                $respPago = $this->getRow($slqPago);
                $montoPago=0;
                $total=$datos['total'];
                if(!empty($respPago['pagos'])){
                    $montoPago=$respPago['pagos'];
                }
                // print_r("Total sin complemento".$total);
                // print_r("\n");
                $total=$total-$montoPago;
                $montoNC=0;
                //Verificamos si tiene nota de credito la factura 
                $sqlNc="SELECT SUM(f.total) AS totalNC FROM facturas f
                JOIN facturas_relaciones fr ON f.id = fr.id_factura
                WHERE fr.tipo = 'NC'
                AND f.nota_credito = 1
                AND f.cancelado=0
                AND fr.cfdi_relacionado=$idFactura";
                $respNC = $this->getRow($sqlNc);
                if(!empty($respNC['totalNC'])){
                    $montoNC=$respNC['totalNC'];
                }
                // print_r("Total con complemento".$total);
                // print_r("\n");
                $total =$total-$montoNC;
                // print_r("Total con NC".$total);
                // print_r("\n");
                if($total>0){
                    $data[] = ['value' => $datos['id'], 'data' => $datos['id']];
                    // $data[] = ['value' => $datos['id']." - ".$cliente, 'data' => $datos['id']." - ".$cliente];

                }
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
        $idFactura=$resp['id'];
        $totalPago=$resp['total'];
        ///Se buscan los pagos de la factura
        $slqPago = "SELECT SUM(monto) AS pagos
        FROM pagos_facturas pf
        JOIN pagos p
        ON p.id = pf.id_pago
        WHERE pf.id_factura = '$idFactura' AND cancelado = 0";

        $respPago = $this->getRow($slqPago);
        $montoPago=0;
        if(!empty($respPago['pagos'])){
            $montoPago=$respPago['pagos'];
        }
        $saldo=(float)$totalPago- (float)$montoPago;
        // $saldo= ((float)$montoPago-(float)$totalPago)*-1;


        $fecha=date('d/m/Y', strtotime($resp['fecha']));
        $slqPar = "					SELECT COUNT(pf.id)+1 AS cantidad
        FROM pagos_facturas pf
        JOIN pagos p
        ON p.id = pf.id_pago
        WHERE pf.id_factura = $idFactura AND p.cancelado = 0";
        $resPar = $this->getRow($slqPar);
        $noParcialida=$resPar['cantidad'];
        if (!empty($resp)) {
            if($resp['moneda']==1){
                $moneda = 'MXN';
            }else{
                $moneda='USD';
            }
            $saldo = number_format($saldo, 2);

            // $data[] = $resp;
            $array[] = array(
                'Folio' => $resp['id'],
                'Fecha' => $fecha,
                'Total' => $totalPago,
                'Saldo' =>  (float)$saldo,
                'Moneda' => $moneda,
                'NoParcialidad' => $noParcialida,
                'MontoPago' => (float)$saldo
              
            );

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
    function createCp($data){
        if(is_array($data)){
            // print_r($data);
            $flag=0;
            $datacomplementosPago=$data['complementosPago'];
            $datainfo=$data['info'];
            // print_r($datainfo);
            if(!empty($datainfo)){
                $fechaPago=$datainfo['fechaPago'];
                // $financiera=$datainfo['financiera'];
                $formaDePago=$datainfo['formaDePago']; 
                // $horaPago=$datainfo['horaPago'];
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
                $total=$datainfo['totalBalance']; 
                $now = new DateTime();
                $now = $now->format('Y-m-d H:i:s');
                date_default_timezone_set('UTC');
                $fechaHoraObj = DateTime::createFromFormat('m/d/Y H:i', $fechaPago);
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
                    $montoPag=number_format((float)$valueCp['MontoPag'], 2, '.', '');
                    $noParcialida=$valueCp['NoParcialida'];
                    $saldo=number_format((float)$valueCp['Saldo'], 2, '.', '');
                    $totalCp=number_format((float)$valueCp['Total'], 2, '.', '');
                    // $descuento = number_format((float)$cfdiComprobante['Descuento'], 2, '.', '');

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
    function getTabla(){
        $success=false;
        $data="Error";
            // $query= 'SELECT * FROM '.$tabla;
            $query= 'SELECT f.id, u.User AS agente, f.moneda, f.total AS monto, f.forma_pago, f.fecha_pago, f.fecha_creacion AS fecha, f.timbrado, f.cancelado, f.fecha_pago, m.Nombre AS moneda
            FROM pagos f
            JOIN user_accounts u
            ON u.id = f.id_agente
            JOIN moneda m 
            ON m.Id= f.moneda
            ORDER BY id DESC';
            $resp= $this->getAllTable($query);
            
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {
                $datosBD['id']=(int)$datosBD['id'];
                $idPago=$datosBD['id'];
                $sqlCliente="	SELECT c.Id, c.RazonSocial
                FROM pagos_facturas pf
                JOIN facturas f
                ON f.id = pf.id_factura
                JOIN clientes c
                ON c.id = f.id_cliente
                WHERE pf.id_pago = $idPago
                ORDER BY pf.id DESC
                LIMIT 1";
                $respCliente= $this->getRow($sqlCliente);
                $idCliente=' ';
                $cliente=' ';
                if(!empty($respCliente)){
                    $idCliente=$respCliente['Id'];
                    $cliente=$respCliente['RazonSocial'];
                }
                $sqlPro="SELECT pr.RazonSocial
                FROM pagos pa
                JOIN proveedores pr
                ON pr.id = pa.id_financiera
                WHERE pa.id = $idPago
                LIMIT 1";
                $respPro= $this->getRow($sqlPro);
                $financiera='';
                if(!empty($respPro)){
                    $financiera = $respPro['RazonSocial'];
                }
                if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 0) {
                    $estilo = '';
                    $status = 'PENDIENTE';
                }
                if ($datosBD['timbrado'] == 0 && $datosBD['cancelado'] == 1) {
                    $estilo = '#FF9191';
                    $status = 'CANCELADA';
                }
                if ($datosBD['timbrado'] == 1 && $datosBD['cancelado'] == 0 ) {
                    $estilo = '#90EE90';
                    $status = 'TIMBRADA';
                }
                if ($datosBD['timbrado'] == 1 && $datosBD['cancelado'] == 1) { // Es enviada en vez de cancelada 
                    $estilo = '#B5DCE8';
                    $status = 'ENVIADA';
                }
                $datosBD["acciones"] ="
                <div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                            <a type='button'  class='dropdown-item btnEditarTabla'   id='ed" . $datosBD["id"] . "' >Editar</a>

                            
                        </div>
                </div>";

                $arreglo = array(
                    'folio' => 'CP-' . $idCliente . '-' . $idPago,
                    'id' => $datosBD['id'],
                    'agente' => $datosBD['agente'],
                    // 'razon_social' => $datosBD['RazonSocial'],
                    'moneda' => $datosBD['moneda'],
                    'financiera' => $financiera,
                    'status' => $status,
                    'estilo' => $estilo,
                    'cliente' => $cliente,
                    // 'formaPago' => $formaPago,
                    // 'cuenta_pago' => $datosBD['cuenta_pago'],
                    'monto' => (float)$datosBD['monto'],
                    'fecha' => $datosBD['fecha'],
                    'fecha_pago' => $datosBD['fecha_pago'],
                    'timbrado' => $datosBD['timbrado'],
                    // 'enviado' => $datosBD['enviado'],
                    'cancelado' => $datosBD['cancelado'],
                    'acciones' => $datosBD['acciones']
                );
                $data[] = $arreglo;
             
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

}
