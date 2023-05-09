<?php
// require_once __DIR__ . '/vendor/autoload.php';
// require_once '../../vendorComposer/vendor/autoload.php';
require_once '../../requerimientos/vendorComposer/vendor/autoload.php';

function getTerminos()
{
    // $tarifa_val='22-02-22';
    // $PrecioCotizacion='122,222.00';
    // $Moneda='Dolares';
    $cotizaciones=[];

    $PrecioCotizacion = "$".number_format( floatval($cotizaciones[0]['PrecioCotizacion']),2,'.',',' );
    $Moneda = $cotizaciones[0]['Moneda'];

    // "$" . number_format(floatval($filasDb['TarifaCobro']), 2, '.', ',');

    $valueTariafa=$Moneda=='Dolares'?'USD '.$PrecioCotizacion:'MXN '.$PrecioCotizacion;


    $arrayTerminos = [

        'nacional' => [

            'incluye'=>[
                'title'=>'ESTA COTIZACIÓN INCLUYE <br>',
                'description'=>[
                    'Rastreo GPS en todo el trayecto.',
                    'Monitoreo las 24 horas del día.',
                    '8 horas para cargar y 8 horas para descarga.'
                ]
            ],


            'noIncluye' => [
                'title' => 'No incluye: - Impuestos - Maniobras de carga y descarga- Seguro de mercancía- Demoras y/o estadías- Gastos generados en destino.',
                'description' => [
                    'Tarifa expresada en <strong> MXN </strong>',
                    'Para todos <strong> servicios en territorio Mexicano </strong> causar <strong> IVA del 16% </strong> de conformidad con la normatividad vigente.',
                    'Vigencia de esta cotizacion hasta el <strong> '.$tarifa_val.' </strong>',
                ]
            ],

            'contratacionServicios'=>[
                'title'=>'TÉRMINOS Y PAGO PARA CONTRATACIÓN DEL SERVICIO',
                'description'=>[
                    '1. Los 5 primeros viajes deben ser liquidados antes de la descarga, posterior se trabaja a crédito de 15 días.',
                    '2. Si excede los términos de pago pactados, se agregará 5% al valor de factura vencida',
                    '3. Las cancelaciones del servicio se deberá reportar con 24 hrs de anticipación, de lo contrario se considerará movimiento en falso de $5,500 MN. más IVA.',
                    '4. Seguro de carga en México se cotiza por separado en caso de que cliente así lo solita.',
                    'TARIFAS PARA TRAMOS NACIONALES MEXICANOS ANTES DE IVA'
                ]
            ],


            'contratacionUnidades'=>[
                'title'=>'TÉRMINOS DE CONTRATACIÓN DE UNIDADES',
                'description'=>[
                    '1. <strong> Confirmación de unidades </strong> disponibilidad confirmada vía correo electrónico.',
                    
                    '2. Solicitud de unidades para cargar con mínimo 24 horas de anticipación. Favor adjuntar <strong> manual de instrucciones y procedimientos </strong> con requerimientos específicos para unidades y operadores.',
                    
                    '3.  <strong> Posicionamiento de la unidad </strong> de transporte con tiempo minimo de anticipacion de 48 horas.Solicitud por escrito
                    Carta de instrucciones y requerimientos específicos (En caso de que las mismas
                    fueran erróneas, y que imposibilite la recolección de la mercancía o posicionamiento de la
                    unidad, se generarán gastos adicionales como flete en falso, cambio de ruta, estadías, etcétera)'
                ]
            ],

            'unidadesDeTransporte' => [
                'title' => 'UNIDADES DE TRANSPORTE',
                'description'=>[

                    
                    '1. <strong> Peso de carga </strong> legal permitido por la SCT  (27 ton).Solicitud por escrito',

                    '2.El tiempo libre al <strong> cargar en origen </strong> es de 6 Hr.',

                    '3. El tiempo libre de <strong> descarga en destino </strong> es de 6 Hr. Una vez se supere el tiempo libre de carga y descarga se cobrará estadia.',

                    '4. Entrega o <strong> recolección adicional </strong> tiene un costo de $2,500 M.N.',

                    '5. A partir de las 24 horas se cobra día de estadía.',

                    '6. <strong> Desvío </strong> de ruta: $5.000 + IVA (sólo si aplica).',

                    '7. Rastreo GPS en todo el trayecto y monitoreo las 24 horas del día',

                    '8. 24 hrs. para despacho aduanal en frontera'

                ]
            ]

        ],




        'internacional' => [

            'general' => [
                'title' => '',
                'description' => [
                    'Administration Fee 10% will apply over total amount financed, for example pier-pass, demurrages, lumpers fee etc. -These charges are due immediately-.  <br> <br>',

                    '<strong> Alternate Route Due to Detour: </strong> <br> If the alternate route results in greater mileage than the original route, an additional charge of $5 USD per mile will be charged for all extra mileage traveled on alternate route.
                     The extra charge will be computed by the total excess mileage obtained from Governing Mileage Guide. <br> <br>',

                    '<strong> Custom Delays: </strong> <br> Delays at customs caused by document errors or mandatory Inspection include 1
                    free hour. *Any/All additional hour will be charged at $100.00 USD per hour until
                    released. <br> <br>',

                    '<strong> Fuel Surcharge: </strong> <br> FSC is calculated on a weekly basis via EIA site. EIA Site:
                    https://www.eia.gov/petroleum/gasdiesel/    <br><br>',

                    '<strong> Cargo Insurance in US only: </strong> <br> Carrier will not be liable for losses more than $100,000.00 USD per
                    shipment. If need more coverage an additional charge will apply per shipment. <br> <br>',

                    '<strong> Cargo Insurance in Mexico upon request: </strong> <br> We do not carry Mexican cargo insurance available
                    upon request, rate based on declared value per shipment.  <br> <br>',

                    'San Diego Yard Usage: 1 free day. After that, $50.00 USD per day. <br> <br>',

                    '<strong> Waiting Times: </strong> <br> On exports from Mexico to US, waiting time is first hour free, additional hours $
                    100.00 USD per hour. Truck must depart from plant within 1 hour with paperwork on
                    hand and directly to Customs. On imports from US to Mexico, waiting time is first hour free,
                    additional hours $ 100.00 USD per hour. Truck must depart within 1 hour with paperwork on hand
                    and directly to Customs. <br> <br>',

                    '<strong> Weight Ticket Fee: </strong><br> $20 USD when applicable. re-weight $10 USD each time.
                    Local move for Re-work: Returning to shipper for rework $150 USD in US, $150 USD in Mexico
                    E-Manifest : $15.00 USD each.
                    CFDI waybill complement: $30.00 USD each. <br> <br>',

                ]
            ],

            'oceanContainer' => [
                'title' => 'OCEAN CONTAINER DRAYAGE <br><br>',
                'description' => [
                    '<strong> Bobtail Charge Bobtail:  </strong><br> $300.00 USD + FSC: This may apply for pick up o return container to port
                    Perdiem Charges: Per-diem will be billed according to interchange agreement with steamship line.
                    Payment due upon receipt 48 hours notice required for empty returns. <br><br>',

                    '<strong> Port Congestion Fee: </strong><br> First 2 hours free. $80.00 USD per additional hour.<br><br>',

                    'IT, T &amp; E or Bond Use: $100.00 USD, US Customs Manipulation: $200.00 USD.<br><br>',

                ]
            ],


            'borderCrossing' => [
                'title' => 'BORDER CROSSING / OVER THE ROAD SERVICES <br><br>',
                'description' => [
                    'Detention w/ Power: First hour free. Additional time $150.00 USD per hour.
                Extra Stop: $150.00 USD per stop. One free hour, additional time will be $100 USD per hour
                Previous at Mexican Customs and Wood Inspections: $100 USD first hour free, $100 USD per
                additional hour until release from Mexican Customs <br><br>',

                    '<strong> Trailer Usage: </strong>',

                    '2 days free 3rd - 5th day $50 USD per day <br>
                    6th - 10th day $60 USD per day <br>
                    After $120 USD per day <br><br>',

                    'Congestion Fee: 2 Hours free, Additional time $80 USD per hour.',
                    'Return Load: $150 USD plus waiting time. <br><br>'

                ]
            ],


            'payment' => [
                'title' => 'PAYMENT TERMS: <br>',
                'description' => [
                    'Payment will be due within 15 days of invoice date. Payment for all invoices not received within 7
                    days of due date, may accrue interest charges at the rate of 5% per month, unless credit terms are
                    different and signed by both parties.<br><br>'
                ]
            ],


            'otherTerms' => [
                'title' => 'OTHER TERMS &amp; CONDITIONS: <br>',
                'description' => [
                    'Rates are based on dock-dock and live load and unload &amp; services with 1 free hour at origin and
                    one free hour at destination.<br><br>'
                ]
            ]




        ]



    ];


    // $statusTerminos='nacional';
    $statusTerminos='internacional';

    // if($statusTerminos=='nacional'){

        // $terminosUbicacion='Términos y condiciones NACIONALES:';

    // }
    // else{
        $terminosUbicacion = 'INTERNATIONAL terms and conditions:';
    // }



    $headerTerminos = `<div class="itemsInfoTerminos"><h4><strong>$terminosUbicacion</strong></h4><div class="listItemsTerminos">`;
    $footerTerminos = `</div></div>`;


    $stringTerminos = '';
    $stringTerminos .= $headerTerminos;


    // return $stringTerminos;


    $statusServicios = false;
    if ($statusServicios) {

        $stringTerminos .=createRowTerminos('<strong>' . $arrayTerminos['nacional']['incluye']['title'] . '</strong>');
        foreach ($arrayTerminos['nacional']['incluye']['description'] as $termino) {
            $stringTerminos .= createRowTerminos($termino);
        };

        $stringTerminos .=createRowTerminos('<strong>' . $arrayTerminos['nacional']['noIncluye']['title'] . '</strong>');
        foreach ($arrayTerminos['nacional']['noIncluye']['description'] as $termino) {
            $stringTerminos .= createRowTerminos($termino);
        };

        $stringTerminos .=createRowTerminos('<strong>' . $arrayTerminos['nacional']['contratacionServicios']['title'] . '</strong>');
        foreach ($arrayTerminos['nacional']['contratacionServicios']['description'] as $termino) {
            $stringTerminos .= createRowTerminos($termino);
        };

        $stringTerminos .=createRowTerminos('<strong>' . $arrayTerminos['nacional']['contratacionUnidades']['title'] . '</strong>');
        foreach ($arrayTerminos['nacional']['contratacionUnidades']['description'] as $termino) {
            $stringTerminos .= createRowTerminos($termino);
        };

        $stringTerminos .=createRowTerminos('<strong>' . $arrayTerminos['nacional']['unidadesDeTransporte']['title'] . '</strong>');
        foreach ($arrayTerminos['nacional']['unidadesDeTransporte']['description'] as $termino) {
            $stringTerminos .= createRowTerminos($termino);
        };

    };


            // -------------------------------------------------------------- 
        // Internacional 
        $statusServiciosInternacional = true;

        if ($statusServiciosInternacional) {

            $stringTerminos .= createRowTerminos('<strong>' . $arrayTerminos['internacional']['general']['title'] . '</strong>');
        
            foreach ($arrayTerminos['internacional']['general']['description'] as $termino) {
                $stringTerminos .= createRowTerminos($termino);
            };
        
        
        
            
            $stringTerminos .= createRowTerminos('<strong>' . $arrayTerminos['internacional']['oceanContainer']['title'] . '</strong>');
        
            foreach ($arrayTerminos['internacional']['oceanContainer']['description'] as $termino) {
                $stringTerminos .= createRowTerminos($termino);
            };
        
        
        
            $stringTerminos .= createRowTerminos('<strong>' . $arrayTerminos['internacional']['borderCrossing']['title'] . '</strong>');
        
            foreach ($arrayTerminos['internacional']['borderCrossing']['description'] as $termino) {
                $stringTerminos .= createRowTerminos($termino);
            };
        
        
            $stringTerminos .= createRowTerminos('<strong>' . $arrayTerminos['internacional']['payment']['title'] . '</strong>');
        
            foreach ($arrayTerminos['internacional']['payment']['description'] as $termino) {
                $stringTerminos .= createRowTerminos($termino);
            };
        
        
            $stringTerminos .= createRowTerminos('<strong>' . $arrayTerminos['internacional']['otherTerms']['title'] . '</strong>');
        
            foreach ($arrayTerminos['internacional']['otherTerms']['description'] as $termino) {
                $stringTerminos .= createRowTerminos($termino);
            };
        
        
        };



        // ----------------------------------------------------------------- 





    $stringTerminos.=$footerTerminos;

    return $stringTerminos;
}


