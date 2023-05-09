<?php include '../../requerimientos/headers/header.php' ?>

<link href="css/style.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<link href="../../requerimientos/vendors/wizardLibrery/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

<!-- switchery -->
<link rel="stylesheet" href="../../requerimientos/vendors/togle_switchery/dist/switchery.css">
<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Proveedores /</span> Lista de proveedores</h4>

    <div class="card">
        
        <h5 class="card-header inline-head">
            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Proveedores' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">
            <?php if($_SESSION['Rol']['proveedorC'] == 1):?>
            <button type="button" class="btn btn-primary derecha btnShowModalAlta">Proveedor <i class='bx bxs-user-plus'></i></button>
            <?php endif ?>
            <!-- <button type="button" class="btn rounded-pill btn-primary">Proveedor <i class='bx bxs-user-plus'></i></button> -->
        </h5>
        
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tProveedores">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Id</th>
                        <th>Status</th>
                        <th>Nombre</th>
                        <th>Razón Social</th>
                        <th>RFC</th>
                        <th>Contacto</th>
                        <th>Teléfono</th>
                        <th>Ext</th>
                        <th>Dirección</th>
                        <th>Código Postal</th>
                        <th>Días crédito</th>
                        <th>Número de cuenta</th>
                        <th>Moneda</th>
                    </tr>
                </thead>
                <tbody></tbody>

            </table>
        </div>
    </div>
</div>
<!-- Extra Large Modal -->

