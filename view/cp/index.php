<?php include '../../requerimientos/headers/header.php' ?>


<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Complemento de pago /</span> Lista de Complemento de pago</h4>



    <div class="card">

        <h5 class="card-header inline-head">

            <?php if ($_SESSION['Rol']['odtC'] == 1) : ?>

                <a href="./alta.php" class="btn btn-primary derecha">Nuevo CP <i class='bx bx-plus'></i></a>

            <?php endif ?>

        </h5>

        <div class="table-responsive text-nowrap m-3 inline">



            <table class="table" id="tablaPrincipal">

                <thead>

                    <tr>

                        <th>Acciones</th>
                        <th>Folio</th>
                        <th>Generado Por</th>
                        <th>Cliente</th>
                        <!-- <th>Financiera</th> -->
                        <th>Importe</th>
                        <th>Moneda</th>
                        <th>Fecha</th>
                        <th>Fecha Depósito</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>

    </div>



    <!-- ----------------------------------------------------------------------------->





</div>





<!-- --------------------------------------------------------------------------------------------  -->



<!-- -----------------------------------------------------------------------------------------------  -->






<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/cp.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>