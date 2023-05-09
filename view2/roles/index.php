<?php include '../../requerimientos/headers/header.php' ?>
<!-- <script src="../../jsGeneral/appInt.js"></script> -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!-- CSS SmartWizard -->
<link href="../../requerimientos/vendors/wizardLibrery/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
<!-- switchery -->
<link rel="stylesheet" href="../../requerimientos/vendors/togle_switchery/dist/switchery.css">


<!-- Page Content including Menu new template -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Roles /</span> Lista de Roles</h4>

    <div class="card">
        <h5 class="card-header inline-head">
            <?php if($_SESSION['Rol']['rolC'] == 1):?>
                <button id='btnNuevoUsuario' type="button" class="btn btn-primary derecha btnAltaRol" data-bs-toggle="modal" data-bs-target="#altaRol">Rol <i class='bx bxs-user-plus'></i></button>
            <?php endif ?>
        </h5>
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaRoles">
                <thead>
                    <tr>
                    <th>Acciones</th>
                    <th>Id</th>
                    <th>Nombre Rol</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Final Content new template -->
                                <!------------------------------------- Modal Nuevo Usuario  -->
                                <div class="modal fade" id="altaRol" tabindex="-1" role="dialog" aria-labelledby="altaRolLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="altaRolLabel">Alta Rol</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <!-- <span aria-hidden="true">&times;</span> -->
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12  ">
                                                        <form id="formAltaRol" method="POST" class="" enctype="multipart/form-data">
                                                            <div class="form-group row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                                <label class="control-label pull-right" style="float: right; margin: 10px;" for="rol">Rol</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                                <input class="form-control " aria-describedby="floatingInputHelp" placeholder="Nombre de Rol" type="text" name="rol" id="rol" autocomplete="off" autofocus="">
                                                                <p style="display:none;" id="ul_rol">
                                                                No has ingresado un rol
                                                                </p>
                                                            </div>
                                                                <!-- <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3"></div> -->
                                                            </div>
                                                            <div id="contCheck" class="container row"></div>
                                                            <!-- <input class="form-check-input" type="checkbox" value="" id="defaultCheck3" checked /> -->

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary btnGuardarRol" >Registrar</button>
                                                                <button id="CancelarRol" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!------------------------------------- Modal Edit Usuario  -->

                                <div class="modal fade" id="editRol" tabindex="-1" role="dialog" aria-labelledby="EditUsuarioLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="EditUsuarioLabel">Editar Usuarios</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                </button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12  ">

                                                        <form id="formEditUsuario" class="" method="POST" enctype="multipart/form-data">
                                                            <div class="form-group row">
                                                            <div class="col-lg-4 col-md-4 col-sm-4 ">
                                                                <label class="control-label " style="float: right; margin: 10px;" for="rol">Rol</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-sm-4">
                                                                <input class="form-control " aria-describedby="floatingInputHelp" placeholder="Nombre de Rol" type="text" name="rol" id="rolEditName" autocomplete="off" autofocus="" disabled>
                                                                <p style="display:none;" id="ul_rol">
                                                                No has ingresado un rol
                                                                </p>
                                                            </div>
                                                                <!-- <div class="col-lg-3 col-md-3 col-sm-3"></div>
                                                                <div class="col-lg-3 col-md-3 col-sm-3"></div> -->
                                                            </div>
                                                             <input type="hidden" id="idEdit">
                                                             <!-- <input type="hidden" id="nombre"> -->
                                                            <div id="contCheckEdit" class="container  row"></div>
                                                                            <!-- -----------------------------------------  -->
<!-- 
                                                                            <div class="field item form-group" >
                                                                                <div style="width: 80px;"></div>
                                                                                <div>
                                                                                    <label for="Status">Status</label>
                                                                                    <input type="checkbox" class="js-switch-status form-control  js-check-change formEditarProvedorDataInput dataCheckboxEditar" id="Status" />
                                                                                </div>
                                                                            </div> -->

                                                                            <!-- -----------------------------------------------  -->

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary btnActualizarEdit" >Actualizar</button>
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!------------------------------------- Modal Eliminar Usuario  -->
                                <div class="modal fade" tabindex="-1" id="btnEliminarRol"  role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <!-- <h5 class="modal-title" >Eliminar Usuario: <b id='nameUserModalEliminar'></b></h5> -->

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <p class="modal-title" >¿Seguro que desea eliminar el Rol?</p>
                                            </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger btnModalEliminarRol" data-bs-dismiss="modal" id="eliminar_usuario">Eliminar</button>
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                <!------------------------------------- Modal Reset 2 Factores  -->
                                <div class="modal fade" tabindex="-1" id="btnResetUsuario"  role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" >Resetear 2 Factores a: <b id='nameUserModalDosFactores'></b></h5>

                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <p class="modal-title" >¿Seguro que desea Resetear la autentificación de 2 factores?</p>
                                            </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary btnModalResetUser" data-dismiss="modal" id="reset_usuario">Resetear</button>
                                                </div>
                                            </div>
                                        </div>
                                </div>


                                <!--------------------------------------->
<!-- ----------------------------------------- -->
  <!-- icheck  -->
    <script src="../../requerimientos/vendors/iCheck/icheck.min.js"></script>

    <!-- <script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script> -->
    <?php include '../../requerimientos/headers/footer.php' ?>
    <script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script>
    <script type="text/javascript" src="../../requerimientos/vendors/wizardLibrery/jquery.smartWizard.min.js"></script>
    <script src="js/roles.js"></script>
    <script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>