<div class="modal fade" tabindex="-1" id="modalAltaProvedor" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- ----------------------------------  -->
            <div class="modal-header">

                <h5 class="modal-title">Nuevo Proveedor<b id='nameProvedorModalAlta'></b></h5>

                <span id="sw-current-step1" style="display: none;"></span>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <!-- <span aria-hidden="true">&times;</span> -->

                </button>

            </div>
            <!-- ----------------------------------  -->
            <div class="modal-body">
                <!-- -----------------------------  -->
                <div class="row">
                    <div class="col-md-12 col-sm-12  ">
                        <form id="formAltaProvedor" method="POST" class="" enctype="multipart/form-data">



                            <div id="smartwizard">



                                <ul class="nav">

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-1">

                                            <div class="num">1</div>

                                            Datos Generales

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-2">

                                            <span class="num">2</span>

                                            Datos Administrativos

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-3">

                                            <span class="num">3</span>

                                            Datos de Contacto

                                        </a>

                                    </li>
                                </ul>



                                <div class="tab-content" id='ziseContentForm'>

                                    <!-- --------------------------------------  -->

                                    <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">



                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="nombreComercialAlta">Nombre Comercial *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_nombreComercialAlta form-control campoValidarAltaProvedor_Sectio1 formAltaProvedorDataInput trim" type="text" id="nombreComercialAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre comercial">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_nombreComercialAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="razonSocialAlta">Razón Social *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_razonSocialAlta form-control campoValidarAltaProvedor_Sectio1 formAltaProvedorDataInput trim" type="text" id="razonSocialAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Razón social">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_razonSocialAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">



                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="rfcAlta">RFC *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_rfcAlta form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput upperTrim" type="text" id="rfcAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="RFC">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_rfcAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="calleYNumeroAlta">Calle y Número *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_calleYNumeroAlta form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput trim" type="text" id="calleYNumeroAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Calle y Número">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_calleYNumeroAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="coloniaAlta">Colonia *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_coloniaAlta form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput trim" type="text" id="coloniaAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Colonia">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_coloniaAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <!-- -----------------------------  -->





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="noCuentaProvedorAlta">No de cuenta banco</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_noCuentaProvedorAlta form-control campoValidarCaracteresAlta formAltaProvedorDataInput trim" type="text" id="noCuentaProvedorAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="No Cuenta">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_noCuentaProvedorAlta" class="form_text_adv formNoReq_text_adv"></p>

                                            </div>

                                        </div>





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="MonedaBckupAltaProvedor">Moneda *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_MonedaBckupAltaProvedor form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput" id="MonedaBckupAltaProvedor" autocomplete="off" style="width:100%;">

                                                    <option value="">Seleccione uno...</option>

                                                    <option value="Dolares">Dolares</option>

                                                    <option value="Pesos">Pesos</option>

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_MonedaBckupAltaProvedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>



                                        <!-- -------------------------------------  -->





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="paisSelectAlta">País *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_pais form-control campoValidarAltaProvedor_Sectio2 selectChosenAlta formAltaProvedorDataInput" id="paisSelectAlta" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_paisSelectAlta" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="estadoSelectAlta">Estado *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_estado form-control campoValidarAltaProvedor_Sectio2 selectChosenAlta formAltaProvedorDataInput" id="estadoSelectAlta" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_estadoSelectAlta" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="ciudadSelectAlta">Ciudad *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_ciudad form-control campoValidarAltaProvedor_Sectio2 selectChosenAlta formAltaProvedorDataInput" id="ciudadSelectAlta" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ciudadSelectAlta" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="codigoPostalAlta">Código Postal *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_codigoPostalAlta form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput trim" type="number" id="codigoPostalAlta" placeholder="Código Postal">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_codigoPostalAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="diasCreditoAlta">Días de Crédito *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_diasCreditoAlta form-control campoValidarAltaProvedor_Sectio2 formAltaProvedorDataInput trim" type="number" id="diasCreditoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Días de Crédito">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_diasCreditoAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">



                                        <!-- -------------------------------------------------------  -->





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="contactoPrincipalAlta">Contacto Principal</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_contactoPrincipalAlta form-control formAltaProvedorDataInput campoValidarCaracteresAlta trim" type="text" id="contactoPrincipalAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre y Apellido del contacto principal">



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_contactoPrincipalAlta" class="form_text_adv formNoReq_text_adv"></p>



                                            </div>

                                        </div>







                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="correoElectronicoAlta">Correo Electrónico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_correoElectronicoAlta form-control formAltaProvedorDataInput validarCorreosNoObligatorios trim" type="email" id="correoElectronicoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="ejemplo@dominio.com">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_correoElectronicoAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <!-- -------------  -->





                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="numeroTelefonoAlta">Número teléfonico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_numeroTelefonoAlta form-control formAltaProvedorDataInput campoValidarCaracteresAlta trim" type="text" id="numeroTelefonoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="" data-inputmask="'mask' : '(999) 999-9999'">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_numeroTelefonoAlta" class="form_text_adv"></p>

                                            </div>

                                        </div>



                                        <div class="field item form-group">
                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="extensionProvedorAlta">Extensión</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input autocomplete="off" class="form_extensionProvedorAlta form-control formAltaProvedorDataInput campoValidarCaracteresAlta trim" type="text" id="extensionProvedorAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Ext">
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_extensionProvedorAlta" class="form_text_adv formNoReq_text_adv "></p>
                                            </div>
                                        </div>
                                        <!-- -----------------------------------------------------  -->
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ----------------------------------  -->
            </div>
            <!-- ----------------------------------  -->
            <div class="modal-footer">
                <button class="btn btn-primary" id="prev-btn-modal1">Anterior</button>
                <button class="btn btn-primary" id="next-btn-modal1">Siguiente</button>
                <button class="btn btn-success btnWizarconfirmarDisponible1" id='btnModalConfirmarAltaProvedor'>Confirmar</button>

                <button class="btn btn-outline-secondary" id='btnModalCancelarAltaProvedor' data-dismiss="modal">Cancelar</button>

            </div>
            <!-- -----------------------------  -->
        </div>
    </div>
