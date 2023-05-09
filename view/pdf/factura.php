<?php
// require_once '../../vendorComposer/vendor/autoload.php';
require_once '../../requerimientos/vendorComposer/vendor/autoload.php';

if ($_POST) {

    if (empty($_POST['dataPDF']) == false) {

        $arrayData = json_decode($_POST['dataPDF'], true);
        $namePDF = $arrayData['namePDF'] . '.pdf';

        $id_XML = 17103;

        $arrayData['xml']= getDataXML($id_XML);
        
        // print_r($arrayData);

        $htmlPDF = '<body>
        <section style="height:100%">'.
            createOrder($arrayData).
        '</section>
        </body>';

        $css = file_get_contents('factura.css');
        $mpdf = new \Mpdf\Mpdf([]);
        $plantilla = $htmlPDF;
        $mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);



        // if($_POST['statusPDF']==1){

            $mpdf->Output($namePDF, 'D');
        // }else{
            // $mpdf->Output();
        // }




        // return $mpdf->Output('', 'S');



    } else {

        echo 'Variable POST No definida';
    }
} else {
    echo 'pagina No disponible';
}

// <section style="height:100%">

function createOrder($data)
{
    $stringOrder = '';

    $stringOrder .= '

        <section style="position:relative;">

            <div style="float: left; width: 30%;">
                <img style="width:200px;height:100px" src="./img/5inco.png" alt="">
            </div>

            <div style="float: right; width: 70%;">
                <div style="background:#00335f;color:#fff;text-align:right;padding:5px 15px">
                    <span style="font-size:20px">Factura Electrónica (CFDI) v4.0</span>
                </div>
                <div style="padding:10px">

                    <div>
                        <div style="float: left; width: 50%;">
                            <strong>Serie:</strong>
                            <span>' . $data['folio'] . '</span>
                        </div>

                        <div style="float: left; width: 50%;">
                            <strong>Folio Fiscal:</strong>
                            <span>
                            '.$data['xml']["uuid"].'
                            </span>
                        </div>
                    </div>

                    <div>
                        <div style="float:left; width:50%">
                            <strong>Expedido:</strong>
                            <span>22205</span>
                        </div>

                        <div style="float:left; width:50%">
                            <strong>Fecha:</strong>
                            <span>' . $data['fechaCreacion'] . '</span>
                        </div>
                    </div>

                </div>
            </div>

        </section>




        <section style="position:relative;">
            
            <div style="float: left;width:100%;">
                <div style="background:#f1f1f1;margin:10px;padding:10px;border-radius:10px;height:100px">
                    <div class="" style="display: block;"><strong>5INCO TRADING</strong></div>
                    <div class="info__pdf" style="display: block;">' . $data['company']['name'] . '</div>
                    <div class="info__pdf">' . $data['company']['direction'] . '</div>
                    <div class="info__pdf">CEL:' . $data['company']['cell'] . '</div>
                    <div class="info__pdf">TEL:' . $data['company']['phone'] . '</div>
                </div>
            </div>



        </section>

        <section style="position:relative;">

            <table>
                <thead>
                    <tr>
                        <th style="font-size:15px" >Cliente</th>
                        <th style="font-size:15px" >Régimen Fiscal  Cliente</th>
                        <th style="font-size:15px" >Tipo de Comprobante</th>
                        <th style="font-size:15px" >Serie/Folio</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td style="font-size:15px" >'.$data['datosCliente']['cliente'].'</td>
                        <td style="font-size:15px" >'.$data['datosCliente']['regimenfiscalCode'].'</td>
                        <td style="font-size:15px" >FACTURA ELECTRÓNICA (CFDI) (I-INGRESO)</td>
                        <td style="font-size:15px" >'.$data['folio'].'</td>
                
                    </tr>


                </tbody>
            </table>


            <table>
                <thead>
                    <tr>
                        <th style="font-size:15px" >Forma y Condiciones de Pago</th>
                        <th style="font-size:15px" >Moneda</th>
                        <th style="font-size:15px" >Método de Pago</th>
                        <th style="font-size:15px" >UsoCFDI</th>

                    </tr>
                </thead>
                <tbody>
                
                    <tr>

                        <td style="font-size:15px;" >'.$data['metodoPago'].'</td>
                        <td style="font-size:15px;" >'.$data['balance']['moneda'].'</td>
                        <td style="font-size:15px;" >'.$data['forma_pago'].'</td>
                        <td style="font-size:15px;" >'.$data['usoCfdi'].'</td>
                
                    </tr>

                </tbody>
            </table>


            <table>

                <thead>
                    <tr>
                        <th style="font-size:15px;width:25%" >R.F.C.</th>
                        <th style="font-size:15px;width:50%" >Dirección:</th>
                        <th style="font-size:15px;width:25%" >Fecha y Hora</th>
                    </tr>
                </thead>

                <tbody>
                
                    <tr>


    
                        <td style="font-size:15px" >'.$data['datosCliente']['rfc'].'</td>
                        <td style="font-size:15px" >
                        '.
                        $data['datosCliente']['nombreCalle'].' '.
                        $data['datosCliente']['colonia'].' '.
                        $data['datosCliente']['ciudad'].' '.
                        $data['datosCliente']['estado'].' '.
                        $data['datosCliente']['pais'].' '.
                        $data['datosCliente']['cp'].' '

                        .'
                        </td>
                        <td style="font-size:15px" >
                        '.$data['fechaCreacion'].'
                        </td>
                
                    </tr>

                </tbody>
            </table>

        </section>




        <section style="position:relative;">
            <table>
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Código</th>

                        <th>Clave ProdServ</th>
                        <th>Descripción</th>

                        <th>Cantidad</th>
                        <th>Clave Unidad</th>

                        <th>Precio</th>
                        <th>Impuesto</th>
                        <th>Importe</th>

                    </tr>
                </thead>
                <tbody>
                        ' . createRow($data['products']) . '
                </tbody>
            </table>
        </section>


        <section style="position:relative;margin:20px 0;">

        <div style="width:100%;">
            <div style="text-align:right">

                <div>
                    <div style="color:#fff;background:#00335f;float:left;width:50%">
                        <div style="padding:5px 10px">Subtotal:</div>
                    </div>
                    <div style=";float:left;width:50%">
                        <strong style="padding:5px 10px;">$' .$data['balance']['subtotal'] . '</strong>
                    </div>
                </div>


            '.createRowIVA($data['statusInpuestos']).'


            <div>
                <div style="color:#fff;background:#00335f;float:left;width:50%">
                    <div style="padding:5px 10px">Total:</div>
                </div>
                <div style=";float:left;width:50%">
                    <strong style="padding:5px 10px;">' .$data['balance']['total'] . '</strong>
                </div>
            </div>


            <div>
                <div style="color:#fff;background:#00335f;float:left;width:50%">
                    <div style="padding:5px 10px">Observaciones:</div>
                </div>
                <div style=";float:left;width:50%">
                    <strong style="padding:5px 10px;">' .$data['observaciones'] . '</strong>
                </div>
            </div>


            </div>

        </div>
    </section>
        



        <section style="position:relative;margin:20px 0;">

                <div style="float: left;width:23%;">
                    <img src="data:image/png;base64, '.$data['xml']["qrCode"].'" alt="Red dot" />
                </div>

                <div style="float:right;width:70%;">
                    <div>

                            
                            <table cellspacing="2" cellpadding="4">
                                <tr>
                                    <td style="text-align:left;font-size: 12px;background-color: #00335f; color: #FFF;">Folio fiscal:</td><td style="font-size: 12px;text-align:left">'.$data['xml']["uuid"].'</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;font-size: 12px;background-color: #00335f; color: #FFF;">No de serie del Certificado del SAT:</td><td style="font-size: 12px;text-align:left">'.$data['xml']["noCertificadoSAT"].'</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;font-size: 12px;background-color: #00335f; color: #FFF;">No de serie del Certificado del CSD:</td><td style="font-size: 12px;text-align:left">'.$data['xml']["noCertificadoCFDI"].'</td>
                                </tr>
                                <tr>
                                    <td style="text-align:left;font-size: 12px;background-color: #00335f; color: #FFF;">Fecha y hora de certificación:</td><td style="font-size: 12px;text-align:left">'.$data['xml']["fechaTimbrado"].'</td>
                                </tr>
                            </table>

                    </div>
                </div>
        </section>

        

        <section style="position:relative;">

            <div style="float: right; width: 100%;">

                <div style="float: right; width: 100%;">
                    <div style="background:#00335f;color:#fff;padding:4px 3px">
                        <span style="font-size:10px;">
                            Sello Digital del CFDI:
                        </span>
                    </div>
                </div>

                <div style="float: right; width: 100%;">
                    <div style="padding:5px 0">
                      <span style="font-size:10px;line-height 10px;">
                        '.$data['xml']["selloCFDI"].'
                        </span>
                    </div>
                </div>

            </div>


            <div style="float: right; width: 100%;">
                <div style="float: right; width: 100%;">
                    <div style="background:#00335f;color:#fff;padding:4px 3px">
                        <span style="font-size:10px">
                            Sello del SAT:
                        </span>
                    </div>
                </div>
                <div style="float: right; width: 100%;">
                    <div style="padding:5px 0">
                        <span style="font-size:10px">
                            '.$data['xml']["selloSAT"].'
                        </span>
                    </div>
                </div>
            </div>


            <div style="float: right; width: 100%;">
                <div style="float: right; width: 100%;">
                    <div style="background:#00335f;color:#fff;padding:4px 3px">
                        <span style="font-size:10px">
                            Cadena original del complemento de certificación digital del SAT:
                        </span>
                    </div>
                </div>
                <div style="float: right; width: 100%;">
                    <div style="padding:5px 0">
                        <span style="font-size:10px">
                            '.$data['xml']["cadenaOriginalSAT"].'
                        </span>
                    </div>
                </div>
            </div>


        </section>

        <br />

        <div style="text-align: center;">Este documento es una representación impresa de un CFDI</div>

    ';

    return $stringOrder;
}

