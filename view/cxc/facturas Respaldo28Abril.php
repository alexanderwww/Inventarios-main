<?php include '../../requerimientos/headers/header.php' ?>

<link rel="stylesheet" href="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="./css/style.css">


<div class="container-xxl flex-grow-1 container-p-y">


    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Facturas del cliente: <strong id="nameFactura"></strong> </span></h4>
        </div>

        <input type="hidden" id="facturaCliente" value="<?php echo $_GET['id'] ?>">
        
        <input type="hidden" id="facturaName" value="<?php echo $_GET['name'] ?>">

<!--         
        <div class="col-12">
            <div class="card p-3">

                <div class="m-3">

                    <div class="row">

                        <div class="col-2">
                            <div class="row"><br></div>
                            <div class="row">Pesos</div>
                            <div class="row">Dólares</div>
                        </div>

                        <div class="col-10">

                            <div class="row">
                                <div class="col-3">Pendiente</div>
                                <div class="col-3">Gran Total</div>
                                <div class="col-3">Cobrado</div>
                                <div class="col-3">Nota Credito</div>
                            </div>

                            <div class="row">
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                            </div>

                            <div class="row">
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                                <div class="col-3">$1,000.00</div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div> -->

        <div class="col-12">
            <div class="card">

                <h5 class="card-header inline-head">
                </h5>


                <div class="table-responsive text-nowrap m-3 inline">

                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>
                                <!-- <th>Acción</th> -->
                                <th>Estatus</th>
                                <th>Folio Factura/Nota Crédito</th>
                                <th>Fecha de promesa de pago</th>
                                <th>Fecha de inicio de crédito</th>

                                <th>Saldo</th>
                                <th>Totales</th>

                            </tr>

                        </thead>

                        <tbody></tbody>
                        <tfoot>
                            <tr>

                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>
        </div>


    </div>


</div>







<!-- ----------------------------------------------------------------------------->

















<!-- -----------------------------------------------------------------------------------------------  -->






<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/facturas.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
