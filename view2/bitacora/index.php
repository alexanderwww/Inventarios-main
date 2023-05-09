
<?php include '../../requerimientos/headers/header.php' ?>

<!-- <script src="../../jsGeneral/appInt.js"></script> -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- CSS SmartWizard -->
<link href="../../requerimientos/vendors/wizardLibrery/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
<!-- switchery -->
<link rel="stylesheet" href="../../requerimientos/vendors/togle_switchery/dist/switchery.css">


<!-- Page Content including Menu new template -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Bitacora /</span> Lista de Bitacoras</h4>

    <div class="card">

    <h5 class="card-header inline-head">
    <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Bitacora' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">

    </h5>
        
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaBitacora">
                <thead>
                    <tr>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Operacion</th>
                    <th>Modulo</th>
                    <th>Comentarios</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Final Content new template -->
                             

                            
                              
                                <!--------------------------------------->
<!-- ----------------------------------------- -->
  <!-- icheck  -->
    <script src="../../requerimientos/vendors/iCheck/icheck.min.js"></script>

    <!-- <script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script> -->
    <?php include '../../requerimientos/headers/footer.php' ?>
    <script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script>
    <script type="text/javascript" src="../../requerimientos/vendors/wizardLibrery/jquery.smartWizard.min.js"></script>

  
    <script src="js/bitacora.js"></script>
