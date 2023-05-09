<?php include '../../requerimientos/headers/header.php' ?>



<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">
<!-- page content including menus -->
<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Cotizador</span></h4>

    <div class="card mb-4">
        <div class="row px-4 py-3" style="align-items: center;">

            <div class="col-12">

                <label for="">Cantidad a fabricar</label>
                <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                    <input onkeyup="setRowsTable(this)" onchange="setRowsTable(this)" class="form-control borderRadiusInputLiters" type="text" id="cantidadFabricar">
                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
                </div>

            </div>

        </div>
    </div>


    <div class="card text-center">


        <!-- ---------------------------------  -->

        <div class="table-responsive text-nowrap m-3 inline " style="min-height:400px">



            <table class="table" id="tableMain">

                <thead>

                    <tr>

                        <th>NOMBRE PRODUCTO</th>
                        <th>PORCENTAJE</th>

                        <th>COSTO POR LITRO</th>
                        <th>IMPORTE TOTAL</th>
                        <th>INVENTARIO</th>


                        <th>BARRILES PARA PRODUCCIÓN</th>
                        <th>LITROS PARA PRODUCCION</th>

                        <th>AGREGAR</th>
                        <th>ELIMINAR</th>

                    </tr>

                </thead>



                <tbody id="containerTable"></tbody>


                <tfoot></tfoot>

            </table>



        </div>

        <!-- ----------------------------------------  -->
    </div>

    <div class="col-12" style="padding: 1rem 0;"></div>

    <div class="card mb-4">
        <div class="row px-4 py-3 text-center" style="align-items: center;">

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



    <div class="card mb-4">

        <div class="row px-4 py-3" style="align-items: center;">

            <div class="col-4">
            
                <label for="">Margen</label>
                <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                    <input onchange="calculateMargen(this)" onkeyup="calculateMargen(this)"  class="form-control borderRadiusInputLiters" type="text" id="margen_porcentaje">
                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">%</span>
                </div>


            </div>

            <div class="col-4 text-center">
                <em style="display: block;">Precio de venta por litros:</em>
                <strong>
                    <span>$</span>
                    <span id="margen_precioVentaPorLitros"></span>
                </strong>
            </div>
    
            <div class="col-4 text-center">
                <em style="display: block;">Precio de venta por barril:</em>
                <strong>
                    <span>$</span>
                    <span id="margen_precioVentaPorBarril"></span>
                </strong>
            </div>

        </div>


    </div>

</div>




<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>

<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

<?php include '../../requerimientos/headers/footer.php' ?>



<script src="js/cotizador.js"></script>

<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>