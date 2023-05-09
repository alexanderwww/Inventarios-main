<?php
include_once 'model/api.modelo.php';
if($_SESSION['Moneda']=='Pesos'){
    $Moneda = 1;
    $TC = 1;
}else{
    $Moneda = 1/$_SESSION['TC'];
    $TC=$_SESSION['TC'];
}
class ControladorOdt extends apiModel
{

    function dataOdtTabla($tabla)
    {
        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            // $query = 'SELECT * FROM ' . $tabla;


            $query='SELECT odt.*, pt.Nombre AS nameProducto, us.User AS nameUser FROM odt 
            left JOIN productos pt ON pt.Id= odt.Producto
           left JOIN user_accounts us ON us.Id =  odt.UsuarioAsignado
            ';

            $resp = $this->getAllTable($query);
            $data = [];
            while ($datosBD = $resp->fetch_assoc()) {
                if($_SESSION['Rol']['proveedorU']==1){
                    if($datosBD['Status']==1){
                        // print_r($datosBD);
                        $btnsItems=" <a type='button'  class='dropdown-item btnCancelarTabla'   id='el" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Cancelar</a>
                        <a type='button'  class='dropdown-item btnViewTabla'   id='vi" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>
                        <a type='button'  class='dropdown-item btnCerrarTabla'   id='ce" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Cerrar</a>";
                        }else{
                        $btnsItems=" 
                        <a type='button'  class='dropdown-item btnViewTabla'   id='vi" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>";
                        }
                }else{
                    $btnsItems=" <a type='button'  class='dropdown-item btnViewTabla'   id='vi" . $datosBD["Id"] . "' name='" . $datosBD["Id"] . "' >Ver</a>";
                }
            
                $datosBD["acciones"] = "
                <div class='btn-group'>
                        <button type='button' class='btn btn-secondary btn-sm dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Acción</button>
                        <div class='dropdown-menu-np dropdown-menu dropdown-menu-right'>

                       ".$btnsItems."

                        </div>
                </div>";
                $datosBD['Costo']=$datosBD['Costo']*$GLOBALS["Moneda"];
                $datosBD['Id'] = (int)$datosBD['Id'];
                $data[] = $datosBD;
            }
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function dataOdt($tabla, $status)
    {
        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            // if($status==2){
            //     $query='SELECT pro.Id, pro.Nombre, pro.Densidad, pro.Hazmat, pro.Marca, pro.Concentracion, pro.Uso, inv.Total AS InventarioActual 
            //     FROM '.$tabla.' pro INNER JOIN inventario inv ON pro.Id = inv.Id_producto';
            // }else{
            //     $query='SELECT pro.Id, pro.Nombre, pro.Densidad, pro.Hazmat, pro.Marca, pro.Concentracion, pro.Uso, inv.Total AS InventarioActual 
            //     FROM '.$tabla.' pro INNER JOIN inventario inv ON pro.Id = inv.Id_producto WHERE pro.Formulacion='.$status;

            // }
            // $query = 'SELECT pro.Id, pro.Nombre, pro.Densidad, pro.Hazmat, pro.Marca, pro.Concentracion, pro.Uso, pro.Color , inv.PrecioLitros, pro.Formulacion, inv.Total AS InventarioActual , pro.tipoUnidad
            // FROM ' . $tabla . ' pro INNER JOIN inventario inv ON pro.Id = inv.Id_producto WHERE pro.tipoUnidad =2';
            $query = "SELECT pro.Id, pro.Nombre, pro.Densidad, pro.Hazmat, pro.Marca, pro.Concentracion, pro.Uso, pro.Color , pro.tipoUnidad,tp.Nombre AS nombreUnidad, 
            inv.PrecioLitros, pro.Formulacion, inv.Total AS InventarioActual , pf.`Version`
                        FROM $tabla pro 
                            INNER JOIN inventario inv ON pro.Id = inv.Id_producto 
                            JOIN tipounidad tp ON tp.Id=pro.tipoUnidad 
                            LEFT JOIN productoformulado pf ON pf.IdProducto = pro.Id
                            WHERE pro.tipoUnidad =2
                            GROUP BY (pro.Id)
                        ";
            $resp = $this->getAllTable($query);
            $data = [];
            $items = [];
            while ($datosBD = $resp->fetch_assoc()) {
                if ($datosBD['Version'] == 1) {
                    $items[] = $datosBD;
                }
                if($datosBD['tipoUnidad']==2){
                    $data[] = $datosBD;

                }
            }
            // print_r($data);
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
                $items = $items;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj, "items" => $items);

        return $mensaje;
    }
    function dataOdtId($tabla, $Id)
    {

        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            $query = 'SELECT pro.Id, pro.Nombre, pro.Color, pro.Densidad, pro.Hazmat, pro.Marca, pro.Concentracion, pro.Uso, inv.Total AS InventarioActual 
            FROM ' . $tabla . ' pro INNER JOIN inventario inv ON pro.Id = inv.Id_producto WHERE pro.ID=' . $Id;

            $resp = $this->getAllTable($query);
            $data = [];
            $data = $resp->fetch_assoc();

            $sqlItems = 'SELECT DISTINCT(pf.IdProducto), inv.Nombre, inv.Total, pf.Version FROM productoformulado pf 
            INNER JOIN inventario inv ON pf.IdProducto = inv.Id_producto
            WHERE pf.Grupo=' . $Id;

            $respItems = $this->getAllTable($sqlItems);
            $items = [];
            while ($itemsDB = $respItems->fetch_assoc()) {
                $contenido = [];
                $sqlContenido = 'SELECT  pf.Id,pf.IdContenido as IdProducto, inv.Nombre, inv.Total, pf.Version, pro.tipoUnidad AS IdUni, uni.Nombre AS nombreUnidad, pf.Porcentaje, inv.PrecioLitros FROM productoformulado pf 
                INNER JOIN inventario inv ON pf.IdContenido = inv.Id_producto
                INNER JOIN productos pro ON pro.Id = pf.IdContenido
                INNER JOIN tipounidad uni ON uni.Id = pro.tipoUnidad
                WHERE pf.IdProducto=' . $itemsDB['IdProducto'] . ' AND pf.Version = ' . $itemsDB['Version'];
                // print_r($sqlContenido);
                $respContenido = $this->getAllTable($sqlContenido);
                while ($contenidoDB = $respContenido->fetch_assoc()) {
                    $contenidoDB['Id'] = (int)$contenidoDB['Id'];
                    $contenidoDB['PrecioLitros']=$contenidoDB['PrecioLitros']*$GLOBALS['Moneda'];
                    $contenidoDB['NoPorcentaje'] = $contenidoDB['Porcentaje'];
                    $contenidoDB['Porcentaje'] = '<input onkeyup="sobreinputItems(event); statusPorcentaje(this);" value="' . $contenidoDB['Porcentaje'] . '" autocomplete="off" class="form-control formAltaItemsData validarDataAltaItems stringClass trim porcentajeItems borderRadiusInputPercentaje" type="text" id="porcentaje_' . $contenidoDB['Id'] . '" readonly>';
                    $contenidoDB['litrosBarril'] = '<input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control formAltaItemsData validarDataAltaItems stringClass trim litrosBarrilItems borderRadiusInputLiters" type="text" id="litrosBarril_' . $contenidoDB['Id'] . '" readonly>';
                    $contenidoDB['litrosProducion'] = '<input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control formAltaItemsData validarDataAltaItems stringClass trim litrosProducionItems borderRadiusInputLiters" type="text" id="litrosProducion_' . $contenidoDB['Id'] . '"  readonly>';
                    $contenidoDB['costoLitro'] = '<input onkeyup="sobreinputItems(event);statusImporte(this);" autocomplete="off" class="form-control formAltaItemsData validarDataAltaItems stringClass trim costoLitroItems borderRadiusInputPrice" type="text" value="' .round( $contenidoDB['PrecioLitros'],2) . '" id="costoLitro_' . $contenidoDB['Id'] . '" readonly>';
                    $contenidoDB['importe'] = '<input onkeyup="sobreinputItems(event);" autocomplete="off" class="form-control formAltaItemsData validarDataAltaItems stringClass trim importeItems borderRadiusInputPrice" type="text" id="importe_' . $contenidoDB['Id'] . '"  readonly>';
                    $contenidoDB["eliminar"] = '<button class="btn btn-danger btn-sm rounded-10 btnElimarFila bx bx-trash" type="button" id="eliminar_' . $contenidoDB['Id'] . '" style="font-size: 18px;"></button>';
                    $contenidoDB['agregar'] = '<button class="btn btn-success btn-sm rounded-10 btnAgregarFila bx bx-plus" type="button" id="agregar_' . $contenidoDB['Id'] . '" style="font-size: 18px;"></button>';
                    $contenidoDB['SelectContenido'] = '<select onchange="sobreSelectData(event);insertTotalProductos(this)" attr_key="' . $contenidoDB['Id'] . '" class="form-control formAltaDataItems validarAltaDataItems selectsProductoPrimario form-select" attr_valueDefaul="' . $contenidoDB['IdProducto'] . '" id="select' . $contenidoDB['Id'] . '"></select>';
                    $contenido[] = $contenidoDB;
                }
                $itemsDB['contenido'] = $contenido;
                $items[] = $itemsDB;
            }
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj, "items" => $items);