function createRowIVA($data){
    $stringRow="";


    foreach($data as $value){
        $stringRow.='
        <div>
            <div style="color:#fff;background:#00335f;float:left;width:50%">
                <div style="padding:5px 10px">Importe con Letra: </div>
            </div>
            <div style=";float:left;width:50%">
                <strong style="padding:5px 10px;font-size:10px">' .$value['importeLetra'] . '</strong>
            </div>
        </div>

        <div>
            <div style="color:#fff;background:#00335f;float:left;width:50%">
                <div style="padding:5px 10px">'.$value['title'].'</div>
            </div>
            <div style=";float:left;width:50%">
                <strong style="padding:5px 10px;">' .$value['cantidad'] . '</strong>
            </div>
        </div>
        ';
    };

    return $stringRow;

}

// Clave Unidad 
function createRow($data)
{

    $stringRow = '';

    foreach ($data as $value) {

        $stringRow .= '
            <tr>
                <td>' . $value['folio'] . '</td>
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

function getDataXml($id_XML)
{

    $file = file_get_contents($id_XML . "_timbrado.xml");
    $file = json_decode($file, true);

    if ($file['status'] == 'success') {

        // $arrayXML=[
        //     'cadenaOriginalSAT'=>$file['data']['cadenaOriginalSAT'],
        //     'noCertificadoSAT'=>$file['data']['noCertificadoSAT'],
        //     'noCertificadoCFDI'=>$file['data']['noCertificadoCFDI'],
        //     'uuid'=>$file['data']['uuid'],
        //     'selloSAT'=>$file['data']['selloSAT'],
        //     'selloCFDI'=>$file['data']['selloCFDI'],
        //     'fechaTimbrado'=>$file['data']['fechaTimbrado'],
        //     'qrCode'=>$file['data']['qrCode'],
        //     'cfdi'=>$file['data']['cfdi']
        //     ];

        return $file['data'];
    };

    return false;
};

