<?php include '../../requerimientos/headers/header.php' ?>

<!-- Dropzone -->
<link href="../../requerimientos/vendors/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">

<!-- page content including menus -->

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Productos /</span> Lista de productos primarios</h4>

    <div class="card">
        <h5 class="card-header inline-head">
            <input type="button" class="btn bg-success text-white" id="btnExcelProducto" nameTable='Productos Primarios' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">
            <?php if ($_SESSION['Rol']['prductoC'] == 1) : ?>
                <button type="button" class="btn btn-primary derecha btnModalAlta">Producto <i class='bx bx-plus'></i></button>
            <?php endif ?>
        </h5>
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaProductos">
                <thead>
                    <tr>
                        <th>Acciones</th>

                        <th>Id</th>

                        <th>Nombre</th>

                        <th>SubNombre</th>

                        <th>Densidad</th>

                        <th>Color</th>

                        <th>Hazmat</th>

                        <th>Marca</th>

                        <th>CAS</th>

                        <th>UN</th>

                        <th>Unidad</th>

                        <th>Concentracion</th>

                        <th>Uso</th>

                        <th>Formulacion</th>


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
                    <h5 class="modal-title">Alta Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-sm-12  ">

                            <form id="formAlta">



                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="my-3">
                                            <label class="label-align" for="Nombre_alta">Nombre *</label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarDataAlta stringClass trim" type="text" id="Nombre_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre_alta" class="form_text_adv"></p>

                                        </div>
                                        <div class="my-3">
                                            <label class="label-align" for="Densidad_alta">Densidad </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarDataAlta stringClass trim mask-pesos" type="text" id="Densidad_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Densidad_alta" class="form_text_adv"></p>

                                        </div>
                                    </div>



                                    <div class="col-6">
                                        <div class="my-3">
                                            <label class="label-align" for="Color_alta">Color </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="text" id="Color_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Color_alta" class="form_text_adv"></p>

                                        </div>
                                        <div class="my-3">
                                            <label class="label-align" for="Concentracion_alta">Concentración </label>

                                            <input onkeyup="sobreinput(event); " autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim mask-pesos" type="text" id="Concentracion_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Concentracion_alta" class="form_text_adv"></p>

                                        </div>

                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-align" for="tipoUnidad_alta">Unidad </label>

                                            <select onchange="sobreSelectData(event)" class=" form-control formAltaData validarDataAlta tipoUnidad_alta" id='tipoUnidad_alta'></select>
                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoUnidad_alta" class="form_text_adv"></p>


                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Marca_alta">Marca </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="text" id="Marca_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Marca_alta" class="form_text_adv"></p>
                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Uso_alta">Uso </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="text" id="Uso_alta" placeholder="">

                                            <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Uso_alta" class="form_text_adv"></p>


                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Hazmat_alta">Hazmat </label>
                                            <div class="col-md-2col-sm-2">

                                                <input type="checkbox" class="RolChecks form-check-input formAltaDataChecked " id='Hazmat_alta'>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="divider divider-Dotted">
                                        <label id="reactivosTitle">Reactivos de Seguridad</label>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="label-align" for="Flameabilidad_alta">Flameabilidad </label>

                                            <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="Flameabilidad_alta" placeholder=""> -->
                                            <select onchange="sobreSelectData(event)" type='select' class="form-control formAltaData validarCaracteresAlta stringClass trim" id="Flameabilidad_alta" autocomplete="off">
                                                <option selected value="">Seleccione uno...</option>
                                                <option value="0">0 - No se inflama</option>
                                                <option value="1">1 - Sobre 93°C</option>
                                                <option value="2">2 - Debajo de 93°C</option>
                                                <option value="3">3 - Debajo de 37°C</option>
                                                <option value="4">4 - Debajo de 25°C</option>

                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Reactividad_alta">Reactividad </label>

                                            <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="Reactividad_alta" placeholder=""> -->
                                            <select onchange="sobreSelectData(event)" type='select' class="form-control formAltaData validarCaracteresAlta stringClass trim" id="Reactividad_alta" autocomplete="off">
                                                <option selected value="">Seleccione uno...</option>
                                                <option value="0">0 - Estable</option>
                                                <option value="1">1 - Inestable en caso de calentamiento</option>
                                                <option value="2">2 - Inestable en cambio de quimico violento</option>
                                                <option value="3">3 - Puede explotar en caso de choque o calentamiento</option>
                                                <option value="4">4 - Puede explotar subitamente</option>

                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Toxicidad_alta">Toxicidad </label>

                                            <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="Toxicidad_alta" placeholder=""> -->
                                            <select onchange="sobreSelectData(event)" type='select' class="form-control formAltaData validarCaracteresAlta stringClass trim" id="Toxicidad_alta" autocomplete="off">
                                                <option selected value="">Seleccione uno...</option>
                                                <option value="0">0 - Minimo</option>
                                                <option value="1">1 - Ligero</option>
                                                <option value="2">2 - Moderado</option>
                                                <option value="3">3 - Serio</option>
                                                <option value="4">4 - Severo</option>

                                            </select>
                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="Corrosividad_alta">Corrosividad </label>

                                            <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="Corrosividad_alta" placeholder=""> -->
                                            <select onchange="sobreSelectData(event)" type='select' class="form-control formAltaData validarCaracteresAlta stringClass trim" id="Corrosividad_alta" autocomplete="off">
                                                <option selected value="">Seleccione uno...</option>
                                                <option value="OX">Componentes oxidantes</option>
                                                <option value="ACID">Componentes ácidos</option>
                                                <option value="ALC">Se encuentran productos alcalinos</option>
                                                <option value="COR">Corrosivos</option>
                                                <option value="W">Son sustancias que no deben de relacionarse con el agua ya que no tienen una relación muy peligrosa juntos</option>
                                                <option value="R">Para materiales con radiación</option>
                                                <option value="BIO">Refleja a los riesgo biológico</option>
                                                <option value="CRYO">Este código refleja que te encuentras frente a materiales criogénicos</option>
                                                <option value="Xn Nocivo">Riesgos epidemiológicos importantes</option>

                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <div class="row">
                                        <div class="col">


                                            <label class="label-align" for="CAS_alta">CAS </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="CAS_alta" placeholder="">

                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="UN_alta">UN </label>

                                            <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="UN_alta" placeholder="">



                                        </div>
                                        <div class="col">
                                            <label class="label-align" for="">Hoja de seguridad</label>

                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalEvidenciasAlta">
                                                Subir
                                            </button>
                                        </div>
                                        <div class="col">


                                            <label class="label-align" for="Formulacion_alta">Formulación </label>
                                            <div class="col-md-2col-sm-2">
                                                <input type="checkbox" class="RolChecks form-check-input formAltaDataChecked " id='Formulacion_alta'>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <!-- -------------------------------------------------  -->


                                <div class="containerItems_alta" id="formItems_alta">

                                    <div id="itemsSecundarios_alta"></div>
                                    <div id="itemsPrincipal_alta"></div>

                                </div>




                                <div class="field item form-group d-flex justify-content-center">

                                    <strong class="d-flex">
                                        <span id="TotalPorcentaje"></span>
                                        <h6 style="color: black; margin: 3px 0 0 10px;" id="infoPorcentajeItemsAlta"></h6>
                                    </strong>

                                </div>
                                <!-- -------------------------------------------------  -->



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
                    <h5 class="modal-title"><span id="modalEditOption">Actualizar</span> Producto: <strong id="modalEditTitle"></strong></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12 col-sm-12  ">

                            <form id="formEdit">



                                <!-- * -->
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Nombre">Nombre *</label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarDataEdit stringClass trim" type="text" id="Nombre" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>

                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="SubNombre">SubNombre *</label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="text" id="SubNombre" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_SubNombre" class="form_text_adv"></p>

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Densidad">Densidad </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarDataEdit stringClass trim mask-pesos" type="text" id="Densidad" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Densidad" class="form_text_adv"></p>

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Color">Color </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="text" id="Color" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Color" class="form_text_adv"></p>

                                    </div>
                                </div>


                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Hazmat">Hazmat </label>
                                    <div class="col-md-1 col-sm-1">

                                        <input type="checkbox" class="RolChecks form-check-input formEditDataChecked " id='Hazmat'>

                                    </div>
                                    <label class="col-form-label col-md-2 col-sm-2  label-align" for="tipoUnidad">Unidad </label>
                                    <div class="col-md-3 col-sm-3">

                                        <select onchange="sobreSelectData(event)" class=" form-control formEditData validarCaracteresEdit " id='tipoUnidad'></select>
                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_tipoUnidad" class="form_text_adv"></p>

                                    </div>

                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="CAS">CAS </label>
                                    <div class="col-md-2 col-sm-2">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="number" id="CAS" placeholder="">

                                    </div>
                                    <label class="col-form-label col-md-2 col-sm-2  label-align" for="UN">UN </label>
                                    <div class="col-md-2 col-sm-2">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="number" id="UN" placeholder="">

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Flameabilidad">Flameabilidad </label>
                                    <div class="col-md-2 col-sm-2">

                                        <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData validarCaracteresAlta stringClass trim" type="number" id="Flameabilidad" placeholder=""> -->
                                        <select onchange="sobreSelectData(event)" type='select' class="form-control formEditData validarCaracteresEdit stringClass trim" id="Flameabilidad" autocomplete="off">
                                            <option selected value="">Seleccione uno...</option>
                                            <option value="0">0 - No se inflama</option>
                                            <option value="1">1 - Sobre 93°C</option>
                                            <option value="2">2 - Debajo de 93°C</option>
                                            <option value="3">3 - Debajo de 37°C</option>
                                            <option value="4">4 - Debajo de 25°C</option>

                                        </select>
                                    </div>
                                    <label class="col-form-label col-md-2 col-sm-2  label-align" for="Reactividad">Reactividad </label>
                                    <div class="col-md-2 col-sm-2">

                                        <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="number" id="Reactividad" placeholder=""> -->
                                        <select onchange="sobreSelectData(event)" type='select' class="form-control formEditData validarCaracteresEdit stringClass trim" id="Reactividad" autocomplete="off">
                                            <option selected value="">Seleccione uno...</option>
                                            <option value="0">0 - Estable</option>
                                            <option value="1">1 - Inestable en caso de calentamiento</option>
                                            <option value="2">2 - Inestable en cambio de quimico violento</option>
                                            <option value="3">3 - Puede explotar en caso de choque o calentamiento</option>
                                            <option value="4">4 - Puede explotar subitamente</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Toxicidad">Toxicidad </label>
                                    <div class="col-md-2 col-sm-2">

                                        <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="number" id="Toxicidad" placeholder=""> -->
                                        <select onchange="sobreSelectData(event)" type='select' class="form-control formEditData validarCaracteresEdit stringClass trim" id="Toxicidad" autocomplete="off">
                                            <option selected value="">Seleccione uno...</option>
                                            <option value="0">0 - Minimo</option>
                                            <option value="1">1 - Ligero</option>
                                            <option value="2">2 - Moderado</option>
                                            <option value="3">3 - Serio</option>
                                            <option value="4">4 - Severo</option>

                                        </select>
                                    </div>
                                    <label class="col-form-label col-md-2 col-sm-2  label-align" for="Corrosividad">Corrosividad </label>
                                    <div class="col-md-2 col-sm-2">

                                        <!-- <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="number" id="Corrosividad" placeholder=""> -->
                                        <select onchange="sobreSelectData(event)" type='select' class="form-control formEditData validarCaracteresEdit stringClass trim" id="Corrosividad" autocomplete="off">
                                            <option selected value="">Seleccione uno...</option>
                                            <option value="OX">Componentes oxidantes</option>
                                            <option value="ACID">Componentes ácidos</option>
                                            <option value="ALC">Se encuentran productos alcalinos</option>
                                            <option value="COR">Corrosivos</option>
                                            <option value="W">Son sustancias que no deben de relacionarse con el agua ya que no tienen una relación muy peligrosa juntos</option>
                                            <option value="R">Para materiales con radiación</option>
                                            <option value="BIO">Refleja a los riesgo biológico</option>
                                            <option value="CRYO">Este código refleja que te encuentras frente a materiales criogénicos</option>
                                            <option value="Xn Nocivo">Riesgos epidemiológicos importantes</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Marca">Marca </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="text" id="Marca" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Marca" class="form_text_adv"></p>

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Concentracion">Concentración </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim mask-pesos" type="text" id="Concentracion" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Concentracion" class="form_text_adv"></p>

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Uso">Uso </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formEditData validarCaracteresEdit stringClass trim" type="text" id="Uso" placeholder="">

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Uso" class="form_text_adv"></p>

                                    </div>
                                </div>

                                <div class="field item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Formulacion">Formulación </label>
                                    <div class="col-md-6 col-sm-6">

                                        <input type="checkbox" class="RolChecks form-check-input formEditDataChecked " disabled id='Formulacion'>


                                    </div>
                                </div>

                                <!-- -------------------------------------------------  -->

                                <div class="containerItems_edit" id="formItems_edit">

                                    <div id="itemsSecundarios_edit"></div>
                                    <div id="itemsPrincipal_edit"></div>

                                </div>

                                <div class="field item form-group d-flex justify-content-center">

                                    <strong>
                                        <h6 style="color: black;" id="infoPorcentajeItemsEdit"></h6>
                                    </strong>

                                </div>
                                <!-- -------------------------------------------------  -->



                                <!-- ------------------------------  -->

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary btnAceptarEdit">Actualizar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

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

                    <h4>¿Desea deshabilitar el producto? </h4>
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

                    <h4>¿Desea Habilitar el producto? </h4>
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