        return $mensaje;
    }
    function dataOdtIdStatus($tabla, $Id,$subTabla)
    {
        // $Id=2;
        $success = false;
        $data = "Error";
        if (!empty($tabla)) {
            $query = 'SELECT DISTINCT o.Id , o.Usuario , o.TipoCambio, o.CantidadFabricar, o.UsuarioAsignado, o.Costo AS costoFabricacion, o.InventarioActual, o.InventarioDespues, o.`Status`, p.Nombre AS nameProducto, usr.`User` AS nameUsuarioAsignado 
            FROM '.$tabla.' o 
            JOIN '.$subTabla.' op ON op.IdOdt = o.Id 
            JOIN productos p ON o.Producto= p.Id 
            JOIN user_accounts usr ON usr.Id = o.UsuarioAsignado
            WHERE o.Id=' . $Id;
            // print_r($query);
            $resp = $this->getAllTable($query);
            $data = [];
            $data = $resp->fetch_assoc();
            $sqlItems = 'SELECT op.IdOdt, op.IdContenido, i.Nombre AS nombreProducto, op.LitroBarril, op.LitroProduccion, op.Costo, op.Importe, op.`Status`, i.PrecioLitros
            FROM '.$tabla.' o 
            INNER JOIN '.$subTabla.' op ON op.IdOdt = o.Id 
            JOIN inventario i ON op.IdContenido= i.Id_producto 
            WHERE op.IdOdt =' . $Id;

            $respItems = $this->getAllTable($sqlItems);
            $items = [];
            while ($itemsDB = $respItems->fetch_assoc()) {

                $items[] = $itemsDB;
            }
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj, "items" => $items);

        return $mensaje;
    }
    function createOdt($dataOdt, $tabla, $productos, $user)
    {
        $success = false;
        $data = "Error";
        if (is_array($dataOdt) && isset($tabla)) {
            // Validacion de la cantidad de productos que se utilizaran no sea menor a 0
            $flagProductos =0;
            foreach($productos as $values){
                $respCantidad = 'SELECT Total FROM inventario WHERE Id_producto =' . $values['IdProducto'];
                $cProducto= ($this->getAllTable($respCantidad))->fetch_assoc();
                $litrosProduccionPro = (int)$values['litrosProducion'];
                $tProductos = $cProducto['Total'] - $litrosProduccionPro;
                if($tProductos < 0){
                    $flagProductos =1;
                }
            }
            if($flagProductos==0){
            $status = $dataOdt['status'];
            // $status = 1;
            $Producto = $dataOdt['Producto'];
            $CantidadFabricar = $dataOdt['CantidadFabricar'];
            if ($status == 1) { //Cuando el estatus viene en 1 significa que se creara una nueva version
                $newPro = $dataOdt['info'];
                $IdGrupo = $newPro['Id'];
                $sqlVersion = 'SELECT MAX(VERSION) AS Version FROM productoformulado WHERE Grupo=' . $IdGrupo;
                $respVersion = $this->getAllTable($sqlVersion);
                $last_Version = $respVersion->fetch_assoc();
                $sqlPro="SELECT pro.Color, pro.Hazmat, pro.Uso, pro.CAS, pro.UN, pro.Flameabilidad, pro.Reactividad, pro.Toxicidad, pro.Corrosividad 
                FROM productos pro WHERE id=$IdGrupo";
                $respPro = $this->getRow($sqlPro);
                $Uso = $respPro['Uso']; ///
                $Color = $respPro['Color'];  ///
                $Hazmat = $respPro['Hazmat'];///
                $CAS = $respPro['CAS'];///
                $UN = $respPro['UN'];///
                $Flameabilidad = $respPro['Flameabilidad'];///
                $Reactividad = $respPro['Reactividad'];///
                $Toxicidad = $respPro['Toxicidad'];///
                $Corrosividad = $respPro['Corrosividad'];///
                
                $version = $last_Version['Version'] + 1;
                $NombreVersion = $newPro['Nombre'] . "_Ver_" . $version;
                $Densidad = $newPro['Densidad']; 
                $Marca = $newPro['Marca'];
                $Concentracion = $newPro['Concentracion'];
                $Formulacion = 1;
                $newName =$dataOdt['NombreProducto'];

                $queryInsert = 'INSERT INTO productos (Nombre, Densidad, Color, Hazmat, Marca, Concentracion, Uso, Formulacion,Status,CAS,UN, Flameabilidad, Reactividad, Toxicidad, Corrosividad) 
                VALUES ("' . $newName . '",' . $Densidad . ',"' . $Color . '",' . $Hazmat . ',"' . $Marca . '",' . $Concentracion . ',"' . $Uso . '",' . $Formulacion . ',0,"' . $CAS . '","' . $UN . '","' . $Flameabilidad . '","' . $Reactividad . '","' . $Toxicidad . '","' . $Corrosividad . '")';
                // print_r($queryInsert);
                $resp = $this->getAllTableLastID($queryInsert);
                $Producto = $resp['last_id'];

                if ($resp = ['affected'] == true) {
                    // SE ACTUALIZARA AL MOMENTO DE CERRAR LA ODT ANGEL 20/04/23
                    // $costoUpd=$dataOdt['Costo']*$GLOBALS['TC'];
                    // $newPrecioLitro=$costoUpd/ $CantidadFabricar;
                    // $sqlInv = 'UPDATE inventario SET PrecioLitros= '.$newPrecioLitro.' WHERE Id='.$Producto;
                    // $respInv = $this->getAllTable($sqlInv);
                    foreach ($productos as $valueItem) {
                        $Id_Producto = $Producto;
                        $IdContenido = $valueItem['IdProducto'];
                        $Porcentaje = $valueItem['porcentaje'];
                        $queryContenido = 'INSERT INTO productoformulado (Grupo, IdProducto,IdContenido, Version, NombreVersion, Porcentaje) 
                        VALUES (' . $IdGrupo . ',' . $Id_Producto . ',' . $IdContenido . ',' . $version . ',"' . $NombreVersion . '",' . $Porcentaje . ')';
                        $resp = $this->getAllTable($queryContenido);
                        $status == 0;
                    }
                    $dataOdt['InventarioActual']=0;
                    $InventarioActual = $dataOdt['InventarioActual'];
                    $cantidadTotal = $dataOdt['InventarioActual'];
                    $InventarioDespues = $dataOdt['CantidadFabricar'];
                } else {
                    $msj = "Error al agregar nueva version";
                    $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

                    return $mensaje;
                }
            } else {
                $cantidadInv = 'SELECT Total FROM inventario WHERE Id_producto =' . $Producto;
                $cantidad = ($this->getAllTable($cantidadInv))->fetch_assoc();

                $InventarioDespues = $cantidad['Total'] + $CantidadFabricar;
                $cantidadTotal = $cantidad['Total'];
                $InventarioActual = $dataOdt['InventarioActual'];

            }
            $status = $dataOdt['status'];
            // $TipoCambio = $dataOdt['TipoCambio'];
            $TipoCambio = $GLOBALS['TC']; 
            $UsuarioAsignado = $dataOdt['UsuarioAsignado'];
            $Costo = $dataOdt['Costo']*$TipoCambio;
            // $InventarioDespues = $dataOdt['InventarioDespues'];


            if ($InventarioActual == $cantidadTotal) {

                $query = 'INSERT INTO ' . $tabla . ' (Usuario, TipoCambio, CantidadFabricar, UsuarioAsignado, Costo, Producto, InventarioActual, InventarioDespues) 
                        VALUES ("' . $user . '",' . $TipoCambio . ',' . $CantidadFabricar . ',"' . $UsuarioAsignado . '",' . $Costo . ',"' . $Producto . '",' . $InventarioActual . ',' . $InventarioDespues . ')';
                $resp = $this->getAllTableLastID($query);
                $Id = $resp['last_id'];
                foreach ($productos as $valueItem) {
                    $IdOdt = $Id;
                    $Id_Producto = $Producto;
                    $IdContenido = $valueItem['IdProducto'];
                    $Porcentaje = $valueItem['porcentaje'];
                    $LitroBarril = $valueItem['litrosBarril'];
                    $LitroProduccion = $valueItem['litrosProducion'];
                    $Costo = $valueItem['costoLitro']*$TipoCambio;
                    $Importe = $valueItem['importe']*$TipoCambio;
                    $query = 'INSERT INTO odtproducto (IdOdt ,IdProducto ,IdContenido ,Porcentaje, LitroBarril, LitroProduccion, Costo, Importe) 
                            VALUES (' . $IdOdt . ',' . $Id_Producto . ',' . $IdContenido . ',' . $Porcentaje . ',' . $LitroBarril . ',' . $LitroProduccion . ',' . $Costo . ',' . $Importe . ')';
                    // print_r($query);
                    $resp = $this->getAllTable($query);
                }
                if ($resp == true) {
                    $success = true;
                    $msj = 'Producto agregado correctamente';
                    $data = $IdOdt;
                } else {
                    $msj = 'Error al agregar un producto: ' . $tabla;
                }
            } else {
                $msj = "La cantidad de producto excede a la disponible en el inventario";
            }
            }else{
                $msj = "Productos insuficientes";
            }


        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
 
    function putOdtStatus($tabla, $id, $status)
    {
        $success = false;
        $data = "Error";
        if (isset($id) && isset($tabla) && isset($status)) {

            $query = "UPDATE " . $tabla . " SET Status = $status WHERE id =$id";
            $resp = $this->updateTable($query);
            if ($resp == true) {
                $success = true;
                $msj = 'Orden de trabajo Cancelada correctamente';
                $data = "Correcto";
            } else {
                $msj = 'Error al Cancelar un Orden de trabajo: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }

    function dataExcelOdt($tabla)
    {

        $arrayQuery=$this->getDataOdt($tabla);

        $success = false;

        $data = "Error";

        if (isset($tabla)) {

            $arrayDataExcel = [];

            foreach ($arrayQuery['data'] as $row) {

                $newRow=[];

                $newRow['Id'] = $row['Id'];
                $newRow['Usuario'] = $row['Usuario'];
                $newRow['Usuario asignado'] = $row['nameUser'];
                $newRow['Cantidad a fabricar'] = $row['CantidadFabricar'];
                $newRow['Producto'] = $row['nameProducto'];
                $newRow['Costo'] = $row['Costo'];
                $newRow['Inventario actual'] = $row['InventarioActual'];
                $newRow['Inventario despues'] = $row['InventarioDespues'];
                $newRow['Tipo de cambio'] = $row['TipoCambio'];

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


    function getDataOdt($tabla)
    {
        $success = false;
        $data = "Error";
        if (!empty($tabla)) {

            $query='SELECT odt.*, pt.Nombre AS nameProducto, us.User AS nameUser FROM odt 
            left JOIN productos pt ON pt.Id= odt.Producto
           left JOIN user_accounts us ON us.Id =  odt.UsuarioAsignado
            ';


            $resp = $this->getAllTable($query);
            $data = [];
            while ($datosBD = $resp->fetch_assoc()) {

                $datosBD['Costo']=$datosBD['Costo']*$GLOBALS["Moneda"];
                $datosBD['Id'] = (int)$datosBD['Id'];
                $data[] = $datosBD;
            }
            if ($resp == true) {
                $success = true;
                $msj = 'Exito en consulta de datos';
                $data = $data;
            } else {
                $msj = 'Error al consultar la tabla: ' . $tabla;
            }
        } else {
            $msj = "No se recibieron datos";
        }
        $mensaje = array("success" => $success, "data" => $data, "message" => $msj);

        return $mensaje;
    }
    function cerrarOdt($id,$status){
        $success=false;
        $data="Error";
        $flag=0;
        $msj='Error al cerrar orden de venta';

        if(isset($id)&& isset($status)){
            $sql="SELECT pro.Id, pro.Nombre, pro.Formulacion FROM productos pro 
            INNER JOIN odt o ON o.Producto = pro.Id
            WHERE o.Id=$id";
            $respPro = $this->getRow($sql);
            $idpro=$respPro['Id'];
            $nombrePro=$respPro['Nombre'];
            $formulacion=$respPro['Formulacion'];
            $queryContador="SELECT COUNT(Id_producto) AS cantidad FROM inventario WHERE Id_producto=$idpro";
            $respCount = $this->getRow($queryContador);
            $cantidad=$respCount['cantidad'];



            if($cantidad <1){
                $insert="INSERT INTO inventario (Id_producto,Nombre,Formulado) VALUES ($idpro,'$nombrePro',$formulacion)";
                // print_r($insert);
    
                $respInsert = $this->getAllTableLastID($insert);
                if ($respInsert['success'] == false) {
                    $flag=1;
                }
            }
           
            // print_r($respInsert);
            $resp=false;
            if ($flag==0) {
                $query = "UPDATE odt SET Status = $status WHERE id =$id";
                // print_r($query);
                $resp = $this->updateTable($query);

                $queryProducto = "UPDATE productos SET Status = 1 WHERE id =$idpro";
                $respPro = $this->updateTable($queryProducto);
                if ($resp==true) {
                    $success=true;
                    $msj='Orden de venta cerrada correctamente';
                    // $data="Correcto";
                }
            }
           
        }else{
            $msj = "No se recibieron datos";
        }
        $mensaje= array("success" => $success,"data"=>$respPro, "message" => $msj);

        return $mensaje;
    }
}
