<?php include '../../requerimientos/headers/header.php' ?>

<!-- SELECT SEARCH  -->

<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">


<!-- Dropzone -->
<!-- CAMBIOS ALEXANDER EVIDENCIAS  -->
<link href="../../requerimientos/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

<!-- ------------->


<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ordenes de Ventas /</span> Lista orden de venta</h4>



    <div class="card">

        <h5 class="card-header inline-head">

            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Órdenes de venta' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">

            <?php if ($_SESSION['Rol']['notasVC'] == 1) : ?>

                <button type="button" class="btn btn-primary derecha btnModalAlta">Orden de Venta <i class='bx bx-plus'></i></button>

            <?php endif ?>

        </h5>

        <div class="table-responsive text-nowrap m-3 inline">

            <table class="table" id="tablaOdv">

                <thead>

                    <tr>

                        <th>Acciones</th>



                        <th>Id</th>



                        <th>Cliente</th>



                        <th>Moneda</th>



                        <th>Subtotal</th>



                        <th>Iva</th>



                        <th>Total</th>



                        <th>Fecha</th>



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

                    <h5 class="modal-title">Alta Orden de Venta</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 col-sm-12  ">

                            <form id="formAlta">



                                <!-- --------------------------------------------------------------------------------------------  -->







                                <!-- ------------------------------  -->



                                <div class="container">



                                    <div class="row mb-3">



                                        <!-- ---------------------------  -->



                                        <div class="col-6">



                                            <div class="my-3">



                                                <label>Folio / # Odv *</label>



                                                <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Nombre_alta" disabled>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre_alta" class="form_text_adv"></p>



                                            </div>



                                            <div class="my-3">



                                                <label>Cliente *</label>



                                                <!-- <select  class=" form-control validarDataAlta formAltaData form-select" id='Cliente_alta'></select> -->



                                                <select onchange="sobreSelectData(event)" data-placeholder="Choose a name..." class="form-control validarDataAlta formAltaData" id="Cliente_alta"></select>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Cliente_alta" class="form_text_adv"></p>



                                            </div>



                                        </div>





                                        <!-- ---------------------------  -->





                                        <div class="col-6">



                                            <div class="my-3">



                                                <label>Tipo de Cambio *</label>



                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control validarDataAlta formAltaData  stringClass trim" type="number" id="TpCambio_alta" value="<?php echo $_SESSION['TipoCambio'] ?>">

                                                </div>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_TpCambio_alta" class="form_text_adv"></p>



                                            </div>



                                            <div class="my-3">

                                                <label>Moneda *</label>

                                                <select onchange="sobreSelectData(event)" class=" form-control validarDataAlta formAltaData form-select" id='Moneda_alta'></select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Moneda_alta" class="form_text_adv"></p>

                                            </div>



                                        </div>





                                        <!-- ---------------------------  -->



                                    </div>



                                </div>



                                <!-- -------------------------------------------------  -->

                                <div class="divider divider-Dotted">

                                    <div class="divider-text"><span><strong>Productos</strong></span></div>

                                </div>



                                <div class="containerItems_alta" id="formItems_alta">


                                    <div id="itemsPrincipal_alta"></div>

                                    <div id="itemsSecundarios_alta"></div>

                                </div>



                                <!-- -------------------------------------------------  -->



                                <div class="container">



                                    <div class="row mb-3">



                                        <!-- ---------------------------  -->



                                        <div class="col-4">



                                            <div class="my-3">



                                                <label>SubTotal *</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control SubtotalEntrada formAltaData  stringClass trim" type="text" id="SubTotal_alta" disabled>

                                                </div>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_SubTotal_alta" class="form_text_adv"></p>



                                            </div>



                                        </div>



                                        <!-- ---------------------------  -->



                                        <div class="col-4">



                                            <div class="my-3">



                                                <label>Iva *</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control  EntradaSalida formAltaData  stringClass trim" type="text" id="Iva_alta" disabled>

                                                </div>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Iva_alta" class="form_text_adv"></p>



                                            </div>



                                        </div>

                                        <!-- ---------------------------  -->





                                        <div class="col-4">



                                            <div class="my-3">



                                                <label>Total *</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control  EntradaSalida formAltaData  stringClass trim" type="text" id="Total_alta" disabled>

                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Total_alta" class="form_text_adv"></p>



                                            </div>



                                        </div>





                                        <!-- ---------------------------  -->



                                    </div>



                                    <div class="row mb-3">



                                        <div>



                                            <label>Observaciones</label>

                                            <textarea class="form-control   formAltaData" onkeyup="sobreinput(event);" aria-label="With textarea" style="height: 40px;" id='Observaciones_alta'></textarea>

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





    <!-- ------------------------------------------------------- Modal Edit -->





    <div class="modal fade" id="modalEdit" role="dialog" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">



                <div class="modal-header">

                    <h5 class="modal-title"> Odv: <strong id="modalEditTitle"></strong></h5>

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



                                        <!-- ---------------------------  -->



                                        <div class="col-6">



                                            <div class="my-3">



                                                <label>Folio / # Pedido *</label>



                                                <input onkeyup="sobreinput(event);" class="form-control stringClass trim" type="text" id="Nombre_edit" disabled>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre_edit" class="form_text_adv"></p>



                                            </div>



                                            <div class="my-3">



                                                <label>Cliente *</label>

                                                <select onchange="sobreSelectData(event)" class=" form-control validarDataEdit formDataEdit form-select" id='Cliente_edit' disabled></select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Cliente_edit" class="form_text_adv"></p>



                                            </div>



                                        </div>



                                        <!-- ---------------------------  -->





                                        <div class="col-6">



                                            <div class="my-3">



                                                <label>Tipo de Cambio *</label>



                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control validarDataEdit formDataEdit  stringClass trim" type="number" id="TpCambio_edit" readonly>

                                                </div>



                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_TpCambio_edit" class="form_text_adv"></p>



                                            </div>



                                            <div class="my-3">

                                                <label>Moneda *</label>

                                                <select onchange="sobreSelectData(event)" class=" form-control validarDataEdit formDataEdit form-select" id='Moneda_edit' disabled></select>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Moneda_edit" class="form_text_adv"></p>

                                            </div>



                                        </div>





                                        <!-- ---------------------------  -->



                                    </div>



                                </div>



                                <!-- -------------------------------------------------  -->

                                <div class="divider divider-Dotted">

                                    <div class="divider-text"><span><strong>Productos</strong></span></div>

                                </div>







                                <div class="containerItems_edit" id="formItems_edit">



                                    <div id="itemsSecundarios_edit"></div>

                                    <div id="itemsPrincipal_edit"></div>



                                </div>



                                <!-- -------------------------------------------------  -->



                                <div class="container">



                                    <div class="row mb-3">



                                        <!-- ---------------------------  -->



                                        <div class="col-4">



                                            <div class="my-3">



                                                <label>SubTotal *</label>

                                                <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                    <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control formDataEdit  stringClass trim" type="text" id="SubTotal_edit" disabled>

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

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control formDataEdit  stringClass trim" type="text" id="Iva_edit" disabled>

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

                                                    <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control formDataEdit  stringClass trim" type="text" id="Total_edit" disabled>

                                                </div>

                                                <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Total_edit" class="form_text_adv"></p>



                                            </div>





                                        </div>



                                        <!-- ---------------------------  -->



                                    </div>





                                    <div class="row mb-3">



                                        <div>



                                            <label>Observaciones</label>

                                            <textarea class="form-control formDataEdit" onkeyup="sobreinput(event);" aria-label="With textarea" style="height: 40px;" id='Observaciones_edit' readonly></textarea>

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Observaciones_edit" class="form_text_adv"></p>



                                        </div>





                                    </div>



                                </div>





                                <!-- ------------------------------  -->



                                <div class="modal-footer">

                                    <!-- <button type="button" class="btn btn-primary btnAceptarEdit">Actualizar</button> -->

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





