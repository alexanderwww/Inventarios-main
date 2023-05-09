<?php include '../../requerimientos/headers/header.php' ?>

<link rel="stylesheet" href="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="./css/style.css">

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Recepción de Gastos <strong></strong> </span></h4>
        </div>




        <div class="col-12">
            <div class="card">

                <h5 class="card-header inline-head">
                </h5>


                <div class="table-responsive text-nowrap m-3 inline">

                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>
                                <th>Acción</th>

                                <th>Folio</th>
                                <th>Uuid</th>

                                <th>Emisor RFC</th>
                                <th>Emisor Nombre</th>

                                <!-- <th>ConceptoESTA YA NO VA</th> YA desplegas los conceptos -->

                                <th>SubTotal</th>
                                <th>IVA</th>

                                <th>Total</th>
                                <th>Moneda</th>

                                <th>Nota de crédito</th>
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


<script src="js/gastos.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>