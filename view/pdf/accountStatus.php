<?php
require_once '../../requerimientos/vendorComposer/vendor/autoload.php';
// require_once '../../vendorComposer/vendor/autoload.php';

function createRow($data)
{

    $stringRow = '';

    foreach ($data as $value) {

        $totalMX=$value['totalMX']?'$'.number_format($value['totalMX'],2):$value['totalMX'];

        $totalUSA=$value['totalUSA']?'$'.number_format($value['totalUSA'],2):$value['totalUSA'];

        $amountPaid=$value['amountPaid']?'$'.number_format($value['amountPaid'],2):$value['amountPaid'];

        $saldo=$value['saldo']?'$'.number_format($value['saldo'],2):$value['saldo'];

        $stringRow .= '
            <tr>
                <td>' . $value['date'] . '</td>

                <td>' . $value['noOrder'] . '</td>
                
                <td>' . $value['noBill'] . '</td>

                <td>' .$totalMX. '</td>
                <td>' .$totalUSA. '</td>
                <td>' . $amountPaid. '</td>

                <td>' . $saldo . '</td>

                <td>' . $value['dueDate'] . '</td>
                
                <td>' . $value['status'] . '</td>
            
            </tr>
        ';
    }
    return $stringRow;
}


$data=[
    'cliente'=>'ALMA VERENICE GARCIA AVITIA',
    'creator'=>'ALEJANDRA ORTIZ',
    'tc'=>28.22,
    'date'=>'22/03/2023',

    'info'=>[
        'name'=>'IVÁN PELAEZ GARCÍA',
        'regimen'=>'PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES',
        'rfc'=>':PEGI620904SQ1'
    ],
    
    'items'=>[
        [
            'date'=>'23/02/2022',
            'noOrder'=>1355,
            'noBill'=>5907,
            'totalMX'=>50760.33,
            'totalUSA'=>580333.3,
            'amountPaid'=>0.33,
            'saldo'=>false,
            'dueDate'=>'28/09/23',
            'status'=>'POR PAGAR'
        ],
        [
            'date'=>'23/02/2022',
            'noOrder'=>1355,
            'noBill'=>5907,
            'totalMX'=>50760.33,
            'totalUSA'=>580333.3,
            'amountPaid'=>0.33,
            'saldo'=>5805.4,
            'dueDate'=>'28/09/23',
            'status'=>'POR PAGAR'
        ]
    ]
];

$htmlPDF = '
<body>

        <section style="position:relative;">

            <div style="float: left; width: 30%;">
                <img style="width:200px;height:100px" src="./img/5inco.png" alt="">
            </div>

            <div style="float: right; width: 70%;">
                <div style="background:#00335f;color:#fff;text-align:right;padding:5px 15px">
                    <strong style="font-size:20px">Estado de Cuenta</strong>
                </div>
                <div style="padding:10px">

                    <div>

                        <div style="float: left; width: 50%;margin:10px 0">
                            <strong>Cliente:</strong>
                            <span>' . $data['cliente'] . '</span>
                        </div>
                        <div style="float: left; width: 50%;">
                            <strong>T.C:</strong>
                            <span>' . $data['tc'] . '</span>
                        </div>
  
                    </div>

                    <div>
                        
                        <div style="float:left; width:50%">
                            <strong>Generado Por:</strong>
                            <span>' . $data['creator'] . '</span>
                        </div>

                        <div style="float:left; width:50%">
                            <strong>Fecha:</strong>
                            <span>' . $data['date'] . '</span>
                        </div>

                    </div>

                </div>
            </div>

        </section>

        <section style="position:relative;">
            
            <div style="float: left;">
                <div style="background:#f1f1f1;margin:10px;padding:10px;border-radius:10px;margin:15px 0">
                    <div class="" style="display: block;"><strong>5INCO TRADING</strong></div>
                    
                    <div class="info__pdf" style="display: block;">
                        ' . $data['info']['name'] . '
                    </div>
                    
                    <div class="info__pdf">
                    ' . $data['info']['regimen'] . '
                    </div>
                    
                    <div class="info__pdf">RFC:
                    ' . $data['info']['rfc'] . '
                    </div>
                    
                </div>
            </div>

        </section>

        <section style="position:relative;">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>No. Pedido</th>
                        <th>No. Factura</th>

                        <th>MN</th>
                        <th>DLLS</th>
                        <th>Importe Pagado</th>

                        <th>Saldo</th>
                        <th>Fecha de vencimiento</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                '.createRow($data['items']).'
                </tbody>
            </table>
        </section>

</body>';


$css = file_get_contents('accountStatus.css');

$mpdf = new \Mpdf\Mpdf([
    'orientation' => 'L'
]);
$plantilla = $htmlPDF;
$mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
// $mpdf->Output();



if($_POST['statusPDF']==1){

    // $mpdf->Output($namePDF, 'D');
    $mpdf->Output('EstadoDeCuenta_.pdf', 'D');

}else{

    $mpdf->Output();
    
}