</div>





<div style="display: none;">





    <div class="boxItemDefaul">



        <div class="container">



            <div class="col-md-12 col-sm-12">

                <!-- -----------------------------------------  -->

                <div class="row mb-3" style="align-items: center">

                    <div class="my-3  col-lg-3 col-md-4 col-sm-6">



                        <label>Producto *</label>

                        <div style="width: 100%;">

                            <select onchange="sobreSelectData(event),getProductoSelect(event);" class=" form-control getProducto formDataExample validarDataExample  productoClass form-select" id='ProductoPrimario_example'></select>

                        </div>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ProductoPrimario_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-6">

                        <label>Vender como: *</label>

                        <select onchange="sobreSelectData(event)" class=" form-control formDataExample validarDataExample  comoClass form-select" id='Como_example'></select>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Como_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-3 col-sm-3">

                        <label>Material Existente*</label>



                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input onkeyup="sobreinput(event); " class="SubtotalEntrada borderRadiusInputLiters form-control validarDataExample  formDataExample materialClass stringClass trim" type="text" id="Material_example" disabled>

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Material_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-3 col-sm-3">

                        <label>Cantidad de Venta*</label>



                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input onkeyup="sobreinput(event); calcularTotal(event);" class="SubtotalEntrada borderRadiusInputLiters validarDataExample  form-control formDataExample cantidadClass   stringClass trim mask-pesos" type="text" id="cantidad_example">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_cantidad_example" class="form_text_adv"></p>

                    </div>

                    <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada SubTotal  form-control formDataExample    stringClass trim" type="hidden" id="Subtotal_example">

                    <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada Iva  form-control formDataExample    stringClass trim" type="hidden" id="IvaPorcentual_example">



                    <div class="my-3 col-lg-3 col-md-3 col-sm-4">

                        <label>Precio por litro *</label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada borderRadiusInputPrice validarDataExample form-control formDataExample precioClass   stringClass trim mask-pesos" type="text" id="Precio_example">

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Precio_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-4">

                        <label>Impuesto:*</label>

                        <select onchange="sobreSelectData(event);calcularTotal(event);" class=" form-control formDataExample validarDataExample impuestoClass form-select" id='Iva_example'>

                            <option value="0"> 0.00%</option>

                            <option value="1"> 8.00%</option>

                            <option value="2"> 16.00%</option>

                        </select>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Iva_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-6 ">

                        <label>Total *</label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input onkeyup="sobreinput(event);" class="form-control  borderRadiusInputPrice validarDataExample formDataExample Total totalClass    stringClass trim" type="text" id="Total_example" disabled>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Total_example" class="form_text_adv"></p>

                    </div>

                    <div class=" col-md-2 col-sm-2">

                        <div class="containerBtnsDefault mt-3" style="padding-left:10px"></div>

                    </div>



                </div>



                <div class="divider divider-Dotted">

                    <div class="divider-text"></div>

                </div>

                <!-- -----------------------------------------  -->





            </div>

        </div>









    </div>



