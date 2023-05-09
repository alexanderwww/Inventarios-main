<?php include '../../requerimientos/headers/header.php' ?>

<!-- DatePicker  -->
<link rel="stylesheet" href="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.css">


<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row" style="gap:1rem">


        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Listado de Facturas Por Pagar: <strong id="nameProveedor"></strong> </span></h4>
        </div>

        <input type="hidden" id="facturaProveedor" value="<?php echo $_GET['id'] ?>">
        <input type="hidden" id="facturaName" value="<?php echo $_GET['name'] ?>">


        <!-- <div class="col-12">
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
                                <th>Aplicar Pago</th>
                                <th>Estatus</th>
                                <th>Folio Factura/Nota Crédito</th>
                                <th>Total</th>
                                <th>Saldo</th>
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
<!-- ------------------------------------------------------- Modal Edit -->


<div class="modal fade" id="modalPago" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"> Factura: <strong id="modalPagoTitle"></strong></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12 col-sm-12  ">

                        <form id="formEdit">

                            <!-- * -->

                            <!-- ------------------------------  -->

                            <div class="container">

                                <div class="row mb-3">

                                    <div class="divider divider-Dotted">

                                        <div class="divider-text"><span><strong>Datos Factura</strong></span></div>

                                    </div>
                                    <!-- ---------------------------  -->

                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Emisor</label>

                                            <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Emisor_alta" disabled>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Emisor_alta" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Folio</label>

                                            <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Folio_alta" disabled>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Folio_alta" class="form_text_adv"></p>

                                        </div>



                                    </div>

                                    <!-- ---------------------------  -->

                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Fecha Factura</label>

                                            <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Fecha_alta" disabled>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Fecha_alta" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Moneda </label>

                                            <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Moneda_alta" disabled>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Moneda_alta" class="form_text_adv"></p>

                                        </div>

                                    </div>
                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Total</label>
                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control   stringClass trim" type="text" id="Total_alta" disabled>

                                            </div>

                                        </div>

                                    </div>
                                    <!-- ---------------------------  -->

                                </div>

                            </div>

                            <!-- -------------------------------------------------  -->

                            <div class="divider divider-Dotted">

                                <div class="divider-text"><span><strong>Datos Pago</strong></span></div>

                            </div>
                            <div class="containerItems_edit" id="formItems_edit">

                                <div id="itemsSecundarios_edit"></div>

                                <div id="itemsPrincipal_edit"></div>

                            </div>

                            <!-- -------------------------------------------------  -->

                            <div class="container">
                                <div class="row mb-3">

                                    <div class="col-6">

                                        <label>Tipo de Pago</label>

                                        <select onchange="sobreSelectData(event)" class=" form-control tipoPago_alta" id='tipoPago_alta'></select>
                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoPago_alta" class="form_text_adv"></p>

                                    </div>

                                    <div class="col-6">

                                        <label>Metodo de Pago</label>

                                        <select onchange="sobreSelectData(event)" class=" form-control metodoPago_alta" id='metodoPago_alta'></select>
                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_metodoPago_alta" class="form_text_adv"></p>

                                    </div>
                                </div>
                                <!-- ---------------------------  -->

                                <div class="row mb-3">


                                    <div class="col-4">

                                        <div class="my-3">

                                            <label>SubTotal *</label>

                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control   stringClass trim" type="text" id="SubTotal_edit" disabled>

                                            </div>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_SubTotal_edit" class="form_text_adv"></p>

                                        </div>

                                    </div>

                                    <!-- ---------------------------  -->

                                    <div class="col-4">

                                        <div class="my-3">

                                            <label>Iva *</label>

                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control   stringClass trim" type="text" id="Iva_edit" disabled>

                                            </div>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Iva_edit" class="form_text_adv"></p>

                                        </div>

                                    </div>

                                    <!-- ---------------------------  -->

                                    <div class="col-4">

                                        <div class="my-3">

                                            <label>Total *</label>

                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control   stringClass trim" type="text" id="Total_edit" disabled>

                                            </div>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Total_edit" class="form_text_adv"></p>

                                        </div>

                                    </div>

                                    <!-- ---------------------------  -->

                                </div>



                            </div>

                            <!-- ------------------------------  -->

                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary btnAceptarPago">Aceptar</button>

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>





<!-- ----------------------------------------------------------------------------->





<div class="modal fade" id="modalAlta" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">



            <div class="modal-header">

                <h5 class="modal-title">Complemento de pago : <strong id='modalAltaTitle'></strong></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>

            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-md-12 col-sm-12  ">

                        <form id="formAlta">


                            <div class="container">



                                <div class="row mb-3">

                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Emisor</label>
                                            <input onkeyup="sobreinput(event);" class="form-control validarMetodoPago getDataMetodoDePago stringClass trim" type="text" id="emisor" disabled>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_emisor" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Total</label>
                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control validarMetodoPago getDataMetodoDePago" type="text" id="total" disabled>
                                            </div>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_total" class="form_text_adv"></p>
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Folio Factura</label>
                                            <input onkeyup="sobreinput(event);" class="form-control validarMetodoPago getDataMetodoDePago" type="text" id="folioFactura" disabled>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_folioFactura" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Moneda</label>
                                            <input onkeyup="sobreinput(event);" class="form-control validarMetodoPago getDataMetodoDePago" type="text" id="moneda" disabled>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_moneda" class="form_text_adv"></p>

                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <div class="my-3">

                                            <label>Fecha factura</label>
                                            <input onkeyup="sobreinput(event);" class="form-control getDataMetodoDePago" type="text" id="fechaFactura" disabled>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_fechaFactura" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Metodo de pago</label>
                                            <div>
                                                <select onchange="sobreSelectData(event)" class="form-control validarMetodoPago getDataMetodoDePago" id="metodoPago" style="width:100%;"></select>
                                            </div>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_metodoPago" class="form_text_adv"></p>

                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="my-3">

                                            <label>Tipo de pago</label>
                                            <div>
                                                <select onchange="sobreSelectData(event)" class="form-control validarMetodoPago getDataMetodoDePago" id="tipoDePago" style="width:100%;"></select>
                                            </div>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoDePago" class="form_text_adv"></p>

                                        </div>

                                        <div class="my-3">

                                            <label>Fecha de pago</label>
                                            <input onkeyup="sobreinput(event);" class="form-control getDataMetodoDePago" type="text" id="fechaDePago">
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_fechaDePago" class="form_text_adv"></p>

                                        </div>
                                    </div>


                                    <div class="col-12">

                                        <label>Importe de pago</label>
                                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                            <!--  -->
                                            <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control validarMetodoPago getDataMetodoDePago" type="text" id="importeDePago">
                                        </div>

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_importeDePago" class="form_text_adv"></p>
                                    </div>

                                    <div class="col-12">


                                        <div class="row" id="containerMetodoDePago">

                                        </div>


                                    </div>

                                    <!-- ---------------------------  -->

                                </div>

                            </div>

                            <!-- ------------------------------  -->



                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary btnAceptarAlta">Aplicar</button>

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                            </div>

                        </form>



                    </div>

                </div>



            </div>



        </div>

    </div>

</div>








<!-- -----------------------------------------------------------------------------------------------  -->






<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>

<!-- DatePicker -->
<script src="../../requerimientos/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


<script src="js/facturas.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>