</div>
 <!-- ------------------------------------------------------- Modal Deshabilitar -->


 <div class="modal fade" tabindex="-1" id="modalDeshabilitar" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deshabilitar: <b id="modalDeshabilitarTitle"></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>
                <div class="modal-body">
                    <!-- ---------------------------------  -->

                    <h4>¿Desea deshabilitar Proveedor? </h4>
                    <!-- ------------------------------------  -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btnAceptarDeshabilitar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                </div>
            </div>
        </div>
    </div>


    <!-- ---------------------------------------------------------------------------Habilitar  -->

    <div class="modal fade" tabindex="-1" id="modalHabilitar" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Habilitar: <b id="modalHabilitarTitle"></b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>
                <div class="modal-body">
                    <!-- ---------------------------------  -->

                    <h4>¿Desea Habilitar Proveedor? </h4>
                    <!-- ------------------------------------  -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btnAceptarHabilitar">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- ----------------------------------------------------------------------------->
<!--------------------------------------------------------------------- Modal Update Proveedor  -->



<div class="modal fade" tabindex="-1" id="modalEditarProvedor" role="dialog" aria-hidden="true">



    <div class="modal-dialog modal-lg" role="document">



        <div class="modal-content">



            <!-- ----------------------------------  -->

            <div class="modal-header">

                <h5 class="modal-title">Editar Proveedor: <b id='nameProvedorModalEditar'></b></h5>

                <span id="sw-current-step2" style="display: none;"></span>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <!-- <span aria-hidden="true">&times;</span> -->

                </button>

            </div>

            <!-- ----------------------------------  -->

            <div class="modal-body">

                <!-- -----------------------------  -->

                <div class="row">

                    <div class="col-md-12 col-sm-12  ">

                        <form id="formEditarProvedor" method="POST" class="" enctype="multipart/form-data">

                            <div id="smartwizard2">

                                <ul class="nav">

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-10">

                                            <div class="num">1</div>

                                            Datos Generales

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-20">

                                            <span class="num">2</span>

                                            Datos Administrativos

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-30">

                                            <span class="num">3</span>

                                            Datos de Contacto

                                        </a>

                                    </li>

                                </ul>

                                <div class="tab-content" id='ziseContentForm2'>

                                    <!-- --------------------------------------  -->

                                    <div id="step-10" class="tab-pane" role="tabpanel" aria-labelledby="step-10">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="NombreCom">Nombre Comercial *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_NombreCom form-control campoValidarEditarProvedor_Sectio1 formEditarProvedorDataInput formEditarProvedorDataInput2 dataInputEditarProvedor trim" type="text" id="NombreCom" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre comercial">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NombreCom" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <!-- ------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="RazonSocial">Razón Social *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_RazonSocial form-control campoValidarEditarProvedor_Sectio1 formEditarProvedorDataInput dataInputEditarProvedor trim" type="text" id="RazonSocial" pattern="[A-Za-z0-9]{1,30}" placeholder="Razón social">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RazonSocial" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-20" class="tab-pane" role="tabpanel" aria-labelledby="step-20">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="RFC">RFC *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_RFC form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor upperTrim" type="text" id="RFC" pattern="[A-Za-z0-9]{1,30}" placeholder="RFC">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RFC" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CalleProveedor">Calle y Número *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CalleProveedor form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor trim" type="text" id="CalleProveedor" pattern="[A-Za-z0-9]{1,30}" placeholder="Calle y Número">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CalleProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="ColoniaProveedor">Colonia *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_ColoniaProveedor form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor trim" type="text" id="ColoniaProveedor" pattern="[A-Za-z0-9]{1,30}" placeholder="Colonia">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ColoniaProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <!-- -----------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="NoCuenta">No de cuenta banco</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_NoCuenta form-control  formEditarProvedorDataInput dataInputEditarProvedor campoValidarCaracteresEditar trim" type="text" id="NoCuenta" pattern="[A-Za-z0-9]{1,30}" placeholder="No Cuenta">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NoCuenta" class="form_text_adv formNoReq_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="MonedaBckup">Moneda *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_MonedaBckup form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor" id="MonedaBckup" autocomplete="off" style="width:100%;">

                                                    <option value="">Seleccione uno...</option>

                                                    <option value="Dolares">Dolares</option>

                                                    <option value="Pesos">Pesos</option>

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_MonedaBckup" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <!-- -------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="PaisProveedor">País *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_PaisProveedor form-control campoValidarEditarProvedor_Sectio2 selectChosenEdit formEditarProvedorDataInput dataSelectEditarProvedor " id="PaisProveedor" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_PaisProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="EstadoProveedor">Estado *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_EstadoProveedor form-control campoValidarEditarProvedor_Sectio2 selectChosenEdit formEditarProvedorDataInput dataSelectEditarProvedor" id="EstadoProveedor" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_EstadoProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CiudadProveedor">Ciudad *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_CiudadProveedor form-control campoValidarEditarProvedor_Sectio2 selectChosenEdit formEditarProvedorDataInput dataSelectEditarProvedor" id="CiudadProveedor" autocomplete="off" style="width:100%;">

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CiudadProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CPProveedor">Código Postal *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CPProveedor form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor trim" type="number" id="CPProveedor" placeholder="Código Postal">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CPProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="DiasCredito">Días de Crédito *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_DiasCredito form-control campoValidarEditarProvedor_Sectio2 formEditarProvedorDataInput dataInputEditarProvedor trim" type="number" id="DiasCredito" pattern="[A-Za-z0-9]{1,30}" placeholder="Días de Crédito">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_DiasCredito" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-30" class="tab-pane" role="tabpanel" aria-labelledby="step-30">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="ContactoPrincipal">Contacto Principal</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_ContactoPrincipal form-control formEditarProvedorDataInput dataInputEditarProvedor campoValidarCaracteresEditar trim" type="text" id="ContactoPrincipal" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre y Apellido del contacto principal">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ContactoPrincipal" class="form_text_adv formNoReq_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CorreoElectronico">Correo Electrónico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CorreoElectronico form-control formEditarProvedorDataInput dataInputEditarProvedor validarCorreosNoObligatorios trim" type="email" id="CorreoElectronico" pattern="[A-Za-z0-9]{1,30}" placeholder="ejemplo@dominio.com">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CorreoElectronico" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Telefono">Número Teléfonico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_Telefono form-control formEditarProvedorDataInput dataInputEditarProvedor campoValidarCaracteresEditar trim" type="text" id="Telefono" pattern="[A-Za-z0-9]{1,30}" placeholder="" data-inputmask="'mask' : '(999) 999-9999'">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Telefono" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Ext">Extensión</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_Ext form-control formEditarProvedorDataInput dataInputEditarProvedor campoValidarCaracteresEditar trim" type="text" id="Ext" pattern="[A-Za-z0-9]{1,30}" placeholder="Ext">

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ext" class="form_text_adv formNoReq_text_adv "></p>

                                            </div>

                                        </div>

                                        <!-- -----------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Status">Status</label>

                                            <div class="col-md-6 col-sm-6">

                                                <!-- <input type="checkbox" class="js-switch-status form-control  js-check-change formEditarProvedorDataInput dataCheckboxEditar" id="Status" /> -->
                                                <input type="checkbox" class="form-check-input formEditarProvedorDataInput dataCheckboxEditar" id='Status' >

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ext" class="form_text_adv formNoReq_text_adv "></p>

                                            </div>

                                        </div>
                                        <!-- -----------------------------------------------  -->

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                <!-- ----------------------------------  -->
            </div>

            <!-- ----------------------------------  -->

            <div class="modal-footer">

                <button class="btn btn-primary" id="prev-btn-modal2">Anterior</button>
                <button class="btn btn-primary" id="next-btn-modal2">Siguiente</button>
                <button class="btn btn-success btnWizarconfirmarDisponible2" id='btnModalConfirmarEditarProvedor'>Actualizar</button>
                <button class="btn btn-secondary" id='btnModalCancelarEditarProvedor'>Cancelar</button>

            </div>

            <!-- -----------------------------  -->

        </div>

    </div>

