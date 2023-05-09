<?php


function getPlantilla($cotizaciones)
{


    $receptor_prop = "CLH BUSINESS COMPANY";


    $fecha = date("d-m-Y", strtotime($cotizaciones[0]['Fecha']));
    $tipo_unidad = $cotizaciones[0]['TipoUnidad'];

    $date_future = strtotime('+30 day', strtotime($cotizaciones[0]['Fecha']));
    $tarifa_val = date('d-m-Y', $date_future);

    $LL0 = [];

    foreach ($cotizaciones as $key => $value) {
        $LL0[$key] ='<tr>
        <td>'.$value['Origen'].'</td>
        <td>'.$value['Destino'].'</td>
        <td>$'.number_format($value['PrecioObjetivo'], 2).'</td>
        </tr>';
    }


    // $tarifa_val = '44000';
    // $receptor_prop = 'Nombressss';
    // $tipo_unidad = 'Unidad';
    // $fecha = '22/02/22';

    $plantilla = '
    <body>
       
        <div class="border"></div>
        <div style="position: absolute; right: 0; top:0; padding:30px">
            <img   style="width:80px" src="img/ri_2.png" />
        </div>
        <section>

            <div class="containerCabezera">

                <div style="float: left; width: 100%; ">
                   <h3>  <strong>PROPUESTA: </strong>   ' . $receptor_prop . ' </h3>
                </div>

               
               
            </div>

        </section>

        <div style="float: right; width: 100%; height: 50px; "  ></div>


        <section>
            <div class="containerAgradecimiento">

                <div class="boxAgredecimiento" style=" padding: 0 100px;">

                    <div style=" text-align: center;">
                        <span>
                            Muchas gracias por permitirnos la oportunidad de cotizar servicios para su compañía. Sera un honor
                            poder ser parte de la logística ya que entendemos sus necesidades de disponibilidad,
                            monitoreo y sobre todo su alto nivel de servicio al cliente.
                        </span>
                    </div>
                    
                    <div style="float: right; width: 100%; height: 10px; "  ></div>

                    <div style=" text-align: center;">
                        <span>
                            Contamos con el mejor equipo de seguimiento 24-7 para poder cumplir con los requerimientos de
                            monitoreo de nuestros clientes y en un futuro a ustedes.
                        </span>
                    </div>

                </div>

            </div>
        </section>

        <div style="float: right; width: 100%; height: 50px; "  ></div>


        <section>
            <div class="containerTabla" style=" padding: 0 10px;">

                <div class="boxDescripcion" style="height: 30px;">
               

                    <div  style="float: left; width: 70%;" >
                        <span>
                            <strong>TIPO DE UNIDAD: </strong>
                            ' . $tipo_unidad . '
                        </span>
                    </div>

                    <div  style="float: right; width: 30%;" >
                        <span>
                            <strong>FECHA: </strong>
                                ' . $fecha . '
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

                        '.implode($LL0).'

                        </tbody>
        
                    </table>
        
                </div>

                <div style="float: right; width: 100%; height: 50px; "  ></div>

                <div class="boxCargosAdicionales" style=" text-align: center;">

                    
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

        <div style="float: right; width: 100%; height: 50px; "  ></div>

        <section>
            <div class="containerFirmas">

                <div class="boxFirmas"  >

                        <div class="itemFirmas" style="float: left; width: 50%;  text-align: center; " >
                            <div style="padding:30px;" >
                                <span>Aprobado por:</span>
                            </div>
                            <div>
                                _______________________________________________
                            </div>
                            <div>
                                NOMBRE / PUESTO
                            </div>
                        </div>

                        <div class="itemFirmas" style="float: right; width: 50%;  text-align: center; " >
                            <div style="padding: 30px;">
                                <span>Firma</span>
                            </div>
                            <div>
                                __________________________________________
                            </div>
                            <div>
                                FIRMA / FECHA
                            </div>
                        </div>

                </div>

            </div>
        </section>
       
        <div style="float: right; width: 100%; height: 50px; "  ></div>


        <section>

            <div class="containerTerminos">

                <div class="boxInfoTerminos">
                    <div class="itemsInfoTerminos">
                        <h4 >
                            <strong>Términos de pago:</strong>
                        </h4>
                        <div class="listItemsTerminos">
                            
                           <div style="float: left; width: 100%;" > 
                                <span>Serán contemplado 30 días de crédito</span>
                            </div>
                           <div style="float: left; width: 100%;" > 
                                <span>Si excede los términos de pago pactados, se agregará 5% al valor de factura vencida</span>
                            </div>
                           <div style="float: left; width: 100%;" > 
                                <span>Tarifa valida por: <strong>' . $tarifa_val . '</strong></span>
                            </div>
                           <div style="float: left; width: 100%;" > 
                                <span>Seguro de carga en México se cotiza por separado en caso de que cliente así lo solita</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        
      

        <div class="border"></div>



    </body>';

    return $plantilla;
}