</div>


<div style="display: none;">
<div class="divider divider-Dotted">
            <label id="reactivosTitle">Formulas</label>
        </div>
    <div class="boxItemDefaul">
  
        <div class="field item form-group">
            <label class="col-form-label col-md-3 col-sm-3  label-align">PORCENTAJE </label>
            <div class="col-md-6 col-sm-6">



                <!-- -----------------------------------------  -->


                <div class="row mb-3">
                    <div class="col-4">

                        <input onkeyup="sobreinput(event); statusPorcentaje(this.id)" autocomplete="off" class="form-control formDataExample validarDataExample Porcentaje_exampleClass stringClass trim" type="number" id="Porcentaje_example" placeholder="">
                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Porcentaje_example" class="form_text_adv"></p>

                    </div>
                    <div class="col-7">

                        <select onchange="sobreSelectData(event)" class=" form-control formDataExample validarDataExample ProductoPrimario_exampleClass" id='ProductoPrimario_example'></select>
                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ProductoPrimario_example" class="form_text_adv"></p>

                    </div>
                    <div class="col-1">

                        <div class="containerBtnsDefault" style="padding-left:10px"></div>

                    </div>

                </div>


                <!-- -----------------------------------------  -->


            </div>
        </div>


        <!-- <div class="field item form-group"> -->
        <!-- <label class="col-form-label col-md-3 col-sm-3  label-align">Producto primario </label> -->
        <!-- <div class="col-md-6 col-sm-6"> -->

        <!-- <select class="selectSearch form-control" id='ProductoPrimario_example' > -->
        <!-- </div> -->

        <!-- </div> -->

    </div>

