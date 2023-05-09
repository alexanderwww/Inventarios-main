<?php include '../../requerimientos/headers/header.php' ?>

<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">

<!-- page content including menus -->

<link rel="stylesheet" href="css/style.css">

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Formulación /</span> Lista de formulacíon</h4>

    <!-- ---------------  -->


    <form id="formAlta">

        <div class="card mb-4">

            <div class="row px-4 py-3" style="align-items: center;">


                <div class="col-sm">
                    <!-- <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2"> -->

                    <label class="label-align" for="Usuario_alta">Usuario *</label>

                    <input autocomplete="off" readonly class="form-control" type="text" placeholder="" value="<?php echo $_SESSION['Nombre'] ?>">

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Usuario_alta" class="form_text_adv"></p>


                </div>

                <!-- <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3"> -->
                <div class="col-sm">

                    <label class="label-align" for="UsuarioAsignado_alta">Usuario asignado *</label>

                    <div style="width: 100%;">
                        <select onchange="sobreSelectData(event)" class="form-control formAltaData validarDataAlta form-select" id='UsuarioAsignado_alta'></select>
                    </div>
                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_UsuarioAsignado_alta" class="form_text_adv"></p>


                </div>

                <!-- <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3"> -->
                <div class="col-sm">

                    <label class="label-align" for="Producto_alta">Producto *</label>

                    <div style="width: 100%;">
                        <select onchange="sobreSelectData(event)" class="js-states form-control formAltaData validarDataAlta form-select" id='Producto_alta'></select>
                    </div>

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Producto_alta" class="form_text_adv"></p>


                </div>

                <!-- <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2"> -->
                <div class="col-sm">

                    <label class="label-align" for="CantidadFabricar_alta">Cantidad a fabricar *</label>



                    <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData borderRadiusInputLiters validarDataAlta stringClass trim" type="text" id="CantidadFabricar_alta" placeholder="">

                        <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
                    </div>

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_CantidadFabricar_alta" class="form_text_adv"></p>

                </div>

                <!-- <div class=" col-sm-6 col-md-4 col-lg-3 col-xl-2"> -->
                <div class="col-sm">


                    <label class="label-align" for="Costo_alta">Costo de fabricación *</label>

                    <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                        <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                        <input onkeyup="sobreinput(event);" autocomplete="off" class="form-control borderRadiusInputPrice formAltaData validarDataAlta stringClass trim" type="text" id="Costo_alta" placeholder="" readonly>
                    </div>

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Costo_alta" class="form_text_adv"></p>

                </div>

            </div>
            <div class="row justify-content-start">
                <div class="col-md-6 col-sm-6 containerNombre" style="display: none; margin-left: -1px; border-radius: 0;">
                    <label class="label-align col-md-10 col-sm-6">Nombre</label>
                    <!-- <div class="col-form-label col-md-6 col-sm-6 width100" style="display: flex; margin-left: -1px; border-radius: 0;"> -->
                    <input autocomplete="off" class="form-control subNombre col-sm-6 col-md-2 col-lg-3 col-xl-4 formAltaData" style="width: 50%;" type="text" id="subNombre_alta">
                    <!-- </div> -->

                </div>
            </div>


            <!-- <div class="row px-5 py-3">

                <div class="col-sm-6">

                    <label class="label-align" for="InventarioActual_alta">Inventario actual del producto*</label>

                    <input readonly onkeyup="sobreinput(event);" autocomplete="off" class="form-control formAltaData" type="number" id="InventarioActual_alta" placeholder="">

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_InventarioActual_alta" class="form_text_adv"></p>


                </div>

                <div class="col-sm-6">

                    <label class="label-align" for="InventarioDespues_alta">Inventario despues *</label>

                    <input readonly onkeyup="sobreinput(event);" autocomplete="off" class="form-control" type="number" id="InventarioDespues_alta" placeholder="">

                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_InventarioDespues_alta" class="form_text_adv"></p>


                </div>

            </div> -->



            <div class="row px-5 py-3">

                <button type="button" class="btn btn-primary btnAceptarAlta">Registrar</button>

            </div>


        </div>

    </form>

    <!-- ---------------  -->

    <!-- ---------------------------------------  -->

    <div class="card text-center containerPrincipalTabs">
        <div class="card-header">

            <ul class="nav nav-pills card-header-pills containerButtonTabs" role="tablist"></ul>

        </div>

        <div class="card-body">

            <div class="tab-content p-0" id="containerTablesTabs"></div>

        </div>

    </div>

    <!-- ---------------------------------------  -->




    <!-- ----------------------------------------------------------------------------->



