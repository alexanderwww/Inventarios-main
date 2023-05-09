<?php include '../../requerimientos/headers/header.php' ?>


<link rel="stylesheet" href="css/style.css">



<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Facturación /</span> Lista de Facturación</h4>



    <div class="card">

        <h5 class="card-header inline-head">

            <?php if ($_SESSION['Rol']['odtC'] == 1) : ?>

                <a href="./altaFacturacion.php" class="btn btn-primary derecha btnModalAlta"><i class='bx bx-plus'></i>Facturación</a>

            <?php endif ?>

        </h5>

        <div class="table-responsive text-nowrap m-3 inline">



            <table class="table" id="tablaPrincipal">

                <thead>

                    <tr>

                        <th>Acciones</th>
                        <!-- <th>Id</th> -->
                        <th>Folio Factura</th>
                        <!-- <th>Odt</th> -->
                        <th>Estatus de factura</th>
                        <th>Generado por</th>
                        <th>Moneda</th>
                        <th>Cliente</th>
                        <th>Importe</th>
                        <th>Fecha de factura</th>
                        <th>Fecha de vencimiento</th>
                        <!-- <th>Folio Pago</th> -->
                        <th>Estatus de pago</th>

                    </tr>

                </thead>

                <tbody></tbody>

            </table>

        </div>

    </div>



    <!-- ----------------------------------------------------------------------------->





</div>





<!-- --------------------------------------------------------------------------------------------  -->




<div class="modal fade" id="modalStatus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Factura <strong class="numFactura"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="labelOdv">¿Desea timbrar la factura <strong class="numFactura" ></strong> ?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btnAcepta_modalStatus">Aceptar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancenlar</button>
            </div>
        </div>
    </div>
</div>







<!-- ----------------------------------------------------------------------------------------------  -->



<!-- ------------------------------------------------------- Modal Edit -->





<div class="modal fade" id="modalView" role="dialog" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">



            <div class="modal-header">

                <h5 class="modal-title"> Orden de trabajo <strong id="modalViewTitle"></strong></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>

            </div>



            <div class="modal-body">



                <div class="row">

                    <div class="col-md-12 col-sm-12  ">



                        <form id="formView">







                            <!-- * -->



                            <!-- ------------------------------  -->



                            <div class="container">



                                <div class="row mb-3">



                                    <!-- ---------------------------  -->



                                    <div class="col-6">



                                        <div class="my-3">



                                            <label># Orden de trabajo </label>



                                            <input class="form-control" type="text" id="odt_view" disabled>



                                        </div>



                                        <div class="my-3">

                                            <label>Cantidad a fabricar </label>

                                            <input class="form-control" type="text" id="cantidadFabricar_view" disabled>

                                        </div>



                                    </div>



                                    <!-- ---------------------------  -->



                                    <div class="col-6">



                                        <div class="my-3">



                                            <label>Usuario </label>

                                            <input class="form-control" type="text" id="usuario_view" disabled>



                                        </div>





                                        <div class="my-3">



                                            <label>Costo de fabricación </label>

                                            <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                                                <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                                                <input style=" margin-left: -1px; border-radius:  0 5px 5px  0;" class="form-control" type="number" id="costo_view" readonly>

                                            </div>



                                        </div>



                                    </div>



                                    <!-- ---------------------------  -->



                                </div>



                            </div>



                            <!-- -------------------------------------------------  -->



                            <div class="container">



                                <div class="row mb-3">



                                    <!-- ---------------------------  -->



                                    <div class="col-6">



                                        <div class="my-3">



                                            <label>Usuario asignado </label>



                                            <input class="form-control" type="text" id="usuarioAsignado_view" disabled>



                                        </div>



                                        <div class="my-3">



                                            <label>Inventario actual </label>

                                            <input class="form-control" type="text" id="inventarioActual_view" disabled>



                                        </div>

                                    </div>



                                    <!-- ---------------------------  -->



                                    <div class="col-6">



                                        <div class="my-3">



                                            <label>Producto </label>

                                            <input class="form-control" type="text" id="producto_view" disabled>



                                        </div>





                                        <div class="my-3">

                                            <label>Inventario Despues </label>

                                            <input class="form-control" type="text" id="inventarioDespues_view" disabled>

                                        </div>



                                    </div>



                                    <!-- ---------------------------  -->

                                </div>



                            </div>



                            <!-- -------------------------------------------------  -->

                            <div class="divider divider-Dotted">

                                <div class="divider-text"><span><strong>Productos</strong></span></div>

                            </div>



                            <div class="containerItemsView" id="formItemsView">



                                <div id="itemsSecundariosView"></div>

                                <div id="itemsPrincipalView"></div>



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





<!-- ---------------------Template modal vista Item-----------------------------------------  -->



<div style="display: none;">



    <div class="boxItemDefaul">



        <div class="container">



            <div class="col-md-12 col-sm-12">

                <!-- -----------------------------------------  -->

                <div class="row mb-3">

                    <div class="my-3  col-lg-4 col-md-4 col-sm-6">



                        <label>Producto </label>

                        <div style="width: 100%;">

                            <input class=" form-control" id='producto_example' disabled />

                        </div>

                    </div>

                    <div class="my-3 col-lg-4 col-md-4 col-sm-6">

                        <label>Precion por litro </label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text" style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input class="form-control borderRadiusInputPrice" id='precioPorLitro_example' disabled />

                        </div>



                    </div>

                    <div class="my-3 col-lg-4 col-md-3 col-sm-3">

                        <label>Litros para Producción</label>



                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input class="form-control borderRadiusInputLiters " type="number" id="litrosPorProduccion_example" disabled />

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                    </div>



                </div>





                <div class="row mb-3">



                    <div class="my-3 col-lg-4 col-md-3 col-sm-4">

                        <label>Litros por barril </label>





                        <div class="col-form-label col-md-6 col-sm-6" style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <input class=" form-control borderRadiusInputLiters" type="number" id="litrosPorBarriol_example" disabled />

                            <span class="input-group-text" style=" margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">LTS</span>

                        </div>



                    </div>



                    <div class="my-3 col-lg-4 col-md-4 col-sm-6 ">

                        <label>Importe </label>



                        <div style="display: flex; margin-left: -1px; border-top-left-radius: 0;border-bottom-left-radius: 0;">

                            <span class="input-group-text " style=" margin-left: -1px; border-top-right-radius: 0;border-bottom-right-radius: 0;">$</span>

                            <input class="form-control borderRadiusInputPrice" type="number" id="importe_example" disabled />

                        </div>



                        

                    </div>



                    <div class="my-3 col-lg-4 col-md-4 col-sm-6 ">

                        <label>Estatus </label>

                        <input class="form-control" type="text" id="estatus_example" disabled />

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



<!-- -----------------------------------------------------------------------------------------------  -->






<script src="../../requerimientos/vendors/jquery/dist/jquery.min.js"></script>


<script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>



<?php include '../../requerimientos/headers/footer.php' ?>


<script src="js/facturacion.js"></script>


<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>