function createRowTerminos($string)
{

    return '
    <div style="float: left; width: 100%;" >
        <span>
            
                ' . $string . '
            
        </span>
    </div>
    ';
}


$htmlPDF  = '
<body>

    <div class="border"></div>
    <div style="position: absolute; left: 10%; top:0; padding:30px">
        <img   style="width:80px" src="img/ri_2.png" />
    </div>

    <div style="float: right; width: 100%; height: 20px; "  ></div>

    
    <section>

        <div class="containerDescriptionTitle">

            <div>

                    <div class="itemDescriptionTitle" style="float: left; width: 50%;" >

                        <h2>                    
                            <strong>Control Terrestre</strong>
                        </h2>
                        <em >
                            NADA NOS DETIENE
                        </em>

                    </div>

                    <div class="itemDescriptionTitle" style="float: right; width: 50%; text-align: right;" >

                        <h2>
                            <strong>COTIZACIÓN</strong>
                        </h2>
                        
                    </div>

            </div>

        </div>

    </section>

    <div style="float: right; width: 100%; height: 25px;"></div>
    
    <section>

        <div class="containerDescriptionDirection">



            <div>

                    <div class="itemDescriptionDirection" style="float: left; width: 50%; font-size: 12px;" >

                        <div style="float: left; width: 100%;" >
                            <span>
                                Dirección: P.º de los Héroes 301, Zona Urbana Rio.
                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>
                                Ciudad, estado, código postal: Tijuana, 22010 Tijuana, B.C
                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>
                                Teléfono: 800 002 6875 / 55 2850 6459
                            </span>
                        </div>

                    </div>



                    <div class="itemDescriptionDirection" style="float: right; width: 50%;text-align: right;" >

                            <div style="float: left; width: 100%;" >
                                <span>
                                
                                <strong>FECHA: </strong>
                                23/03/2023
                                </span>
                            </div>

                            <div style="float: left; width: 100%;" >
                                <span>
                                <strong>Cotización n.º: </strong>
                                314
                                </span>
                            </div>

                            <div style="float: left; width: 100%;" >
                                <span>
                                    <strong>Id. del cliente:</strong>
                                </span>
                            </div>

                    </div>



            </div>



        </div>

    </section>


    <div style="float: right; width: 100%; height: 25px; "  ></div>

    <section>

        <div class="containerDescriptionPresupuesto">

            <div>

                    <div class="itemDescriptionPresupuesto" style="float: left; width: 50%;  font-size: 12px;" >

                        <div style="float: left; width: 100%;" >
                            <span>

                                <strong>
                                    Presupuesto para:
                                </strong>

                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>

                            Nombre:
                            PRUEBA

                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>

                            Nombre de la empresa:
                            PRUEBA

                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>
                            Dirección:
                            Prueba
                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                            <span>
                                Ciudad y código postal: 
                                PRUEBA /PRUEBA
                                Prueba
                            </span>
                        </div>

                        <div style="float: left; width: 100%;" >
                        <span>
                        Teléfono:
                        Prueba
                        </span>
                    </div>

                    </div>


                    <div class="itemDescriptionPresupuesto" style="float: right; width: 50%;  text-align: right;" >

                            <div style="float: left; width: 100%;" >
                                <span>
                                    <strong>Cotizacion válido hasta: </strong>
                                    23/03/2023
                                </span>
                            </div>

                            <div style="float: left; width: 100%;" >
                            <span>
                                <strong>Elaborado por: </strong>
                                Prueba
                            </span>
                        </div>

                    </div>


            </div>


        </div>

    </section>


    <div style="float: right; width: 100%; height: 25px; "  ></div>


    <div style="float: right; width: 100%; height: 50px; "  ></div>


    <section>
        <div class="containerTabla" style=" padding: 0 10px;">

            <div class="boxDescripcion" style="height: 30px;">


                <div  style="float: left; width: 70%;" >
                    <span>
                        <strong>TIPO DE UNIDAD: </strong>
                        PRUEBA
                    </span>
                </div>

                <div  style="float: right; width: 30%;" >
                    <span>
                        <strong>FECHA: </strong>
                        PRUEBA
                    </span>
                </div>


            </div>

            <div class="boxTabla">

                <table>

                    <tr class="titulosTabla">
                        <th>ORIGEN</th>
                        <th>DESTINO</th>
                        <th>TARIFA</th>
                    </tr>

                    <tbody>

                 

                    </tbody>

                </table>

            </div>

            <div style="float: right; width: 100%; height: 20px; "  ></div>

            <div class="boxCargosAdicionales" style=" text-align: center; font-size: 12px;">


                <div class="itemsCargosTitulo">
                    <h4 class="title"><strong>Cargos Adicionales (En caso de aplicar)</strong></h4>
                </div>

                <div class="itemsCargosSubtitulos">

                    <div style="float: right; width:50%;">
                        <strong>Description</strong>
                    </div>
                    <div style="float: right; width:50%;">
                        <strong>Rate</strong>
                    </div>

                </div>
                <div class="itemsCargos">


                    <div style="float: left; width: 100%; ">
                            <span class="textInfo">
                                1st Pick Up and 1st Delivery…………………………………………………... Included
                            </span>
                    </div>


                    <div style="float: left; width: 100%; ">
                        <span class="textInfo">
                            2 Free hours of Waiting total time………………………………………………... $105 USD / Extra HR
                        </span>
                    </div>


                    <div style="float: left; width: 100%; ">
                        <span class="textInfo">
                            2 Free Days of Waiting Time At customs………………………………………… $250 USD / Extra Day
                        </span>
                    </div>


                    <div style="float: left; width: 100%; ">
                        <span class="textInfo">
                            Monitoring GPS 24/7 ……………………………………………………………... Included
                        </span>
                    </div>


                    <div style="float: left; width: 100%; ">
                        <span class="textInfo">
                            CTPAT Specialist……………………………………………………………………… Included
                        </span>
                    </div>


                    <div>
                        <span class="textInfo">
                            TONU …………………………………………………………………………………………. 70% rate
                        </span>
                    </div>


                </div>

            </div>

        </div>

    </section>

    <div style="float: right; width: 100%; height: 20px; "  ></div>


    -----------------------------------------------

    <section>

        <div class="containerTerminos" style="font-size: 10px;">

            <div class="boxInfoTerminos">
                <div class="itemsInfoTerminos">

                    <h4 >
                        <strong>
                        GENERAL TERMS AND ACCESSORIAL CHARGES TIJUANA BORDER 
                        </strong>
                    </h4>

                    <div class="listItemsTerminos">
                    '.getTerminos().'
                    </div>
                </div>
                
            </div>
        </div>

    </section>


    -----------------------------------------------

    <div style="float: right; width: 100%; height: 50px; "  ></div>


    <section>

            <div>

                <div>
                    
                    <div style="float: left; width: 100%;" >

                        <div style="text-align:center">
    
                            <span style="font-zise:12px">
                                Si tienes cualquier tipo de pregunta acerca de esta oferta, comunícate con: Laura Camargo / +52 664 475 9119 / laura.camargo@controlterrestre.com
                            </span>

                        </div>

                        <h4 style="text-align:center">
                            <strong>
                            GRACIAS POR SU CONFIANZA
                            </strong>
                        </h4>

                    </div>

                </div>
                
            </div>

    </section>



    <div class="border"></div>



</body>';





$css = file_get_contents('purchaseOrder.css');
$mpdf = new \Mpdf\Mpdf([]);
$plantilla = $htmlPDF;

$mpdf->writeHtml($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->writeHtml($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);

// $mpdf->Output();
// $mpdf->Output($namePDF, 'D');


// if($_POST['statusPDF']==1){

// $mpdf->Output($namePDF, 'D');
// }else{

$mpdf->Output();
    
// }
