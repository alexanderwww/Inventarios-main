<?php include '../../requerimientos/headers/header.php' ?>

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">
<!-- ------------->

<link rel="stylesheet" href="./css/style.css">


<!-- page content including menus -->


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Compras /</span> Catalogo de compras</h4>

    <div class="card">
        <h5 class="card-header inline-head">
            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Compras' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">
            <?php if($_SESSION['Rol']['comprasC'] == 1):?>
            <button type="button" class="btn btn-primary derecha btnModalAlta">Compras <i class='bx bx-plus'></i></button>
            <?php endif ?>
        </h5>
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaCompras">
                <thead>
                    <tr>

                        <th>Acciones</th>

                        <th>Id</th>

                        <th>Fecha</th>

                        <th>Producto</th>

                        <th>Entrada</th>

                        <th>Proveedor</th>

                        <th>Precio Litro</th>

                        <th>Moneda</th>

                        <th>Tipo Cambio</th>

                        <th>No. Factura</th>

                        <th>Observaciones</th>
                        <th>Estatus</th>


                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- ------------------------------------------------------- Modal Alta -->


    <div class="modal fade" id="modalAlta" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Compras de material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-sm-12  ">

                            <form id="formAlta">


                                <!-- ---------------------------  -->


                                <div class="container">

                                    <div class="row mb-3">

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Usuario</label>
                                                <input onkeyup="sobreinput(event);" class="form-control  formDataAlta stringClass trim" type="text" id="Nombre" value="<?php echo $_SESSION['Nombre'] ?>" disabled>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>

                                            </div>

                                            <div class="my-3">

                                                <label>Material Existente</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control  formDataAlta  stringClass trim" type="text" id="Existente_alta" disabled>
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>
                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Existente_alta" class="form_text_adv"></p>

                                            </div>

                                        </div>

                                        <div class="col-6">

                                            <div class="my-3" style="width: 100%;">

                                                <label>Productos *</label>
                                                <div style="width: 100%;">
                                                    <select onchange="sobreSelectData(event)" class="js-states form-control validarDataAlta formDataAlta" id='Producto_alta'>
                                                    </select>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Producto_alta" class="form_text_adv"></p>

                                            </div>

                                            <div class="my-3">

                                                <label>Entrada material</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataAlta formDataAlta  stringClass trim mask-pesos" type="text" id="Entrada_alta">
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Entrada_alta" class="form_text_adv"></p>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Existencia de material despues de entrada</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control formDataAlta  stringClass trim" type="text" id="Despues_alta" disabled>
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Despues_alta" class="form_text_adv"></p>


                                            </div>

                                            <div class="my-3" style="width: 100%;">

                                                <label>Proveedor *</label>
                                                <div style="width: 100%;">
                                                    <select onchange="sobreSelectData(event)" class="js-states form-control validarDataAlta formDataAlta " id='Proveedor_alta'>
                                                    </select>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Proveedor_alta" class="form_text_adv"></p>


                                            </div>

                                        </div>

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Precio por litro</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control borderRadiusInputPrice width100 validarDataAlta formDataAlta  stringClass trim mask-pesos"   type="text" id="Precio_alta">
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Precio_alta" class="form_text_adv"></p>

                                            </div>

                                            <div class="my-3">

                                                <label>Moneda</label>
                                                <select onchange="sobreSelectData(event)" class=" form-control validarDataAlta formDataAlta form-select" id='Moneda_alta'></select>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Moneda_alta" class="form_text_adv"></p>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Tipo de cambio</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control borderRadiusInputPrice width100 validarDataAlta formDataAlta  stringClass trim" type="number" id="TpCambio_alta" value="<?php echo $_SESSION['TipoCambio'] ?>">

                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_TpCambio_alta" class="form_text_adv"></p>


                                            </div>


                                        </div>

                                        <div class="col-6">


                                            <div class="my-3">

                                                <label>No. Factura</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataAlta formDataAlta  stringClass trim" type="number" id="NoFactura_alta">
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NoFactura_alta" class="form_text_adv"></p>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="my-3">
                                            <label>Observaciones</label>
                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control formDataAlta  stringClass trim" type="text" id="Observaciones_alta">
                                            </div>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Observaciones_alta" class="form_text_adv"></p>

                                        </div>
                                    </div>

                                </div>


                                <!-- ------------------------------  -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary btnAceptarAlta">Registrar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

        <!-- ------------------------------------------------------- Modal View -->


        <div class="modal fade" id="modalView" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-titleView">Compras de material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-sm-12  ">

                            <form id="formView">


                                <!-- ---------------------------  -->


                                <div class="container">

                                    <div class="row mb-3">

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Usuario</label>
                                                <input onkeyup="sobreinput(event);" class="form-control  formDataView stringClass trim" type="text" id="Nombre_View"  disabled>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>

                                            </div>
