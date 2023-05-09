<?php include '../../requerimientos/headers/header.php' ?>


<link rel="stylesheet" href="css/style.css">



<div class="container-fluid flex-grow-1 container-p-y">


    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Cuentas por cobrar /</span> Lista de cuentas por cobrar</h4>
        </div>


        <div class="col-12">
            <div style="display: flex;gap: 1rem;">

                <div class="card" style="width: fit-content;">
                    <div class="card-body" style="width: min-content;">
                        <div class="row" style="    align-items: center;justify-content: center;">
                            <img style="width: 100px; height: 100px;" src="../../requerimientos/imgGeneral/element/pay.svg" alt="chart success" class="rounded">
                        </div>
                        <div class="row">
                            <span class="fw-semibold d-block mb-1">Total: MX</span>
                            <h3 class="card-title mb-2" id="totalMX">Cargando...</h3>
                        </div>
                    </div>
                </div>

                <div class="card" style="width: fit-content;">
                    <div class="card-body" style="width: min-content;">
                        <div class="row" style="    align-items: center;justify-content: center;">
                            <img style="width: 100px; height: 100px;" src="../../requerimientos/imgGeneral/element/pay.svg" alt="chart success" class="rounded">
                        </div>
                        <div class="row">
                            <span class="fw-semibold d-block mb-1">Total: USD</span>
                            <h3 class="card-title mb-2" id="totalUSD">Cargando...</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">

            <div class="card">

                <!-- <h5 class="card-header inline-head"> -->
                <!-- </h5> -->


                <div class="table-responsive text-nowrap m-3 inline">

                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>

                                <!-- <th>Acciones</th> -->

                                <th>Id </br> Cliente</th>
                                <th>Cliente</th>

                                <th>Credito</th>
                                <th>0-15</th>
                                <th>15-30</th>
                                <th>+30</th>
                                <th>Total</th>

                                <!-- <th>Monto </br> Cerrado </br> Pesos</th> -->
                                <!-- <th>Monto </br> Cerrado </br> Dólares</th> -->

                                <!-- <th>No Cobrado </br>Vencido </br> Pesos</th> -->
                                <!-- <th>No Cobrado </br>Vencido </br> Dólares</th> -->

                                <!-- <th>No Cobrado </br>Crédito </br> Pesos</th> -->
                                <!-- <th>No Cobrado </br>Crédito </br> Dólares</th> -->

                            </tr>

                        </thead>

                        <tbody></tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!-- <th></th> -->
                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>


</div>


<!-- -----------------------------------------------------------------------------------------------  -->



<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>



<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/cxc.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>