</div>
<!--------------------------------------------------------------------- Modal Update View  -->



<div class="modal fade" tabindex="-1" id="modalViewProvedor" role="dialog" aria-hidden="true">



    <div class="modal-dialog modal-lg" role="document">



        <div class="modal-content">



            <!-- ----------------------------------  -->

            <div class="modal-header">

                <h5 class="modal-title">View Proveedor: <b id='nameProvedorModalView'></b></h5>

                <span id="sw-current-step2" style="display: none;"></span>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    <!-- <span aria-hidden="true">&times;</span> -->

                </button>

            </div>

            <!-- ----------------------------------  -->

            <div class="modal-body">

                <!-- -----------------------------  -->

                <div class="row">

                    <div class="col-md-12 col-sm-12  ">

                        <form id="formViewProvedor" method="POST" class="" enctype="multipart/form-data">

                            <div id="smartwizard3">

                                <ul class="nav">

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-10">

                                            <div class="num">1</div>

                                            Datos Generales

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-20">

                                            <span class="num">2</span>

                                            Datos Administrativos

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a class="nav-link" href="#step-30">

                                            <span class="num">3</span>

                                            Datos de Contacto

                                        </a>

                                    </li>

                                </ul>

                                <div class="tab-content" id='ziseContentForm2'>

                                    <!-- --------------------------------------  -->

                                    <div id="step-10" class="tab-pane" role="tabpanel" aria-labelledby="step-10">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="NombreCom">Nombre Comercial </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_NombreComView form-control campoValidarViewProvedor_Sectio1 formViewProvedorDataInput formViewProvedorDataInput2 dataInputViewProvedor trim" type="text" id="NombreComView" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre comercial" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NombreCom" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <!-- ------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="RazonSocial">Razón Social *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_RazonSocial form-control campoValidarViewProvedor_Sectio1 formViewProvedorDataInput dataInputViewProvedor trim" type="text" id="RazonSocial" pattern="[A-Za-z0-9]{1,30}" placeholder="Razón social" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RazonSocial" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-20" class="tab-pane" role="tabpanel" aria-labelledby="step-20">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="RFC">RFC *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_RFC form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor upperTrim" type="text" id="RFC" pattern="[A-Za-z0-9]{1,30}" placeholder="RFC" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RFC" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CalleProveedor">Calle y Número *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CalleProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor trim" type="text" id="CalleProveedor" pattern="[A-Za-z0-9]{1,30}" placeholder="Calle y Número" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CalleProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="ColoniaProveedor">Colonia *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_ColoniaProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor trim" type="text" id="ColoniaProveedor" pattern="[A-Za-z0-9]{1,30}" placeholder="Colonia" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ColoniaProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <!-- -----------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="NoCuenta">No de cuenta banco</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_NoCuenta form-control  formViewProvedorDataInput dataInputViewProvedor campoValidarCaracteresView trim" type="text" id="NoCuenta" pattern="[A-Za-z0-9]{1,30}" placeholder="No Cuenta" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NoCuenta" class="form_text_adv formNoReq_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="MonedaBckup">Moneda *</label>

                                            <div class="col-md-6 col-sm-6">

                                                <select type='select' class="form_MonedaBckup form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor" id="MonedaBckup" autocomplete="off" style="width:100%;" readonly>

                                                    <option value="">Seleccione uno...</option>

                                                    <option value="Dolares">Dolares</option>

                                                    <option value="Pesos">Pesos</option>

                                                </select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_MonedaBckup" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <!-- -------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="PaisProveedor">País </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input type='input' class="form_PaisProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor " id="namePais" autocomplete="off" style="width:100%;" readonly>

                                                </input>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_PaisProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="EstadoProveedor">Estado </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input type='input' class="form_EstadoProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor" id="nameEstado" autocomplete="off" style="width:100%;" readonly>

                                                </input>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_EstadoProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CiudadProveedor">Ciudad </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input type='input' class="form_CiudadProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor" id="nameCiudad" autocomplete="off" style="width:100%;" readonly>

                                                </input>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CiudadProveedor" class="form_text_adv">

                                                </p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CPProveedor">Código Postal </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CPProveedor form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor trim" type="number" id="CPProveedor" placeholder="Código Postal" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CPProveedor" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="DiasCredito">Días de Crédito </label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_DiasCredito form-control campoValidarViewProvedor_Sectio2 formViewProvedorDataInput dataInputViewProvedor trim" type="number" id="DiasCredito" pattern="[A-Za-z0-9]{1,30}" placeholder="Días de Crédito" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_DiasCredito" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- --------------------------------------  -->

                                    <div id="step-30" class="tab-pane" role="tabpanel" aria-labelledby="step-30">

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="ContactoPrincipal">Contacto Principal</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_ContactoPrincipal form-control formViewProvedorDataInput dataInputViewProvedor campoValidarCaracteresView trim" type="text" id="ContactoPrincipal" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre y Apellido del contacto principal" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ContactoPrincipal" class="form_text_adv formNoReq_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="CorreoElectronico">Correo Electrónico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_CorreoElectronico form-control formViewProvedorDataInput dataInputViewProvedor validarCorreosNoObligatorios trim" type="email" id="CorreoElectronico" pattern="[A-Za-z0-9]{1,30}" placeholder="ejemplo@dominio.com" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CorreoElectronico" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Telefono">Número Teléfonico</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_Telefono form-control formViewProvedorDataInput dataInputViewProvedor campoValidarCaracteresView trim" type="text" id="Telefono" pattern="[A-Za-z0-9]{1,30}" placeholder="" data-inputmask="'mask' : '(999) 999-9999'" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Telefono" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Ext">Extensión</label>

                                            <div class="col-md-6 col-sm-6">

                                                <input autocomplete="off" class="form_Ext form-control formViewProvedorDataInput dataInputViewProvedor campoValidarCaracteresView trim" type="text" id="Ext" pattern="[A-Za-z0-9]{1,30}" placeholder="Ext" readonly>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ext" class="form_text_adv formNoReq_text_adv "></p>

                                            </div>

                                        </div>

                                        <!-- -----------------------------------------  -->

                                        <div class="field item form-group">

                                            <label class="col-form-label col-md-3 col-sm-3  label-align" for="Status">Status</label>

                                            <div class="col-md-6 col-sm-6">

                                                <!-- <input type="checkbox" class="js-switch-status form-control  js-check-change formViewProvedorDataInput dataCheckboxView" id="Status" /> -->
                                                <input type="checkbox" class="form-check-input formViewProvedorDataInput dataCheckboxView" id='StatusV' disabled>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ext" class="form_text_adv formNoReq_text_adv "></p>

                                            </div>

                                        </div>
                                        <!-- -----------------------------------------------  -->

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>

                </div>
                <!-- ----------------------------------  -->
            </div>

            <!-- ----------------------------------  -->

            <div class="modal-footer">

                <button class="btn btn-primary" id="prev-btn-modal3">Anterior</button>
                <button class="btn btn-primary" id="next-btn-modalV3">Siguiente</button>
                <button class="btn btn-secondary" id='btnModalCancelarViewProvedor'>Cancelar</button>

            </div>

            <!-- -----------------------------  -->

        </div>

    </div>

</div>
<!-- jquery.inputmask agregado-->

<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>
<script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script>
<script type="text/javascript" src="../../requerimientos/vendors/wizardLibrery/jquery.smartWizard.min.js"></script>
<script src="js/proveedor.js"></script>




<!-- Bootstrap -->


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- SELECT SEARCH  -->
<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>