<!-- 
                                            <div class="my-3">

                                                <label>Material Existente</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control  formDataView  stringClass trim" type="number" id="Existente_View" disabled>
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>
                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Existente_View" class="form_text_adv"></p>

                                            </div> -->

                                        </div>

                                        <div class="col-6">

                                            <div class="my-3" style="width: 100%;">

                                                <label>Producto</label>
                                                <div style="width: 100%;">
                                                <input onkeyup="sobreinput(event);" class="form-control  formDataView stringClass trim" type="text" id="Producto_View"  disabled>
                                                    
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Producto_View" class="form_text_adv"></p>

                                            </div>

                                           

                                        </div>


                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-6">


                                            <div class="my-3" style="width: 100%;">

                                                <label>Proveedor *</label>
                                                <div style="width: 100%;">
                                                <input  class="form-control  formDataView stringClass trim" type="text" id="Proveedor_View"  disabled>

                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Proveedor_View" class="form_text_adv"></p>


                                            </div>
                                            <div class="my-3">

                                                <label>Entrada material</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataView formDataView  stringClass trim" type="number" id="Entrada_View" disabled>
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Entrada_View" class="form_text_adv"></p>

                                            </div>
                                        </div>

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Precio por litro</label>
                                                
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataView borderRadiusInputPrice width100 formDataView  stringClass trim" type="number" id="Precio_View" disabled>
                                                </div>


                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Precio_View" class="form_text_adv"></p>

                                            </div>

                                            <div class="my-3">

                                                <label>Moneda</label>
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataView formDataView  stringClass trim"  id="Moneda_View" disabled>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Moneda_View" class="form_text_adv"></p>

                                            </div>

                                        </div>


                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-6">

                                            <div class="my-3">

                                                <label>Tipo de cambio</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_TpCambio_View" class="form_text_adv"></p>
                                                </div>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataView formDataView borderRadiusInputPrice  width100 stringClass trim" type="number" id="TpCambio_View" disabled>
                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_TpCambio_View" class="form_text_adv"></p>


                                            </div>


                                        </div>

                                        <div class="col-6">


                                            <div class="my-3">

                                                <label>No. Factura</label>
                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control validarDataView formDataView  stringClass trim" type="number" id="NoFactura_View" disabled>
                                                </div>
                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_NoFactura_View" class="form_text_adv"></p>

                                            </div>


                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="my-3">
                                            <label>Observaciones</label>
                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control formDataView  stringClass trim" type="text" id="Observaciones_View" disabled>
                                            </div>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Observaciones_View" class="form_text_adv"></p>

                                        </div>
                                    </div>

                                </div>


                                <!-- ------------------------------  -->

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!------------------------------------- Modal Cancelar Compra  -------------------------------------------------------------------------------------------->
    <div class="modal fade" tabindex="-1" id="btnCancelar" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <p class="modal-title">¿Seguro que desea Cancelar la Orden de Compra?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btnModalCancelar" data-bs-dismiss="modal" id="Cancelar_compra">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------------------------------------------------------------------------  -->

    <script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>

    <script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

    <?php include '../../requerimientos/headers/footer.php' ?>


    <script src="js/compras.js"></script>

    <script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        
    <!-- SELECT SEARCH -->
    <script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>    
    <!-- ------------------->
