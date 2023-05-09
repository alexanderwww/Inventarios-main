<?php
// require_once __DIR__ . '/vendor/autoload.php';
require_once '../../vendorComposer/vendor/autoload.php';

use Luecano\NumeroALetras\NumeroALetras;


if (!$_POST) {
    echo 'pagina No disponible';
    return;
}

if (!empty($_POST['dataPDF']) == false) {
    echo 'Variable POST No definida';
    return;
}

$data = json_decode($_POST['dataPDF'], true);
$namePDF=$data['namePDF'].'.pdf';




function writeToNumber($number,$currency)
{
    $formatter = new NumeroALetras();
    $decimals = 2;
    return $formatter->toMoney($number, $decimals, $currency, 'CENTAVOS');
}

function createRow($data)
{

    $stringRow = '';

    foreach ($data as $value) {

        $stringRow .= '
            <tr>
                <td>'.$value['noParte'].'</td>
                <td>'.$value['description'].'</td>
                <td>'.number_format($value['lot'],2).'</td>
                <td>'.$value['unitMedida'].'</td>
                <td>$'.number_format($value['unitPrice'],2).'</td>
                <td>$'.number_format($value['total'],2).'</td>
            </tr>
        ';
    }

    return $stringRow;
}


$htmlPDF = '<body>
<div style="position:absolute;width:100%;height:10px" ></div>
    <section>

        <div  style="float: left; width: 33%;">
            <div>
                <img style="width:200px;height:100px" src="./img/5inco.png">
            </div>
        </div>

        <div  style="float: left; width: 33%;">
            <div style="text-align:center">

                <p style="display:block;margin:5px">
                    <strong>'.$data['descriptionOrder']['agent'].'</strong>
                </p>
                <p class="fontZise__description" style="display:block;margin:0">
                    RFC:'.$data['descriptionOrder']['rfc'].'
                </p>
                <p class="fontZise__description"  style="display:block;margin:0">
                    '.$data['descriptionOrder']['direction'].'
                </p>
    
                <p class="fontZise__description"  style="display:block;margin:0">
                TEL. '.$data['descriptionOrder']['phone'].'
                </p>


            </div>
        </div>

        <div  style="float: left; width: 33%;text-align:right">
            <div>
                <p style="display:block;margin:5px">
                    <strong>ORDEN DE <br>COMPRA <br>#'.$data['descriptionOrder']['invoice'].'</strong>
                </p>
                <p class="fontZise__description"  style="display:block;margin:5px">
                    <strong>Fecha emitida:</strong> <br>
                    '.$data['descriptionOrder']['inssuedDate'].'
                </p>
            </div>
        </div>

    </section>

    <section>

        <div  style="float: left; width: 33%;">
            <div>
                <p class="fontZise__description" style="display:block;margin:10px 0 0 0">
                    <strong>Proveedor:</strong>
                </p>
                <p class="fontZise__description" style="display:block;margin:0">
                '.$data['provider']['name'].'                    
                </p>
                <p class="fontZise__description" style="display:block;margin:0">
                '.$data['provider']['direction'].'                     
                </p>

                <p class="fontZise__description" style="display:block;margin:0">
                    <strong>RFC:</strong>
                    '.$data['provider']['rfc'].'
                </p>

            </div>
        </div>

        <div  style="float: left; width: 33%;">
            <div style="text-align:center">
                <p class="fontZise__description" style="display:block;margin:5px">
                    <strong>Atención:</strong>
                    '.$data['provider']['attention'].'
                </p>
                <p class="fontZise__description" style="display:block;margin:5px">
                    <strong>Télefono:</strong>
                    '.$data['provider']['phone'].'
                </p>
                <p class="fontZise__description" style="display:block;margin:5px">
                    <strong>Correo:</strong>
                    '.$data['provider']['mail'].'
                </p>
            </div>
        </div>

        <div  style="float: left; width: 33%;text-align:right">
                <div>
                    <p class="fontZise__description" style="display:block;margin:5px">
                        <strong>Agente:</strong> <br>
                        '.$data['descriptionOrder']['agent'].'
                    </p>
                    <p class="fontZise__description" style="display:block;margin:5px">
                    <strong>Tipo de moneda:</strong> <br>
                    '.$data['descriptionOrder']['currency'].'
                </p>
            </div>
        </div>

    </section>

    <section>
        <p style="display:block;margin-top:20px">
            <strong>Estimado proveedor</strong> <br>
            Adjunto orden de compra.
        </p>
    </section>


    <section>
        <table id="tablepurchaseOrder">
            <thead>
                <tr>
                    <th>No. Parte</th>
                    <th>Descripción </th>
                    <th>Cantidad</th>

                    <th>Unidad de <br> Medida</th>
                    <th>Precio <br> Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                '.createRow($data['purchaseOrder']).'
            </tbody>
        </table>
    </section>

    <section style="margin: 30px 0 0 0">


        <table id="tableBalanceTotal">
            <thead>
                <tr>
                    <th style="text-align:left;width:70%" >
                    <p class="fontZise__balance" style="display:block;margin:0">
                    Importe con Letra:
                    </p>
                    </th>
                    <th style="text-align:right;width:10%" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            Subtotal:
                        </p>
                    </th>
                    <th style="text-align:left;width:20%" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            $'.number_format($data['balance']['subtotal'],2).'
                        </p>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td  style="text-align:left" >
                        <p class="fontZise__witre" style="display:block;margin:0;">   
                            '.writeToNumber($data['balance']['total'], $data['balance']['currency']).'.
                        </p>
                    </td>
                    <td  style="text-align:right" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            IVA:
                        </p>
                    </td>
                    <td  style="text-align:left" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            $'.number_format($data['balance']['iva'],2).'
                       </p>
                   </td>
                </tr>
                <tr>
                    <td  style="text-align:left" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            Observaciones:
                        </p>
                    </td>
                    <td  style="text-align:right" >
                        <p class="fontZise__balance" style="display:block;margin:0">            
                            Total:
                        </p>
                    </td>
                    <td  style="text-align:left" >
                        <p class="fontZise__balance" style="display:block;margin:0">
                            $'.number_format($data['balance']['total'],2).'
                        </p>
                    </td>
                </tr>

            </tbody>
        </table>

    </section>

