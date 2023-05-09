<?php
include_once 'model/api.modelo.php';
if($_SESSION['Moneda']=='Pesos'){
    $Moneda = 1;
}else{
    $Moneda = 1/$_SESSION['TC'];
}

// NOTA CAMBIO ALEXANDER PDF TIMBRADO
require '../requerimientos/vendorComposer/vendor/autoload.php';

use JetBrains\PhpStorm\Internal\ReturnTypeContract;
use Luecano\NumeroALetras\NumeroALetras;


class ControladorOdv extends apiModel{

    function dataAjustesTabla($tabla){
        $success=false;
        $data="Error";
        if(!empty($tabla)){

            $query='SELECT a.Id, a.Fecha, a.Observaciones, a.Subtotal, a.Iva, a.Total, a.TipoCambio, cli.Nombre, mon.Nombre AS Moneda, a.Status
            FROM '.$tabla.' a
            INNER JOIN clientes cli ON cli.Id = a.Id_cliente
            JOIN moneda mon ON mon.Id = a.Moneda';
            $resp= $this->getAllTable($query);
            $data=[];
            while ($datosBD = $resp->fetch_assoc()) {



                if($datosBD['Status']==0){
                    $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";

                    $datosBD['Status']="Cancelado";
                }
    
                // CAMBIO ALEXANDER PEDIDOS CERRAR
                elseif($datosBD['Status']==2){

                    $btns=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a> ";
                    $datosBD['Status']="Cerrado";

                }
                else{
                    if($_SESSION['Rol']['notasVU']==1){
                        // Agregar BTN de Generar PDF 
                        $btns="
                        <a type='button'  class='dropdown-item btnPDFDownload'   id='pd" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar PDF</a>
                        <a type='button'  class='dropdown-item btnPDFView'   id='pw" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver PDF</a>
                            ";
                        

                        // Se le Agrego un Punto ------------ /Cambio Angel se le agrego el boton de cerrar
                        $btns.=" <a type='button'  class='dropdown-item btnView'   id='ed" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>
                        <a type='button'  class='dropdown-item btnCerrarTabla'   id='ce" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Cerrar</a>
                        <a type='button'  class='dropdown-item btnCancelarTabla'   id='el" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Cancelar</a> ";

                    

                    }else{

                    $btns="
                    <a type='button'  class='dropdown-item btnPDFDownload'   id='pd" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar PDF</a>
                    <a type='button'  class='dropdown-item btnPDFView'   id='pw" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver PDF</a>
                    ";

                    // Agregar BTN de Generar PDF 
                    $btns.=" <a type='button'  class='dropdown-item btnPDF'   id='pd" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar PDF</a> ";

                    $btns.=" <a type='button'  class='dropdown-item btnGenerarFactura'   id='gn" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Generar Factura</a> ";
                    
                    }

                     $datosBD['Status']="Habilitado";
                }


                   $datosBD["acciones"] ="
                    <div class='btn-group'>
                            <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                            <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                                ".$btns."
                            </div>
                    </div>";

                $datosBD['Iva']=$datosBD['Iva']*$GLOBALS["Moneda"];
                $datosBD['Subtotal']=$datosBD['Subtotal']*$GLOBALS["Moneda"];
                $datosBD['Total']=$datosBD['Total']*$GLOBALS["Moneda"];
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
    function dataOdv($tabla,$status){
        $success=false;
        $data="Error";
        if(!empty($tabla)){
            $query= 'SELECT Id, Nombre FROM '.$tabla.' WHERE Status='.$status;
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
    function next_ID($tabla){
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
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

        return $mensaje;

    }
    function dataOdvId($tabla,$Id){
        $success=false;
        $data="Error";
        $items=null;
        if(!empty($tabla)){
            $query= 'SELECT * FROM '.$tabla.'
            WHERE Id = '.$Id;

            $resp= $this->getAllTable($query);
            $data=[];
            $data=$resp->fetch_assoc();

            $sqlItems = 'SELECT vp.*,inv.Total AS TotalMaterial, inv.PrecioLitros  FROM venta_producto vp
            JOIN inventario inv ON vp.Id_producto = inv.Id_producto
            WHERE vp.Id_odv='.$data['Id'];

            $respItems= $this->getAllTable($sqlItems);
            $items= [];
            while($itemsDB = $respItems->fetch_assoc()){
                $items[]=$itemsDB;
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
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj,"items"=>$items);

        return $mensaje;

    }
    function createOdv($dataOdv,$tabla,$dataItems){
        $success=false;
        $data="Error";
        if(is_array($dataOdv) && isset($tabla) && is_array($dataItems)){
                $flagProductos =0;
                foreach($dataItems as $values){
                    $respCantidad = 'SELECT Total FROM inventario WHERE Id_producto =' . $values['IdProducto'];
                    $cProducto= ($this->getAllTable($respCantidad))->fetch_assoc();
                    $cantidad= (int)$values['Cantidad'];
                    $tProductos = $cProducto['Total'] - $cantidad;
                    if($tProductos < 0){
                        $flagProductos =1;
                    }
                }
                if($flagProductos==0){
                $Cliente = $dataOdv['Cliente'];
                $Observaciones = $dataOdv['Observaciones'];
                $SubTotal = $dataOdv['SubTotal'];
                $Iva = (float)$dataOdv['Iva'];
                $Total =(float) $dataOdv['Total'];
                $TpCambio =(float) $dataOdv['TpCambio'];
                $Moneda = $dataOdv['Moneda'];
                if($Moneda==2){
                    $SubTotal = $SubTotal*$TpCambio;
                    $Iva = $Iva*$TpCambio;
                    $Total = $Total*$TpCambio;

                }
                $query= 'INSERT INTO '.$tabla.' (Id_cliente,Observaciones, Subtotal, Iva, Total, Moneda,TipoCambio,Status)
                VALUES ('.$Cliente.',"'.$Observaciones.'",'.$SubTotal.','.$Iva.','.$Total.','.$Moneda.','.$TpCambio.',1)';
                $resp = $this->getAllTableLastID($query);
                if($resp['success']==1){
                    $IdOdv=$resp['last_id'];
                    foreach($dataItems as $valueItem){
                        $IdProducto = $valueItem['IdProducto'];
                        $IdProductoComo = $valueItem['IdProductoComo'];
                        $Cantidad = $valueItem['Cantidad'];
                        $Precio = $valueItem['Precio'];
                        $Iva = $valueItem['Iva'];
                        $IvaPorcentual = $valueItem['IvaPorcentual'];
                        $SubTotal = $valueItem['Subtotal'];
                        $Total = $valueItem['Total'];
                        $query= 'INSERT INTO venta_producto (Id_odv, Id_producto,VendidoComo, Cantidad, Precio_Litro, Subtotal, IvaPorcentual ,Iva ,Total,Status)
                        VALUES ('.$IdOdv.','.$IdProducto.','.$IdProductoComo.','.$Cantidad.','.$Precio.','.$SubTotal.','.$IvaPorcentual.','.$Iva.','.$Total.',1)';
                        $resp= $this->getAllTable($query);
                        if($resp==1){
                            $success=true;
                            $msj='Se agrego correctamente la Odv: '.$IdOdv;
                            $data=$IdOdv;
                        }else{
                            $msj='Error al agregar venta_producto';

                        }
                    }
                }else{
                    $msj='Error al agregar Odv';

                }
            }else{
                $msj = "Productos insuficientes";
            }

        }else{
            $msj = "No se recibieron datos";

        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

        return $mensaje;
    }

    public function updateInventario($idProducto,$total)
    {
        if(isset($idProducto) && isset($total)){
            $sql = 'UPDATE inventario SET Total = '.$total.' WHERE Id_producto = '.$idProducto;
            $this->updateTable($sql);
        }
    }
    function putOdv($dataProducto,$tabla,$id,$dataItems){
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

                $idItems=$valueItems['IdItem'];
                $IdProducto=$valueItems['IdProducto'];
                $IdProductoComo=$valueItems['IdProductoComo'];
                $Cantidad = $valueItems['Cantidad'];
                $Precio = $valueItems['Precio'];
                $Subtotal = $valueItems['Subtotal'];
                $IvaPorcentual = $valueItems['IvaPorcentual'];
                $Iva = $valueItems['Iva'];
                $Total = $valueItems['Total'];
                if($idItems==NULL){
                    $sqlItems='venta_producto (Id_odv, Id_producto, VendidoComo, Cantidad, Precio_Litro, Subtotal, IvaPorcentual, Iva, Total)
                    VALUES ('.$id.','.$IdProducto.','.$IdProductoComo.',"'.$Cantidad.'",'.$Precio.','.$Subtotal.','.$IvaPorcentual.','.$Iva.','.$Total.')';
                    $resultInsert = $this->setTableGrl($sqlItems);
                    if($resultInsert ==1){
                        $actualizacion=1;
                    }
                }else{
                    $queryItems = "venta_producto
                                  SET Id_odv= $id, Id_producto=$IdProducto, VendidoComo= '$IdProductoComo', Cantidad= '$Cantidad',
                                  Precio_Litro= '$Precio', Subtotal= '$Subtotal', IvaPorcentual= '$IvaPorcentual', Iva= '$Iva', Total= '$Total'
                                  WHERE Id =$idItems";
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
    function putOdvStatus($tabla,$id,$status){
        $success=false;
        $data="Error";
        if(isset($id)&& isset($tabla)&& isset($status)){

            $query = "UPDATE ".$tabla." SET Status = $status WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp==true) {
                $success=true;
                $msj='Cambio de estatus en orden de venta correctamente';
                $data="Correcto";
            }else{
                $msj='Error al cambiar estatus de : '.$tabla;
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

        return $mensaje;
    }
    function cerrarOdv($id,$status){
        $success=false;
        $data="Error";
        if(isset($id)&& isset($status)){
            $fecha_actual = date("Y-m-d");
            $query = "UPDATE odv SET Status = $status,FechaCerrado='$fecha_actual' WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp==true) {
                $success=true;
                $msj='Orden de venta cerrada correctamente';
                $data="Correcto";
            }else{
                $msj='Error al cerrar orden de venta';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);

        return $mensaje;
    }

    function dataExcelOdv($tabla)
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
                $newRow['Moneda'] = $row['Moneda'];
                $newRow['Subtotal'] = $row['Subtotal'];
                $newRow['Iva'] = $row['Iva'];
                $newRow['Total'] = $row['Total'];
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

        $timestamp = strtotime($date);
        return date('d/m/Y', $timestamp);
        // echo $new_date;  // Imprime "16/12/2022"
    }


    function generarDataPDFOdv($tabla,$id){


        // return $this->getDataFactura(27);

        $arrayData=$this->dataOdvId($tabla,$id);

        $query = "SELECT C.*,U.name FROM clientes C LEFT JOIN user_accounts U ON C.Ejecutiva = U.Id WHERE C.Id=".$arrayData['data']['Id_cliente'];
        $resp= $this->getAllTable($query);
        $dataCliente=$resp->fetch_assoc();


        if($arrayData['success']){

            $data=$arrayData['data'];
            $items=$arrayData['items'];

            $arrayHeader=[
                'invoice'=>$data['Id'],
                'bill'=>'',
                'creator'=>'NO almacenado',
                'oc'=>'NO Almacenado',
                'tc'=>floatval($data['TipoCambio']),
                'date'=>($this->formatoFechaVista(explode(' ',$data['Fecha'])[0] )),
            ];


    
            $arrayCompany=[
                'name' => '5INCO TRADING',
                'direction' => 'PROLONGACIÓN ELOTE #90, LAS HUERTAS 1RA SECCIÓN
                TIJUANA, BAJA CALIFORNIA, CP. 22116',
                'cell' => '664 396 0114',
                'phone' => '689 0345'
            ];

            $arrayCliente=[
                'name'=>$dataCliente['Nombre'],
                'direction'=>$dataCliente['CalleCliente']. ' Ext: '.$dataCliente['Ext'].' Int: NoAlmacenado'.' C.P:'.$dataCliente['CPCliente'],
                'rfc'=>$dataCliente['RFC'],
                "dias_credito"=>$dataCliente['dias_credito']==0?"Contado":"Credito"
            ];
            // 20-04-23



            $arrayProductos=[];

            foreach($items as $producto){
                $dataProducto= $this->getDataProducto($producto['Id_producto']);

                $arrayProductos[]=[
                    'name' =>$dataProducto['Nombre'],
                    'lot' => floatval($producto['Cantidad']),

                    'um' => $dataProducto['um'],
                    'unitPrice' => floatval($producto['Precio_Litro']),
                    'amount' => floatval($producto['Total'])
                ];
            };


            $arrayBalance=[
                'total'=>floatval($data['Total']),
                'subtotal'=>floatval($data['Subtotal']),
                'iva'=>floatval($data['Iva']),
                "ivas"=>[
                    [
                        [
                            "iva"=>"8%",
                            "cantidad"=>2000,
                        ],
                        [
                            "iva"=>"16%",
                            "cantidad"=>2000,
                        ]

                    ]
                ]
            ];


            $arrayHeader['company']=$arrayCompany;
            $arrayHeader['client']=$arrayCliente;

            $arrayHeader['products']=$arrayProductos;

            $arrayHeader['balance']=$arrayBalance;

            $arrayHeader['namePDF']='OrdenDePedito-'.date('d-m-Y', time());

            return array("success" => true,"data"=>$arrayHeader, "message" =>'Consulta Exitosamente',"items"=>[]);


        }else{

            return array("success" => false,"data"=>[], "message" =>'Error al General PDF',"items"=>[]);
        }



    }

    
    function getDataProducto($idProducto){

        $query="SELECT p.Nombre, t.Nombre AS um FROM productos p JOIN tipounidad t ON t.Id=p.tipoUnidad AND p.Id=".$idProducto;
        $resp= $this->getAllTable($query);

        $response=$resp->fetch_assoc();

        return $response;
    }



    // ---------------------------- 

    function getDataFactura($idFactura){


 
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

            // NOTA VERICAR CLAVE UNIDAD (NOSE ENCUENTRA LA TABLA)
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

    // CAMBIOS ALEXANDER EVIDENCIAS NUEVAS FUNCIONES

    function altaFiles($idPedido, $tabla, $archivos, $user)
    {
        $success = false;
        $data = "Error";

        if (isset($tabla) && isset($archivos) && isset($idPedido) && isset($user)) {

            $respId = $this->nextID($tabla);



            if ($respId['success'] == false) {
                $msj = "Error al buscar Id en el controlador";
            } else {

                $lastId = $respId["Auto_increment"];

                foreach ($_FILES as $key => $value) {

                    $cantArchivos = count($value["name"]);

                    if ($cantArchivos >= 11) {
                        $msj = "Limite de archivos recibidos";
                    } else {

                        foreach ($value["name"] as $key2 => $name) {

                            $ext = pathinfo($name, PATHINFO_EXTENSION);
                            $archivo = $idPedido . '_' . $lastId . '_evidencia.' . $ext;

                            $carpetaSubir = '../Data/EvidenciasPedidos/' . basename($archivo);

                            $tmstAltaRegistro = date("Y-m-d H:i:s");
                            $epochAltaEv = time() * 1000;
                            
                            $sqlArchivos = $tabla . ' (Id, Tipo, NombreArchivo, Extension,tmstAltaRegistro, epochRegistro,Usuario) 
                            VALUES (' . $idPedido . ',"' . $value["type"][$key2] . '","' . $archivo . '","' . $ext . '","' . $tmstAltaRegistro . '","' . $epochAltaEv . '","' . $user . '")';

                            $resultInsert = $this->setTableGrl($sqlArchivos);

                            if ($resultInsert == 1) {

                                if (move_uploaded_file($value["tmp_name"][$key2], $carpetaSubir)) {
                                    $success = true;
                                    $msj = 'El Archivo se subio Correctamente';
                                    $data = "Correcto";
                                } else {
                                    $msj = "No se pudo subir" . $name;
                                }

                            } else {
                                $msj = "Error al dar de alta el producto";
                            }


                            $lastId++;
                        }
                    }
                }
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }



    function countEvidencias($Id)
    { //Ok
        $data = "Error";

        $tabla = 'archivospedidos';


        $query = 'SELECT * FROM archivospedidos WHERE Id=' . $Id;
        $resp = $this->getAllTable($query);

        $data = [];

        while ($dataDB = $resp->fetch_assoc()) {

            $urlEvidencias = explode('api', __DIR__)[0];
            $dataDB['url'] = $urlEvidencias;

            $data[] = $dataDB;
        }


        if ($resp == true) {

            if (empty($data)) {
                $data = ' ';
            }

            $success = true;
            $msj = 'Exito en consulta de datos';
            $data = $data;
        } else {
            $success = false;

            $msj = 'Error al consultar la tabla: ' . $tabla;
        }

        return array("success" => $success,"data"=>$data, "message" =>$msj,"items"=>[]);

    }


    function contador($tabla,$id,$cantidad){
        $success=false;
        $data="Error";
        if(isset($tabla) && isset($id) && isset($cantidad)){
            $sql = "SELECT COUNT(Id) AS Contador FROM $tabla WHERE Id=".$id;
            $result = $this->getAllTable($sql);
    
            $contador = $result->fetch_all(MYSQLI_ASSOC)[0]['Contador'];
            $total=$cantidad+$contador;
            if ($total<=10) {
                $success=true;
                $msj='Exito en consulta de datos';
                $data=$cantidad;
            }else{
                $msj='El limite de archivos por producto supera los 10 ';
            }
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;
    
    }
    function getStatusClientes($id){
        $success=false;
        $data="Error";
        $query = "SELECT DISTINCT(Nombre) AS cliente, Id, dias_credito
        FROM clientes
        WHERE Id = $id";
        $resp = $this->getAllTable($query);
        $datosClientes = $resp->fetch_all(MYSQLI_ASSOC);
        // $arrayDatos=[];
        foreach($datosClientes as $valueCli){
            $idCliente=$valueCli['Id'];
            $nameCliente=$valueCli['cliente'];
            $diasCredito=$valueCli['dias_credito'];
            $sql = "SELECT f.id,
            f.total AS totalFactura, DATE(f.fecha) AS fechaFactura, f.moneda
            FROM facturas f 
            WHERE f.timbrado =1 
            AND f.cancelado =0 
            AND f.nota_credito = 0
            AND f.id_cliente= $idCliente";
            // print_r($sql);
            $respuestaFac = $this->getAllTable($sql);
            $datosFac = $respuestaFac->fetch_all(MYSQLI_ASSOC);
            if(!empty($datosFac)){
                
                $creditoPesos=0;
                $creditoDolares=0;
                $vencidoPesos=0;
                $vencidoDolares=0;
                foreach($datosFac as $valueFac){
                    $totalPesos=0;
                    $totalDolar=0;
                    $idFactura=$valueFac['id'];
                    if($valueFac['moneda']==1){
                        $totalPesos=floatval($valueFac['totalFactura']);
                    }else{
                        $totalDolar=floatval($valueFac['totalFactura']);
                    }
                    $sqlCP="SELECT pf.monto AS monto, p.moneda
                    FROM pagos_facturas pf
                    JOIN pagos p
                    ON p.id = pf.id_pago
                    WHERE pf.id_factura = $idFactura
                    AND p.timbrado = 1
                    AND p.cancelado = 0";
                    $respuestaCP = $this->getAllTable($sqlCP);
                    $datosCP = $respuestaCP->fetch_all(MYSQLI_ASSOC);
                    $montoPesos=0;
                    $montoDolar=0;
                    $saldoPesos=$totalPesos;
                    $saldoDolares=$totalDolar;
                    if(!empty($datosCP)){
                        
                        foreach($datosCP as $valueCP){
                            if($valueCP['moneda']==1){
                                $montoPesos+=floatval($valueCP['monto']);
                            }else{
                                $montoDolar+=floatval($valueCP['monto']);
                            }
                            
                        }
                        $saldoPesos=$totalPesos-$montoPesos;
                        $saldoDolares=$totalDolar-$montoDolar;
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
                    }else {
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
                }
                $vencidoPesos=0;
                if($vencidoPesos>0 || $vencidoDolares>0){
                    $success=false;
                    $msj='El cliente tiene saldo vencido';
                    $data='Error';
                }else{
                    $success=true;
                    $msj='El cliente no tiene saldo vencido';
                    $data='Éxito';
                }

            }

        }


        $mensaje= array("success" => $success,"data"=>$data, "message" => $msj);
    
        return $mensaje;

    }
}
?>