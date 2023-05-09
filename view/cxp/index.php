<?php include '../../requerimientos/headers/header.php' ?>


<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y">


    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Cuentas por pagar /</span> Lista de Proveedores</h4>
        </div>

        <div class="col-12">
            <div style="display: flex;gap: 1rem;">

                <div class="card" style="width: fit-content;">
                    <div class="card-body" style="width: min-content;min-width: 200px;">
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
                    <div class="card-body" style="width: min-content;min-width: 200px;">
                        <div class="row" style="    align-items: center;justify-content: center;">
                            <img style="width: 100px; height: 100px;" src="../../requerimientos/imgGeneral/element/pay.svg" alt="chart success" class="rounded">
                        </div>
                        <div class="row">
                            <span class="fw-semibold d-block mb-1">Total: USD</span>
                            <h3 class="card-title mb-2" id="totalUSD" >Cargando...</h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">

            <div class="card">

                <div class="table-responsive text-nowrap m-3 inline">

                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>

                                <th>Acción</th>
                                <th>Id</th>

                                <th>Proveedor</th>
                                <th>Vencido Pesos</th>
                                <th>Vencido Dólares</th>

                                <th>Crédito Pesos</th>
                                <th>Crédito Dólares</th>

                            </tr>

                        </thead>

                        <tbody>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>

                                <th></th>
                                <th></th>
                                <th></th>

                                <th></th>
                                <th></th>
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


<script src="js/cxp.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>