<?php
require_once '../../vendorComposer/vendor/autoload.php';


if ($_POST) {

    if (empty($_POST['dataPDF']) == false) {

        $arrayData = json_decode($_POST['dataPDF'], true);
        $namePDF=$arrayData['namePDF'].'.pdf';

        $htmlPDF = '<body>' . createOrder($arrayData) . '</body>';

        $css = file_get_contents('orderOfOrders.css');
        $mpdf = new \Mpdf\Mpdf([]);
        $plantilla = $htmlPDF;
        $mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

        if($_POST['statusPDF']==1){

            $mpdf->Output($namePDF, 'D');
        }else{
    
            $mpdf->Output();
            
        }

        // Data a enviar 
        // $data = [ [
        //     'invoice' => 13567,
        //     'bill' => 4556776,
        //     'oc' => 'OC',
        //     'creator' => 'Alexander',
        //     'tc' => 15,
        //     'date' => '22/03/22',
        //     'company' => [
        //         'name' => '5INCO TRADING',
        //         'direction' => 'PROLONGACIÓN ELOTE #90, LAS HUERTAS 1RA SECCIÓN
        //         TIJUANA, BAJA CALIFORNIA, CP. 22116',
        //         'cell' => ' 664 396 0114',
        //         'phone' => ' 664 396 0114'
        //     ],
        //     'client' => [
        //         'name' => 'CARPINTERIA CARRILLO',
        //         'direction' => 'AV.SOCRATES, CAMINO VERDE, EXT: 6811, INT:, , , C.P:2022',
        //         'rfc' => '24FDSGSD33'
        //     ],
        //     'products' => [
        //         [
        //             'name' => 'THINNER LACQUER 020320',
        //             'lot' => 1000,
        //             'um' => 'CUBETA',
        //             'unitPrice' => 2500,
        //             'amount' => 50000
        //         ],
        //         [
        //             'name' => 'THINNER LACQUER 020320',
        //             'lot' => 1000,
        //             'um' => 'CUBETA',
        //             'unitPrice' => 2500,
        //             'amount' => 50000
        //         ],
        //         [
        //             'name' => 'THINNER LACQUER 020320',
        //             'lot' => 1000,
        //             'um' => 'CUBETA',
        //             'unitPrice' => 2500,
        //             'amount' => 50000
        //         ]
        //     ],

        //     'balance'=>[
        //         'total'=>1000000,
        //         'subtotal'=>5000000,
        //         'iva'=>1000
        //     ]

        // ]];

    } else {

        echo 'Variable POST No definida';
    }
} else {
    echo 'pagina No disponible';
}


function createOrder($data)
{
    $stringOrder = '';

    // foreach ($arrayOrder as $data) {
        $stringOrder .= '
<section style="height:100%">

    <section style="position:relative;">

        <div style="float: left; width: 30%;">
            <img style="width:200px;height:100px" src="./img/5inco.png" alt="">
        </div>

        <div style="float: right; width: 70%;">
            <div style="background:#00335f;color:#fff;text-align:right;padding:5px 15px">
                <span style="font-size:20px">Pedido</span>
            </div>
            <div style="padding:10px">

                <div>
                    <div style="float: left; width: 33%;">
                        <strong>Folio:</strong>
                        <span>' . $data['invoice'] . '</span>
                    </div>
                    <div style="float: left; width: 33%;">
                        <strong>Factura:</strong>
                        <span>' . $data['bill'] . '</span>
                    </div>
                    <div style="float: left; width: 33%;">
                        <strong>O.C:</strong>
                        <span>' . $data['oc'] . '</span>
                    </div>
                </div>

                <div>
                    <div style="float:left; width:33%">
                        <strong>Generado Por:</strong>
                        <span>' . $data['creator'] . '</span>
                    </div>
                    <div style="float:left; width:33%">
                        <strong>T.C:</strong>
                        <span>' . $data['tc'] . '</span>
                    </div>
                    <div style="float:left; width:33%">
                        <strong>Fecha:</strong>
                        <span>' . $data['date'] . '</span>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <section style="position:relative;">
        
        <div style="float: left;width:50%;">
            <div style="background:#f1f1f1;margin:10px;padding:10px;border-radius:10px;height:100px">
                <div class="" style="display: block;"><strong>5INCO TRADING</strong></div>
                <div class="info__pdf" style="display: block;">' . $data['company']['name'] . '</div>
                <div class="info__pdf">' . $data['company']['direction'] . '</div>
                <div class="info__pdf">CEL:' . $data['company']['cell'] . '</div>
                <div class="info__pdf">TEL:' . $data['company']['phone'] . '</div>
            </div>
        </div>

        <div style="float: left;width:50%;">
            <div style="background:#f1f1f1;margin:10px;padding:10px;border-radius:10px;height:100px">
                <div class="" style="display: block;"><strong>CLIENTE</strong></div>
                <div class="info__pdf">' . $data['client']['name'] . '</div>
                <div class="info__pdf">' . $data['client']['direction'] . '</div>
                <div class="info__pdf">RFC:' . $data['client']['rfc'] . '</div>
            </div>
        </div>

    </section>

    <section style="position:relative;">
        <table>
            <thead>
                <tr>
                    <th>PRODUCTO</th>
                    <th>Cantidad</th>
                    <th>UM</th>
                    <th>Precio Unitario</th>
                    <th>Importe</th>
                </tr>
            </thead>
            <tbody>
                    ' . createRow($data['products']) . '
            </tbody>
        </table>
    </section>

    <section style="position:relative;margin:20px 0;">
        <div style="float: left;width:50%;">
            <div style="width:100px;height:100px;background:black">
                CODIGO QR
            </div>
        </div>
        <div style="float:right;width:50%;">
            <div style="text-align:right">
                <div>
                    <div style="color:#fff;background:#00335f;float:left;width:50%">
                        <div style="padding:5px 10px">Subtotal:</div>
                    </div>
                    <div style=";float:left;width:50%">
                        <strong style="padding:5px 10px;">$' . number_format($data['balance']['total'], 2) . '</strong>
                    </div>
                </div>
                <div>
                    <div style="color:#fff;background:#00335f;float:left;width:50%">
                        <div style="padding:5px 10px">IVA 0%:</div>
                    </div>
                    <div style="float:left;width:50%">
                        <strong style="padding:5px 10px;">$' . number_format($data['balance']['subtotal'], 2) . '</strong>
                    </div>
                </div>
                <div>
                    <div style="color:#fff;background:#00335f;float:left;width:50%">
                        <div style="padding:5px 10px">Total:</div>
                    </div>
                    <div style="float:left;width:50%">
                        <strong style="padding:5px 10px;">$' . number_format($data['balance']['iva'], 2) . '</strong>
                    </div>
                </div>

            </div>

        </div>
    </section>

</section>';
    // }

    return $stringOrder;
}


function createRow($data)
{

    $stringRow = '';

    foreach ($data as $value) {

        $stringRow .= '
    <tr>
        <td>' . $value['name'] . '</td>
        <td>' . number_format($value['lot'], 2) . '</td>
        <td>' . $value['um'] . '</td>
        <td>$' . number_format($value['unitPrice'], 2) . '</td>
        <td>$' . number_format($value['amount'], 2) . '</td>
    </tr>
';
    }
    return $stringRow;
}