</div>


<!-- -----------------------------------------------------------------------Container Temoplate Table------>


<div style="display: none;">


    <div class="boxTemplateTable">



        <!-- -----------------------------------------------  -->
        <!-- <div class="row px-3 py-25 d-flex justify-content-center">
    <div class="col-sm-2">

    <label class="label-align">Inventario actual</label>

    </div>
    <div class="col-sm-2">

    <label class="label-align">Inventario despues</label>
    
    </div>
    <div class="col-sm-2">

    <label class="label-align">Costo por Barril</label>
    
    </div>

</div> -->
        <div class="row px-3 py-25 d-flex justify-content-center" style="align-items: center;">

            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-4 ">

                <label class="label-align">Inventario actual</label>




                <div class="col-form-label col-md-6 col-sm-6 width100" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                    <input readonly autocomplete="off" class="form-control borderRadiusInputLiters width100 InventarioActualItem_Example" type="text" id="" placeholder="">
                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
                </div>


            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-4 ">

                <label class="label-align">Inventario despues</label>
                <!-- borderRadiusInputLiters width100% -->
                <div class="col-form-label col-md-6 col-sm-6 width100" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                    <input readonly autocomplete="off" class="form-control borderRadiusInputLiters width100   InventarioDespuesItem_Example" type="text" id="" placeholder="">
                    <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>
                </div>

            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-4 ">

                <label class="label-align">Costo por Barril</label>

                <div class="width100" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                    <span class="input-group-text " style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>
                    <input readonly autocomplete="off" class="form-control borderRadiusInputPrice width100 CostoBarrilItem_Example " type="text" id="" placeholder="">
                </div>
                <!-- <input readonly autocomplete="off" class="form-control CostoBarrilItem_Example borderRadiusInputPrice" type="number" id="" placeholder=""> -->

            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 col-xl-4 ">
                <label class="label-align">Nombre</label>
                    <div class="width100" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">
                        <input disabled autocomplete="off" class="form-control subNombre formAltaData NombreItem_Example"  type="text" id="">
                    </div>
            </div>

        </div>

        <!-- <div class="row justify-content-start">
                <div class="col-12 p-2 " style=" margin-left: -1px; border-radius: 0;">
                    <label class="label-align">Nombre</label>
                    <input disabled autocomplete="off" class="form-control subNombre formAltaData NombreItem_Example" style="width: 50%;" type="text" id="">

                </div>
            </div> -->
        <!-- -----------------------------------------------  -->

        <div class="row justify-content-start">


            <div class="col-12 p-2">
                <label for="">Crear nueva versión</label>
                <input type="checkbox" class="mx-3 RolChecks form-check-input checkHabilitarEdit">

            </div>

        </div>


        <div class="table-responsive text-nowrap m-3 inline " style="min-height:400px">

            <table class="table" id="tablaExample">
                <thead>
                    <tr>
                        <!-- <th>Id</th> -->

                        <th>Nombre Producto</th>

                        <th>Total Producto</th>
                        
                        <th>Unidad</th>


                        <th>Porcentaje</th>

                        <!-- <th>Version</th> -->

                        <th>Barriles para producción</th>

                        <th>Litro para producción</th>

                        <th>Costo por litro</th>

                        <th>Importe total</th>

                        <th>agregar</th>

                        <th>Eliminar</th>

                    </tr>
                </thead>

                <tbody></tbody>

            </table>

        </div>

    </div>


</div>





<div>

</div>








<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>

<?php include '../../requerimientos/headers/footer.php' ?>

<script src="js/formulacion.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>