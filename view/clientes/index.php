<?php include '../../requerimientos/headers/header.php' ?>


<!-- <script src="../../jsGeneral/appInt.js"></script> -->

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- CSS SmartWizard -->
<link href="../../requerimientos/vendors/wizardLibrery/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
<!-- switchery -->
<link rel="stylesheet" href="../../requerimientos/vendors/togle_switchery/dist/switchery.css">
<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">


<!-- page content including menus -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Proveedores /</span> Lista de clientes</h4>

    <div class="card">
        <h5 class="card-header inline-head">
            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Clientes' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">
            <?php if($_SESSION['Rol']['clienteC'] == 1):?>
                <button id='btnAltaCliente' type="button" class="btn btn-primary derecha btnAltaCliente" data-bs-toggle="modal" data-bs-target="#modalAltaCliente">Cliente <i class='bx bxs-user-plus'></i></button>
            <?php else: ?>
                <button id='btnAltaCliente' type="button" class="btn btn-primary derecha btnAltaCliente" style="display:none">Cliente <i class='bx bxs-user-plus'></i></button>
            <?php endif ?>
       
        </h5>
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaCliente">
                <thead>
                    <tr>
                    <th>Acciones</th>

                    <th>Id</th>

                    <th>Status</th>

                    <th>Nombre</th>

                    <th>Razón Social</th>

                    <th>RFC</th>

                    <th>Régimen Fiscal</th>

                    <!-- <th>Ejecutiva</th> -->

                    <th>Teléfono</th>

                    <th>Ext</th>

                    <th>Dirección</th>

                    <th>Código Postal</th>

                    <th>Días Crédito</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>



  



                                <!------------------------------------- Tabla  -->



                                <!-------------------------------------Modal Alta Cliente -->

                

                                <div class="modal fade" tabindex="-1" id="modalAltaCliente"  role="dialog" aria-hidden="true">

                                        

                                    <div class="modal-dialog modal-lg" role="document">



                                            <div class="modal-content">



                                                <!-- ----------------------------------  -->

                                                <div class="modal-header">

                                                    <h5 class="modal-title" >Alta Cliente</h5>

                                                    <span id="sw-current-step1" style="display: none;" ></span>                                    

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                                                    <!-- <span aria-hidden="true">&times;</span> -->

                                                    </button>

                                                </div>

                                                <!-- ----------------------------------  -->

                                                <div class="modal-body">

                                                    <!-- -----------------------------  -->

                                                    <div class="row">



                                                        <div class="col-md-12 col-sm-12  ">



                                                            <form id="formAltaCliente" method="POST" class="" enctype="multipart/form-data">



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

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="nombreComercialAlta">Nombre Comercial  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_nombreComercialAlta form-control campoValidarAlta_Sectio1 formAltaDataInput trim"

                                                                                type="text" id="nombreComercialAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre comercial">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_nombreComercialAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="razonSocialAlta">Razón Social  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_razonSocialAlta form-control campoValidarAlta_Sectio1 formAltaDataInput trim"

                                                                                type="text" id="razonSocialAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Razón social del Cliente">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_razonSocialAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                        </div>

                                                                        <!-- --------------------------------------  -->

                                                                        <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="rfcAlta">RFC *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_rfcAlta form-control campoValidarAlta_Sectio2 formAltaDataInput upperTrim"

                                                                                type="text" id="rfcAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="RFC">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_rfcAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="RegimenFiscalAlta">Régimen Fiscal *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_RegimenFiscalAlta form-control campoValidarAlta_Sectio2 formAltaDataInput trim"

                                                                                type="text" id="RegimenFiscalAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Régimen Fiscal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RegimenFiscalAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="calleYNumeroAlta">Calle y Número  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_calleYNumeroAlta form-control campoValidarAlta_Sectio2 formAltaDataInput trim"

                                                                                type="text" id="calleYNumeroAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Calle y Número">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_calleYNumeroAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="coloniaAlta">Colonia  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_coloniaAlta form-control campoValidarAlta_Sectio2 formAltaDataInput trim"

                                                                                type="text" id="coloniaAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Colonia">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_coloniaAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            <!-- ----------------------------------------  -->

                                                                            

                                                                            <!-- <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="usuarioSelectAlta">Ejecutivo a cargo*</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_usuarioSelectAlta form-control campoValidarAlta_Sectio2 formAltaDataInput" id="usuarioSelectAlta"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_usuarioSelectAlta" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div> -->



                                                                           <!-- ----------------------------------------  -->





                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="paisSelectAlta">País *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_pais form-control campoValidarAlta_Sectio2 selectChosenAlta formAltaDataInput" id="paisSelectAlta"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_paisSelectAlta" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="estadoSelectAlta">Estado *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_estado form-control campoValidarAlta_Sectio2 selectChosenAlta formAltaDataInput" id="estadoSelectAlta"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_estadoSelectAlta" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="ciudadSelectAlta">Ciudad *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_ciudad form-control campoValidarAlta_Sectio2 selectChosenAlta formAltaDataInput" id="ciudadSelectAlta"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ciudadSelectAlta" class="form_text_adv">

                                                                                    No has seleccionado el ciudad

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="codigoPostalAlta">Código Postal  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_codigoPostalAlta form-control campoValidarAlta_Sectio2 formAltaDataInput trim"

                                                                                type="number" id="codigoPostalAlta"  placeholder="Código Postal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_codigoPostalAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="diasCreditoAlta">Días de Crédito  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_diasCreditoAlta form-control campoValidarAlta_Sectio2 formAltaDataInput trim"

                                                                                type="number" id="diasCreditoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Días de Crédito">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_diasCreditoAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <!-- --------------------------------------  -->

                                                                        <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="contactoPrincipalAlta">Contacto Principal</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_contactoPrincipalAlta form-control formAltaDataInput campoValidarCaracteresAlta trim"

                                                                                type="text" id="contactoPrincipalAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre y Apellido del contacto principal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_contactoPrincipalAlta" class="form_text_adv formNoReq_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="correoElectronicoAlta">Correo Electrónico</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_correoElectronicoAlta form-control  formAltaDataInput validarCorreosNoObligatorios trim"

                                                                                type="email" id="correoElectronicoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="ejemplo@dominio.com">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_correoElectronicoAlta" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>





                                                                            <!-- ---------------------------  -->



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="numeroTelefonoAlta">Número telefónico</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_numeroTelefonoAlta form-control campoValidarAlta_Sectio3 formAltaDataInput campoValidarCaracteresAlta trim"

                                                                                type="text" id="numeroTelefonoAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="" data-inputmask="'mask' : '(999) 999-9999'">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_numeroTelefonoAlta" class="form_text_adv formNoReq_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="extensionAlta">Extensión</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_extensionAlta form-control formAltaDataInput campoValidarCaracteresAlta trim"

                                                                                type="text" id="extensionAlta" pattern="[A-Za-z0-9]{1,30}" placeholder="Extensión">



                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_extensionAlta" class="form_text_adv formNoReq_text_adv "></p>



                                                                                </div>

                                                                            </div>                                                                           



                                                                        </div>



                                                                    </div>

                                                                    

                                                                </div>



                                                            </form>



                                                        </div>



                                                    </div>

                                                    <!-- ----------------------------------  -->

                                                </div>

                                                <!-- ----------------------------------  -->

                                                <div class="modal-footer" >



                                                    <button class="btn btn-primary" id="prev-btn-modal1">Anterior</button>

                                                    <button class="btn btn-primary" id="next-btn-modal1">Siguiente</button>

                                                    

                                                    <button class="btn btn-success btnWizarconfirmarDisponible1" id='btnModalConfirmarAlta'>Confirmar</button>



                                                    <button class="btn btn-secondary" id='btnModalCancelarAlta'>Cancelar</button>



                                                </div>

                                                <!-- -----------------------------  -->



                                            </div>



                                    </div>



                                </div>                            





                                <!--------------------------------------------------------------------- Modal Update Cliente  -->



                                <div class="modal fade" tabindex="-1" id="modalEditar"  role="dialog" aria-hidden="true">

                                        

                                    <div class="modal-dialog modal-lg" role="document">



                                            <div class="modal-content">



                                                <!-- ----------------------------------  -->

                                                <div class="modal-header">

                                                    <h5 class="modal-title" >Editar Cliente: <b id='nameModalEditar'></b></h5>

                                                    <span id="sw-current-step2" style="display: none;" ></span>                                    

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                                                    <!-- <span aria-hidden="true">&times;</span> -->

                                                    </button>

                                                </div>

                                                <!-- ----------------------------------  -->

                                                <div class="modal-body">

                                                    <!-- -----------------------------  -->

                                                    <div class="row">



                                                        <div class="col-md-12 col-sm-12  ">



                                                            <form id="formEditar" method="POST" class="" enctype="multipart/form-data">



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

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Nombre">Nombre Comercial  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_NombreCom form-control campoValidarEditar_Sectio1 formEditarDataInput dataInputEditar trim"

                                                                                type="text" id="Nombre" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre comercial">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="RazonSocial">Razón Social  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_RazonSocial form-control campoValidarEditar_Sectio1 formEditarDataInput dataInputEditar trim"

                                                                                type="text" id="RazonSocial" pattern="[A-Za-z0-9]{1,30}" placeholder="Razon social del Cliente">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RazonSocial" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                        </div>

                                                                        <!-- --------------------------------------  -->

                                                                        <div id="step-20" class="tab-pane" role="tabpanel" aria-labelledby="step-20">

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="RFC">RFC *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_RFC form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar upperTrim"

                                                                                type="text" id="RFC" pattern="[A-Za-z0-9]{1,30}" placeholder="RFC">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RFC" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="RFC">Régimen Fiscal *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_RegimenFiscal form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar trim"

                                                                                type="text" id="RegimenFiscal" pattern="[A-Za-z0-9]{1,30}" placeholder="Régimen Fiscal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_RegimenFiscal" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="CalleCliente">Calle y Número  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_CalleCliente form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar trim"

                                                                                type="text" id="CalleCliente" pattern="[A-Za-z0-9]{1,30}" placeholder="Calle y Número">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CalleCliente" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="ColoniaCliente">Colonia *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_ColoniaCliente form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar trim"

                                                                                type="text" id="ColoniaCliente" pattern="[A-Za-z0-9]{1,30}" placeholder="Colonia">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ColoniaCliente" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                          <!-- ----------------------------------------  -->

                

                                                                            <!-- <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Ejecutiva">Ejecutivo a cargo*</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_Ejecutiva form-control campoValidarEditar_Sectio2 formEditarDataInput dataSelectEditar" id="Ejecutiva"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ejecutiva" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div> -->



                                                                           <!-- ----------------------------------------  -->



                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="PaisCliente">País *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_PaisCliente form-control campoValidarEditar_Sectio2 selectChosenEdit formEditarDataInput dataSelectEditar " id="PaisCliente"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_PaisCliente" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="EstadoCliente">Estado *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_EstadoCliente form-control campoValidarEditar_Sectio2 selectChosenEdit formEditarDataInput dataSelectEditar" id="EstadoCliente"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_EstadoCliente" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group">

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="CiudadCliente">Ciudad *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <select type='select' class="form_CiudadCliente form-control campoValidarEditar_Sectio2 formEditarDataInput selectChosenEdit dataSelectEditar" id="CiudadCliente"

                                                                                    autocomplete="off" style="width:100%;">

                                                                                    </select>

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CiudadCliente" class="form_text_adv">

                                                                                    </p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="CPCliente">Código Postal  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_CPCliente form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar trim"

                                                                                type="number" id="CPCliente"  placeholder="Código Postal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CPCliente" class="form_text_adv">Se requiere Código Postal</p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="dias_credito">Días de Crédito  *</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_dias_credito form-control campoValidarEditar_Sectio2 formEditarDataInput dataInputEditar trim"

                                                                                type="number" id="dias_credito" pattern="[A-Za-z0-9]{1,30}" placeholder="Días de Crédito">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_dias_credito" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        <!-- --------------------------------------  -->

                                                                        <div id="step-30" class="tab-pane" role="tabpanel" aria-labelledby="step-30">

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="ContactoPrincipal">Contacto Principal</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_ContactoPrincipal form-control formEditarDataInput dataInputEditar campoValidarCaracteresEditar  trim"

                                                                                type="text" id="ContactoPrincipal" pattern="[A-Za-z0-9]{1,30}" placeholder="Nombre y Apellido del contacto principal">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ContactoPrincipal" class="form_text_adv formNoReq_text_adv"></p>

                                                                                </div>

                                                                            </div>

                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="CorreoElectronico">Correo Electrónico</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_CorreoElectronico form-control formEditarDataInput dataInputEditar validarCorreosNoObligatorios trim"

                                                                                type="email" id="CorreoElectronico" pattern="[A-Za-z0-9]{1,30}" placeholder="ejemplo@dominio.com">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CorreoElectronico" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Telefono">Número telefónico</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                <input autocomplete="off"  class="form_Telefono form-control formEditarDataInput dataInputEditar campoValidarCaracteresEditar trim"

                                                                                type="text" id="Telefono" pattern="[A-Za-z0-9]{1,30}" placeholder="" data-inputmask="'mask' : '(999) 999-9999'">

                                                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Telefono" class="form_text_adv"></p>

                                                                                </div>

                                                                            </div>



                                                                            

                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Ext">Extensión</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    <input autocomplete="off"  class="form_Ext form-control formEditarDataInput dataInputEditar campoValidarCaracteresEditar trim"

                                                                                    type="text" id="Ext" pattern="[A-Za-z0-9]{1,30}" placeholder="Ext">

                                                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Ext" class="form_text_adv formNoReq_text_adv "></p>

                                                                                </div>

                                                                            </div>



                                                                            <div class="field item form-group" >

                                                                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Status">Status</label>

                                                                                <div class="col-md-6 col-sm-6">

                                                                                    

                                                                                    <input type="checkbox" class="js-switch-status js-check-change formEditarDataInput dataCheckboxEditar" id="Status" />



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

                                                <div class="modal-footer" >



                                                    <button class="btn btn-primary" id="prev-btn-modal2">Anterior</button>

                                                    <button class="btn btn-primary" id="next-btn-modal2">Siguiente</button>

                                                    

                                                    <button class="btn btn-success btnWizarconfirmarDisponible2" id='btnModalConfirmarEditar'>Actualizar</button>

                                                    <button class="btn btn-secondary" id='btnModalCancelarEditar'>Cancelar</button>



                                                </div>

                                                <!-- -----------------------------  -->



                                            </div>



                                    </div>



                                </div>



                                

                                <!------------------------------------- Modal Excel  -->

                                <div class="modal fade" tabindex="-1" id="modalExcelInfo"  role="dialog" aria-hidden="true">

                                        <div class="modal-dialog" role="document">

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <h5 class="modal-title" >Nota</h5>



                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                                    <span aria-hidden="true">&times;</span>

                                                    </button>

                                                </div>

                                            <div class="modal-body">

                                                <p class="modal-title" >

                                                Te recordamos que la información es propiedad de Control Terrestre, para tu seguridad, el archivo estará protegido por una contraseña y llevará embebido en la metadata el nombre del usuario que exportó la información

                                                </p>

                                            </div>

                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                                                    <button type="button" class="btn bg-success text-white" id="btnExcelEntendido" data-dismiss="modal">Entendido</button>

                                                </div>

                                            </div>

                                        </div>

                                </div>



                                <!--------------------------------------->

    <!-- <script src="../../vendors/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

   <!-- <script src="../../vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<?php include '../../requerimientos/headers/footer.php' ?>
<script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script>
<script type="text/javascript" src="../../requerimientos/vendors/wizardLibrery/jquery.smartWizard.min.js"></script>
<script src="js/clientes.js"></script>
<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<!-- SELECT SEARCH  -->
<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>
