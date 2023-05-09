<?php include '../../requerimientos/headers/header.php' ?>



<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">
<!-- page content including menus -->
<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y" style="padding-top:5px !important">







    <div class="card mb-4 containerFont" style="margin-bottom: 10px !important;">

        <div class="row px-4 py-3" style="align-items: center;margin: 0 !important;padding: 0 !important;">

            <div class="col-6 row">
            <div class="col-3 text-center">

                <label for="">Margen</label>
                <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;justify-content: center;width: 100%;">
                    <input onchange="calculateMargen(this)" onkeyup="calculateMargen(this)" class="form-control borderRadiusInputLiters stylePorcenta_input" style="height: 10px;" type="text" id="margen_porcentaje">
                    <span class="input-group-text stylePorcenta_" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0; height: 10px;">%</span>
                </div>


            </div>

            <div class="col-3 text-center">
                <em style="display: block;">Precio total de venta:</em>
                <strong>
                    <span>$</span>
                    <span id="margen_precioTotalVenta"></span>
                </strong>
            </div>

            <div class="col-3 text-center">
                <em style="display: block;">Precio de venta por litros:</em>
                <strong>
                    <span>$</span>
                    <span id="margen_precioVentaPorLitros"></span>
                </strong>
            </div>

            <div class="col-3 text-center">
                <em style="display: block;">Precio de venta por barril:</em>
                <strong>
                    <span>$</span>
                    <span id="margen_precioVentaPorBarril"></span>
                </strong>
            </div>
            </div>

            <div class="col-6"></div>



        </div>


    </div>


    <div class="card text-center">


        <!-- ---------------------------------  -->

        <div class="row">
            <div class="col-12">

                <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;padding-bottom: 0;">
                    <label style="padding-left: .5rem;padding-right: .5rem;" for="">Cantidad a fabricar</label>
                    <input onkeyup="setRowsTable(this)" onchange="setRowsTable(this)" style="padding-top: 0rem; padding-bottom: 0rem;height: 20px;font-size: 10px;" class="form-control borderRadiusInputLiters" type="text" id="cantidadFabricar">
                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;height: 20px;">LTS</span>
                </div>

            </div>
        </div>

        <div class="table-responsiveQUITAR text-nowrapQUITAR m-3 inline containerTableMain " style="min-height:400px;margin: 0 !important;">




            <table class="table" id="tableMain">

                <thead>

                    <tr>

                        <th class="styleTh">NOMBRE PRODUCTO</th>
                        <th class="styleTh">PORCENTAJE</th>

                        <th class="styleTh">COSTO POR LITRO</th>
                        <th class="styleTh">IMPORTE TOTAL</th>
                        <th class="styleTh">INVENTARIO</th>


                        <th class="styleTh">BARRILES PARA PRODUCCIÓN</th>
                        <th class="styleTh">LITROS PARA PRODUCCION</th>

                        <th class="styleTh">AGREGAR</th>
                        <th class="styleTh">ELIMINAR</th>

                    </tr>

                </thead>



                <tbody id="containerTable"></tbody>


                <tfoot></tfoot>

            </table>



        </div>

        <!-- ----------------------------------------  -->
    </div>


    <!-- ---------------------  -->
    <div class="col-12" style="padding: 5px;"></div>

    <div class="card mb-4 containerFont" style="margin-bottom: 10px !important;">

        <div class="row px-4 py-3 text-center" style="align-items: center;padding: 0 !important;">

            <div class="col-4">
                <em style="display: block;">Porcentaje:</em>
                <strong>
                    <span id="balance_porcentaje"></span>
                    <span>%</span>
                </strong>

            </div>

            <div class="col-4">
                <em style="display: block;">Costo por litro:</em>
                <strong>
                    <span>$</span>
                    <span id="balance_costoPorLitro"></span>
                </strong>
            </div>

            <div class="col-4">
                <em style="display: block;">Importe total:</em>
                <strong>
                    <span>$</span>
                    <span id="balance_importeTotal"></span>
                </strong>
            </div>

            <!-- <div class="col-4"><em style="display: block;">Inventario:</em><strong id="balance_inventario">2000</strong></div> -->
            <div class="col-4">
                <em style="display: block;">Barriles para producción:</em>
                <strong>
                    <span id="balance_barrilesParaProduccion"></span>
                </strong>
            </div>

            <div class="col-4">
                <em style="display: block;">Litros para producción:</em>
                <strong id="balance_litrosPorProduccion"></strong>
                <span>LTS</span>
            </div>


            <div class="col-4">
                <em style="display: block;">Costo por barril:</em>
                <strong>
                    <span>$</span>
                    <span id="balance_costoPorBarril"></span>
                </strong>
            </div>

        </div>
    </div>

    <!-- <div class="col-12" style="padding: 5px;"></div> -->

</div>




<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>

<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

<?php include '../../requerimientos/headers/footer.php' ?>



<script src="js/cotizador.js"></script>

<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>