</div>

<!-- --------------------------------------------------------------------------------------------  -->

<div style="display: none;">





    <div class="boxItemDefaulView">



        <div class="container">



            <div class="col-md-12 col-sm-12">

                <!-- -----------------------------------------  -->

                <div class="row mb-3" style="align-items: center;">

                    <div class="my-3  col-lg-3 col-md-4 col-sm-6">



                        <label>Producto *</label>

                        <div style="width: 100%;">

                            <select onchange="sobreSelectData(event),getProductoSelect(event);" class=" form-control getProducto formDataExample validarDataExample selectProductoEdit  productoClass form-select" id='ProductoPrimario_example' disabled></select>

                        </div>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ProductoPrimario_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-6">

                        <label>Vender como: *</label>

                        <select onchange="sobreSelectData(event)" class=" form-control formDataExample validarDataExample selectVenderComoEdit comoClass form-select" id='Como_example' disabled></select>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Como_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-3 col-sm-3" style="display: none;">

                        <label>Material Existente*</label>





                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input onkeyup="sobreinput(event); " class="SubtotalEntrada borderRadiusInputLiters form-control validarDataExample  formDataExample materialClass stringClass trim" type="number" id="Material_example" disabled>

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Material_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-3 col-sm-3">

                        <label>Cantidad de Venta*</label>



                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input onkeyup="sobreinput(event); calcularTotal(event);" class="SubtotalEntrada borderRadiusInputLiters validarDataExample  form-control formDataExample cantidadClass   stringClass trim mask-pesos" type="text" id="cantidad_example" disabled>

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_cantidad_example" class="form_text_adv"></p>

                    </div>

                    <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada SubTotal  form-control formDataExample    stringClass trim" type="hidden" id="Subtotal_example">

                    <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada Iva  form-control formDataExample    stringClass trim" type="hidden" id="IvaPorcentual_example">



                    <div class="my-3 col-lg-3 col-md-3 col-sm-4">

                        <label>Precio por litro *</label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input onkeyup="sobreinput(event);calcularTotal(event); " class="SubtotalEntrada borderRadiusInputPrice validarDataExample form-control formDataExample precioClass   stringClass trim mask-pesos" type="text" id="Precio_example" disabled>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Precio_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-4">

                        <label>Impuesto:*</label>

                        <select onchange="sobreSelectData(event);calcularTotal(event);" class=" form-control formDataExample validarDataExample impuestoClass form-select" id='Iva_example' disabled>

                            <option value="0"> 0.00%</option>

                            <option value="1"> 8.00%</option>

                            <option value="2"> 16.00%</option>

                        </select>

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Iva_example" class="form_text_adv"></p>

                    </div>

                    <div class="my-3 col-lg-3 col-md-4 col-sm-6 ">

                        <label>Total *</label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input onkeyup="sobreinput(event);" class="form-control borderRadiusInputPrice  validarDataExample formDataExample Total totalClass    stringClass trim" type="text" id="Total_example" disabled>

                        </div>



                        <!-- Aqui Status -->

                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Total_example" class="form_text_adv"></p>

                    </div>

                    <div class=" col-md-2 col-sm-2">

                        <div class="containerBtnsDefault mt-3" style="padding-left:10px"></div>

                    </div>



                </div>



                <div class="divider divider-Dotted">

                    <div class="divider-text"></div>

                </div>

                <!-- -----------------------------------------  -->





            </div>

        </div>









    </div>