</div>


<!-- --------------------------------------------------------------------------------------------Evidencias -->

<!-- ----------------------------------------------Modal Alta Evidencias-->


<div class="modal fade modal-subir-archivos-alta" id="modalEvidenciasAlta" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Subir Hoja de seguridad</h4>

                <!-- <button type="button" class="btn-close closeModalEvidenciasAlta" data-bs-dismiss="modal" aria-label="Close"></button> -->


            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <form enctype="multipart/form-data" class="dropzone" id="dropzone-archivos-alta" style="width:100%;">

                            <div class="dz-message" data-dz-message><span>Elige el archivo para subir</span></div>


                            <input type="hidden" name="Accion" value="productos" />
                            <input type="hidden" name="Tabla" value="archivosproductos" />
                            <input type="hidden" id="ProductosEvidenciaIdAlta" name="Id" value="null" />

                        </form>

                    </div>

                </div>
                <i>Máximo 10 archivos, 10 megas por archivo </i>
            </div>


            <div class="modal-footer">

                <button type="button" class="btn btn-secondary " data-dismiss="modal">Cerrar</button>

            </div>

        </div>

    </div>

</div>


<!-- -------------------------------------------------Modal Edit Evidencias -->


<div class="modal fade modal-subir-archivos" id="modalEvidencias" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title">Subir Hoja de seguridad</h4>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>



            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="x_content">

                            <div class="row noselected" id="containerEvidenciasUserEdit">

                                <p class="text-center" style="width:100%;">No hay Hoja de seguridad.</p>


                                <!-- ------------------------------  -->


                                <!-- ------------------------------------  -->
                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <form enctype="multipart/form-data" class="dropzone" id="dropzone-archivos" style="width:100%;">

                            <div class="dz-message" data-dz-message><span>Elige el archivo para subir</span></div>

                            <input type="hidden" name="Accion" value="productos" />
                            <input type="hidden" name="Tabla" value="archivosproductos" />
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


<!-- --------------------------------------------------------------------------------------------  -->



<script src="https://code.jquery.com/jquery-3.3.1.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

<?php include '../../requerimientos/headers/footer.php' ?>



<script src="js/productos.js"></script>

<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Dropzone -->
<script src="../../requerimientos/vendors/dropzone/dist/min/dropzone.min.js"></script>

<script type="text/javascript">
    subirArchivosAlta();
    subirArchivosEdit();
</script>


<!-- SELECT SEARCH  -->
<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>