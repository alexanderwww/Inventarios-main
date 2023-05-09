<?php include '../../requerimientos/headers/header.php' ?>

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">

<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row" style="gap:1rem">

        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Edit Facturación /</span></h4>
        </div>

        <div class="col-12">

            <div class="card">

                <div class="m-3">

                    <form>

                        <div class="row">

                            <div class="col-sm-3">
                                <label class="form-label">Elaborado por</label>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id='user' value='<?php echo $_SESSION['Nombre']; ?>' disabled>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_user" class="form_text_adv"></p>
                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Cliente</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="cliente" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_cliente" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Regimen Fiscal</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="regimenFiscal" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_regimenFiscal" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Folio</label>
                                <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente" id="folio" disabled>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_folio" class="form_text_adv"></p>

                            </div>

                            <!-- --------------------------------------  -->

                            <div class="col-sm-3">
                                <div class="row">

                                    <div class="col-6">

                                        <label class="form-label">Moneda</label>
                                        <div>
                                            <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="moneda" style="width:100%;">
                                                <option value="pesos">Pesos</option>
                                                <option value="dolares">Dolares</option>
                                            </select>
                                        </div>
                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_moneda" class="form_text_adv"></p>

                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Tipo de cambio</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input onkeyup="sobreinput(event);" class="form-control validarDataCliente getDataCliente mask-pesos" type="text" value="<?php echo $_SESSION['TC']; ?>" id="tipoDeCambio">
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoDeCambio" class="form_text_adv"></p>

                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Uso de CFDI</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="usoCFDI" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_usoCFDI" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Metodo de pago</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="metodoDePago" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_metodoDePago" class="form_text_adv"></p>

                            </div>

                            <div class="col-sm-3">
                                <label class="form-label">Forma de pago</label>
                                <div>
                                    <select onchange="sobreSelectData(event)" class="form-control validarDataCliente getDataCliente" id="formaDePago" style="width:100%;"></select>
                                </div>
                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_formaDePago" class="form_text_adv"></p>

                            </div>

                        </div>

                        <div class="row" style="border: 1px solid #efefef;margin: 1rem;"></div>

                        <div class="row">

                            <div class="col-sm-4">
                                <label class="form-label">Total de Devoluciones</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control getDataBalance" id="totalDevoluciones" disabled>

                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">Total de ODVS</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control getDataBalance" id="totalODVS" disabled>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <label class="form-label">Total</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input class="form-control getDataBalance" id="totalBalance" disabled>
                                </div>
                            </div>


                        </div>

                        <div class="row" style="border: 1px solid #efefef;margin: 1rem;"></div>

                        <div class="row">

                            <span class="card-header inline-head">
                                CFDI Relacionados
                            </span>


                            <div class="row" style="gap:1rem">

                                <div class="col">
                                    <div>
                                        <select class="form-control" id="CFDIrelacionado" autocomplete="off" style="width:100%;"></select>
                                    </div>
                                </div>

                                <div class="col">

                                    <input type="button" class="btn btn-primary  text-white" id="insertCFDI" value="Nuevo CFDI">

                                </div>


                                <div class="col-12 ">

                                    <div class="row">
                                        <div class="col">Folio Factura</div>
                                        <div class="col">Fecha</div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="row" id="container_CFDI" style="gap:1rem"></div>

                                </div>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- getData reaonly -->
        <!-- <div class="col-12">

            <div class="card">

                <div class="m-3">


                    <div class="row">

                        <div class="col-sm-4">
                            <label class="form-label">Total de Devoluciones</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalance" id="totalDevoluciones" disabled>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label">Total de ODVS</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalance" id="totalODVS" disabled>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalance" id="totalBalance" disabled>
                            </div>
                        </div>


                    </div>


                </div>

            </div>

        </div> -->

        <!-- Dinamico ? validar  -->
        <!-- <div class="col-12">
            <div class="card">

                <div class="m-3">

                    <div class="row">

                        <span class="card-header inline-head">
                            CFDI Relacionados
                        </span>


                        <div class="row" style="gap:1rem">

                            <div class="col">
                                <div>
                                    <select class="form-control" id="CFDIrelacionado" autocomplete="off" style="width:100%;"></select>
                                </div>
                            </div>

                            <div class="col">

                                <input type="button" class="btn btn-primary  text-white" id="insertCFDI" value="Nuevo CFDI">

                            </div>


                            <div class="col-12 ">

                                <div class="row">
                                    <div class="col">Folio Factura</div>
                                    <div class="col">Fecha</div>
                                    <div class="col"></div>
                                </div>
                                <div class="row" id="container_CFDI" style="gap:1rem"></div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div> -->

        <div class="col-12">

            <div class="card">

                <h5 class="card-header inline-head">

                    <input type="button" class="btn btn-primary  text-white" id="insertConcepto" value="Nuevo concepto">
                </h5>

                <div class="table-responsive text-nowrap m-3 inline">



                    <table class="table" id="tablaPrincipal">

                        <thead>

                            <tr>
                                <th>Acciones</th>
                                <th class="minWidth">Odv</th>
                                <th class="minWidth">Código</th>
                                <th class="minWidth">Clave ProdServ</th>
                                <th class="minWidth">Descripción</th>
                                <th class="minWidth">Cantidad</th>
                                <th class="minWidth">Unidad</th>
                                <th class="minWidth">Precio</th>
                                <th class="minWidth">Total</th>
                                <th class="minWidth">Impuesto</th>
                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <!-- Validar caracteres balance  -->
        <div class="col-12">

            <div class="card">

                <div class="m-3">


                    <div class="row">


                        <div class="col-12">
                            <label class="form-label">Observaciones</label>
                            <input class="form-control getDataBalanceConceptos" id='balanceObservaciones'>
                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_observaciones" class="form_text_adv"></p>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Subtotal</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalanceConceptos" id="balanceSubtotal" disabled>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Impuesto Retenido</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalanceConceptos" id="balanceImpuestosRetenidos" disabled>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Impuesto Trasladado</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalanceConceptos" id="balanceImpuestoTrasladado" disabled>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label class="form-label">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input class="form-control getDataBalanceConceptos" id="balanceTotal" disabled>
                            </div>
                        </div>

                    </div>


                </div>

            </div>

        </div>

        <div class="col-12">

            <div class="d-flex justify-content-end">
                <input type="button" class="btn btn-primary  text-white" id="insertFactura" value="Generar Factura">
            </div>

        </div>

    </div>

</div>


<!-- -----------------------------------------------------------------------------------------------  -->


<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/altaFacturacion.js"></script>






<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../requerimientos/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>


<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>


