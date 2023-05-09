<?php include '../../requerimientos/headers/header.php' ;

// print_r($_SESSION['listaAccesso']);

?>





<link rel="stylesheet" href="./css/style.css">



<!-- page content including menus -->

<style>







</style>

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ajustes /</span> Lista de Ajustes</h4> -->



    <div class="card">

         <h5 class="card-header inline-head">

            <!-- <button type="button" class="btn btn-primary derecha btnModalAlta" >Ajustes <i class='bx bxs-user-plus'></i></button> -->

            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Inventario' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">



        </h5>



        <div class="table-responsive text-nowrap m-3 inline">

            <table class="table" id="tablaAjustes">

                <thead>

                    <tr>

                        <th>Id</th>



                        <th>Producto</th>



                        <th>Total (Litros)</th>



                        <th>Precio por litro</th>



                        <th>Valor</th>



                        <!-- <th>Salida</th>



                        <th>Existencia Despues de ajustes</th>



                        <th>Observaciones</th>



                        <th>Fecha de Ajuste</th> -->



                        <!-- <th>Formulacion</th> -->



                        <!-- <th>Editar</th> -->

                    </tr>

                </thead>

                <tbody></tbody>

                <tfoot>

                    <tr>

                        <th colspan="1" style="text-align:right">Inventario Producto:</th>

                        <th></th>

                        <th colspan="1" style="text-align:right">Inventario Total:</th>

                        <th></th>

                    </tr>



                </tfoot>

            </table>

        </div>

    </div>



    <!-- ------------------------------------------------------- Modal Alta -->





    <div class="modal fade" id="modalAlta" role="dialog" aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">



                <div class="modal-header">

                    <h5 class="modal-title">Ajuste de material</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>

                </div>



                <div class="modal-body">



                    <div class="row">

                        <div class="col-md-12 col-sm-12  ">



                            <form id="formAlta">







                                <!-- * -->

                                <div class="field item form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Nombre_user">Usuario</label>

                                    <div class="col-md-6 col-sm-6">

                                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control " type="text" id="Nombre_user" value="<?php echo $_SESSION['Nombre'] ?>" placeholder="<?php echo $_SESSION['Nombre'] ?>" disabled>

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nom_userlta" class="form_text_adv"></p>

                                    </div>

                                </div>



                                <div class="field item form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align defaultFormControlInput">Productos *</label>

                                    <div class="col-md-6 col-sm-6">

                                        <!-- <select class="selectSearch form-control" id='ProductoPrimario_example' > -->

                                        <select onchange="sobreSelectData(event)" class=" form-control formDataExample validarDataExample ProductoPrimario_example validarDataAlta formAltaData form-select" id='ProductoPrimario_example'>

                                        </select>

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_ProductoPrimario_example" class="form_text_adv"></p>

                                    </div>

                                </div>



                                <div class="field item form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Existente_alta">Material Existente</label>

                                    <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">



                                        <input onkeyup="sobreinput(event);" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" autocomplete="off" class="form-control formAltaData  stringClass trim" type="number" id="Existente_alta" placeholder="" disabled>

                                        <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Existente_alta" class="form_text_adv"></p>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Entrada_alta">Entrada material</label>

                                    <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                        <!-- <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span> -->

                                        <input onkeyup="sobreinput(event);" autocomplete="on" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control EntradaSalida formAltaData  stringClass trim" type="number" id="Entrada_alta" placeholder="" min="1">

                                        <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>

                                        <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Entrada_alta" class="form_text_adv"></p>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Salida_alta">Salida material</label>

                                    <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                        <!-- <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span> -->

                                        <input onkeyup="sobreinput(event);" autocomplete="on" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control EntradaSalida formAltaData  stringClass trim" type="number" id="Salida_alta" placeholder="" min="1">

                                        <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label class="col-form-label col-md-3 col-sm-3  label-align" for="Despues_alta">Existencia de material despues de ajuste</label>

                                    <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                        <!-- <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span> -->

                                        <input onkeyup="sobreinput(event);" autocomplete="off" style=" margin-left: -1px; border-radius:  5px 0 0  5px;" class="form-control formAltaData  stringClass trim" type="number" id="Despues_alta" placeholder="" disabled>

                                        <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">Lts</span>

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











    <!-- --------------------------------------------------------------------------------------------  -->





    <!-- <script src="../../requerimientos//vendors/dropzone/dist/min/dropzone.min.js"></script> -->





    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>

    <!-- <script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script> -->





    <script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



    <?php include '../../requerimientos/headers/footer.php' ?>



    <!-- <script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script> -->





    <!-- Dropzone -->

    <!-- <script src="../../requerimientos/vendors/dropzone/dist/min/dropzone.min.js"></script> -->





    <script src="js/inventario.js"></script>



    <!-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> -->









    <!-- Bootstrap -->



    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->





    <!-- SELECT SEARCH -->



    <script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>