</div>

<!-- --------------------------------------------------------------------------------------------  -->



<div class="modal fade" tabindex="-1" id="modalCancelar" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Cancelar <b id="modalDeshabilitarTitle"></b></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>



            </div>

            <div class="modal-body">

                <!-- ---------------------------------  -->



                <h4 id="labelOdv">¿Desea cancelar la orden de venta?</h4>

                <!-- ------------------------------------  -->

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary btnAceptarDeshabilitar">Aceptar</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>



            </div>

        </div>

    </div>

</div>

<!-- ------------------------------------ MODAL CERRAR AGREGADO ANGEL 20/04/23 -->


<div class="modal fade" tabindex="-1" id="modalCerrar" role="dialog" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Cerrar <b id="modalCerrarTitle"></b></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>



            </div>

            <div class="modal-body">

                <!-- ---------------------------------  -->



                <h4 id="labelOdv">¿Desea Cerrar la orden de venta?</h4>

                <!-- ------------------------------------  -->

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary btnAceptarCerrar">Aceptar</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>



            </div>

        </div>

    </div>

</div>

<!-- ------------------------------------------------------------------------------------------------------CAMBIO ALEXANDER EVIDENCIAS nota El modal de Cancelar tiene todavia el nombre de Odv en lugar de "Pedido"  -->




<!-- <div class="modal fade modal-subir-archivos" id="modalEvidencias" tabindex="-1" role="dialog" aria-hidden="true">
     -->
<div class="modal fade modal-subir-archivos" id="modalEvidencias" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEvidenciasLabel" aria-hidden="true">



    <div class="modal-dialog modal-lg">



        <div class="modal-content">



            <div class="modal-header">



                <h4 class="modal-title">Carga de documentos</h4>


                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


            </div>



            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="x_content">

                            <div class="row noselected" id="containerEvidenciasUserEdit">

                                <p class="text-center" style="width:100%;">No hay Hoja de seguridad.</p>

                            </div>

                        </div>

                    </div>



                </div>



                <div class="row">



                    <div class="col-lg-12 col-md-12 col-sm-12">



                        <form enctype="multipart/form-data" class="dropzone" id="dropzone-archivos" style="width:100%;">



                            <div class="dz-message" data-dz-message><span>Elige el archivo para subir</span></div>



                            <input type="hidden" name="Accion" value="odv" />

                            <input type="hidden" name="Tabla" value="archivospedidos" />

                            <input type="hidden" id="inputIdEvidenciasEdit" name="Id" value="null" />





                        </form>



                    </div>



                </div>

                <i>Máximo 10 archivos, 10 megas por archivo </i>

            </div>



            <div class="modal-footer">



                <button type="submit" class="btn btn-success btnFormEvidencias">Subir</button>



                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Cerrar</button>



            </div>



        </div>



    </div>



</div>






<!-- Modal -->
<div class="modal fade" id="modalCerrar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCerrarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cerrar pedido <strong id="nameCerrar"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <h4>¿Desea Cerrar el Pedido?</h4>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnAceptarCerrar">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- ----------------------------------------------------------------------------------------------  -->



<script src="https://code.jquery.com/jquery-3.3.1.js"></script>





<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>



<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>



<script src="js/odv.js"></script>

<!-- Dropzone -->
<!-- CAMBIOS ALEXANDER EVIDENCIAS  -->
<script src="../../requerimientos/vendors/dropzone/dist/min/dropzone.min.js"></script>

<script>
    subirArchivosEdit();
</script>


<!-- SELECT SEARCH  -->

<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>