</body>';




$css = file_get_contents('purchaseOrder.css');
$mpdf = new \Mpdf\Mpdf([]);
$plantilla = $htmlPDF;

$mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

// $mpdf->Output();
// $mpdf->Output($namePDF, 'D');


if($_POST['statusPDF']==1){

    $mpdf->Output($namePDF, 'D');
}else{

    $mpdf->Output();
    
}


// DATOS A ENVIAR 
// $data=[
//     'descriptionOrder'=>[
//         'invoice'=>953,
//         'inssuedDate'=>'03/02/2023 - 03:38 PM',
//         'agent'=>'Kevin Alexander',
//         'currency'=>'Dólares',
//         'rfc'=>'DFSD8DGDS7GS',
//         'direction'=>'PROLONGACIÓN ELOTE, COL. LAS HUERTAS, NO. EXT: 90 TIJUANA, BAJA CALIFORNIA, MEXICO C.P. 22116',
//         'phone'=>'664 456 45 56'
//     ],
    
//     'provider'=>[
//         'name'=>'BRENNTAG PACIFIC S DE RL DE CV',
//         'direction'=>'CIUDAD INDUSTRIAL CINCO SUR 9048 TIJUANA BAJA CALIFORNIA MEXICO 22444',
//         'rfc'=>'SFSD8783FSFSDFDS',
//         'attention'=>'',
//         'phone'=>'',
//         'mail'=>''
//     ],

//     'purchaseOrder'=>[
//         [
//             'noParte'=>133,
//             'description'=>'METANOL LIMPIO 100 ',
//             'lot'=>13235,
//             'unitMedida'=>'LTs',
//             'unitPrice'=>0.48,
//             'total'=>99944.433
//         ],
//         [
//             'noParte'=>133,
//             'description'=>'METANOL LIMPIO 100 ',
//             'lot'=>13235,
//             'unitMedida'=>'LTs',
//             'unitPrice'=>0.48,
//             'total'=>99944.433
//         ],
//         [
//             'noParte'=>133,
//             'description'=>'METANOL LIMPIO 100 ',
//             'lot'=>13235,
//             'unitMedida'=>'LTs',
//             'unitPrice'=>0.48,
//             'total'=>99944.433
//         ]
//     ],

//     'balance'=>[
//         'currency'=>'DOLARES',
//         'subtotal'=>34565.66,
//         'iva'=>46.90,
//         'total'=>79999.42,
//         'observations'=>'SIN dsadasdad asdsadasd sadasdsadasda asdasdasdasdas asdadasd asdasds'
//     ]

// ];
