<?php include '../../requerimientos/headers/header.php' ?>

<link rel="stylesheet" href="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.css">
<!-- <link rel="stylesheet" href="../../requerimientos/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css"> -->

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">


<link rel="stylesheet" href="css/style.css">




<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Nuevo Complemento de Pago</span></h4>
        </div>

        <!-- Validado y getData, Sobre Input y select  -->
        <div class="col-12">

            <div class="card">

                <div class="m-3">

                    <form>

                        <div class="row">

                            <div class="col-sm-3">
                                <label class="form-label">Elaborado por</label>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id='user' value='<?php echo $_SESSION['Nombre']; ?>' disabled>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id='idUser' value='<?php echo $_SESSION['IdUser']; ?>' type="hidden">
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_user" class="form_text_adv"></p>
                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Forma de pago</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="formaDePago" autocomplete="off" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_formaDePago" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-6">
                                <label class="form-label">Fecha de pago</label>
                                <input onkeyup="sobreinput(event);" class="form-control getDataCliente" id="fechaPago" type="text">
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_fechaPago" class="form_text_adv"></p>

                            </div>


                            <!-- <div class="col-sm-3">
                                <label class="form-label">Hora de pago</label>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id="horaPago">
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_horaPago" class="form_text_adv"></p>

                            </div> -->

                            <!-- --------------------------------------  -->
                        </div>

                        <!-- NOTA CAMBIO 999 EN COMENTARIO CAMPO Y CAMBIO CLASES COLUMN 3 A 4 BOOSTRAP -->

                        <div class="row">

                            <div class="col-sm-4">

                                <label class="form-label">Moneda</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="moneda" autocomplete="off" style="width:100%;">
                                        <option value="pesos">Pesos</option>
                                        <option value="dolares">Dolares</option>
                                    </select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_moneda" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">Tipo de cambio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente mask-pesos" type="text" value="<?php echo $_SESSION['TC']; ?>" id="tipoDeCambio">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoDeCambio" class="form_text_adv"></p>

                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">Referencia</label>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id="referencia">
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_referencia" class="form_text_adv"></p>

                            </div>

                            <!-- <div class="col-sm-3">
                                <label class="form-label">Financiera</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="financiera" autocomplete="off" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_financiera" class="form_text_adv"></p>

                            </div> -->


                        </div>

                    </form>

                </div>

            </div>

        </div>


        <div class="col-12">

            <div class="card">

                <h5 class="card-header inline-head">

                    <input type="button" class="btn btn-primary  text-white" id="insertNewRow" value="Nuevo Partida">
                </h5>

                <div class="table-responsive text-nowrap m-3 inline">

                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>
                                <th>Acciones</th>
                                <th class="minWidth">Folio factura</th>
                                <th class="minWidth">Fecha</th>
                                <th class="minWidth">Total</th>
                                <th class="minWidth">Saldo</th>
                                <th class="minWidth">Moneda</th>
                                <th class="minWidth">No. parcialidad</th>
                                <th class="minWidth">Monto de pago</th>
                            </tr>

                        </thead>

                        <tbody>
                        </tbody>


                    </table>

                </div>

                <div class="m-3">
                    <div class="col-4"></div>
                    <div class="col-4"></div>
                    <div class="col-4">

                    <div class="">
                        <label class="form-label">Total</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input onkeyup="sobreinput(event);" class="form-control getDataCliente   mask-pesos" type="text" id="totalBalance" disabled>
                        </div>
                    </div>

                    </div>

                </div>

            </div>

        </div>





        <div class="col-12">

            <div class="d-flex justify-content-end">
                <input type="button" class="btn btn-primary  text-white" id="insertFactura" value="Generar Complemento de pago">
            </div>

        </div>

    </div>

</div>


<!-- -----------------------------------------------------------------------------------------------  -->


<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/altaCp.js"></script>


<!-- <script src="../../requerimientos/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script> -->
<script src="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- <script src="../../requerimientos/vendors/moment/min/moment.min.js"></script> -->


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>


<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>