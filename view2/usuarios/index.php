<?php include '../../requerimientos/headers/header.php';?>

<!-- SELECT SEARCH  -->
<link rel="stylesheet" href="../../requerimientos/vendors/selectSearchChosen/chosen.css">
<!-- CSS SmartWizard -->
<link href="../../requerimientos/vendors/wizardLibrery/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />
<!-- switchery -->
<link rel="stylesheet" href="../../requerimientos/vendors/togle_switchery/dist/switchery.css">


<!-- Page Content including Menu new template -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Usuarios /</span> Lista de Usuarios</h4>

    <div class="card">
        <h5 class="card-header inline-head">
            <input type="button" class="btn bg-success text-white" id="btnExcelTabla" nameTable='Usuarios' style="outline: 0 !important;box-shadow: none !important;" name="<?php echo $_SESSION['Nombre']; ?>" value="Excel">
            <?php if ($_SESSION['Rol']['usuarioC'] == 1) : ?>
                <button id='btnNuevoUsuario' type="button" class="btn btn-primary derecha btnAltaCliente" data-bs-toggle="modal" data-bs-target="#altaUsuario">Usuario <i class='bx bxs-user-plus'></i></button>
            <?php endif ?>
            <button id='btnNuevoUsuario' type="button" class="btn btn-primary derecha btnAltaCliente" style="display:none">Usuario <i class='bx bxs-user-plus'></i></button>
        </h5>
        <div class="table-responsive text-nowrap m-3 inline">
            <table class="table" id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th>Id</th>
                        <th>Estatus</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <!-- <th>Asignar Unidades</th> -->
                        <!-- <th>Reset 2 factores</th> -->

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Final Content new template -->
<!------------------------------------- Modal Nuevo Usuario  -->

<div class="modal fade" id="altaUsuario" tabindex="-1" role="dialog" aria-labelledby="altaUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="altaUsuarioLabel">Alta Usuarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">

                        <form id="formAltaUsurio" method="POST" class="" enctype="multipart/form-data">

                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Usuario">Usuario *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_usuario form-control formDataAlta campoValidarAltaUsuario lowerTrim" type="text" id="Usuario" placeholder="Usuario en minusculas" pattern="[A-Za-z0-9]{1,30}">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Usuario" class="form_text_adv"></p>
                                </div>
                            </div>

                            <!-- ------------------------------  -->
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Nombre">Nombre y Apellido*</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_nombre form-control formDataAlta campoValidarAltaUsuario properTrim" type="text" id="Nombre">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Nombre" class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Email">Correo Electrónico *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_email form-control formDataAlta campoValidarAltaUsuario trim" type="email" id="Email">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Email" class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Password">Contraseña *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_password form-control formDataAlta campoValidarAltaUsuario trim" type="password" id="Password">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Password" class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Rol">Nivel de acceso*</label>
                                <div class="col-md-6 col-sm-6">
                                    <select type='select' class="form_rol form-control formDataAlta select2 select campoValidarAltaUsuario" id="SelectRolAltaUser" autocomplete="off" style="width:100%;">
                                    </select>
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_SelectRolAltaUser" class="form_text_adv"></p>

                                </div>
                            </div>
                            <div class="field item form-group campoAcceso" id="campoAcceso"></div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align" for="Foto">Foto</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="file" id="Foto" class="imagen form_foto campoValidarAltaUsuario" name='fotoUsuario'>
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id="ul_Foto" class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn_FormAltaUser">Registrar</button>
                                <button id="CancelarUsuario" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<!------------------------------------- Modal Edit Usuario  -->

<div class="modal fade" id="editUsuario" tabindex="-1" role="dialog" aria-labelledby="EditUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="EditUsuarioLabel">Edit Usuarios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">

                        <form id="formEditUsuario" class="" method="POST" enctype="multipart/form-data">


                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Usuario *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_usuario form-control formDataEdit campoValidar lowerTrim" type="text" id="UsuarioEdit" pattern="[A-Za-z0-9]{1,30}" readonly>
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_UsuarioEdit' class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nombre y Apellido*</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_nombre form-control formDataEdit campoValidar properTrim" type="text" id="NombreEdit">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_NombreEdit' class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Correo Electrónico *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_email form-control formDataEdit campoValidar trim" type="email" id="EmailEdit">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_EmailEdit' class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Contraseña *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form_password form-control formDataEdit campoValidar trim" type="password" id="PasswordEdit">
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_PasswordEdit' class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Rol*</label>
                                <div class="col-md-6 col-sm-6">
                                    <select type='select' class="form_rol form-control formDataEdit select2 select campoValidar" id="SelectRolUserEdit" autocomplete="off" style="width:100%;">
                                    </select>
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_SelectRolUserEdit' class="form_text_adv"></p>
                                </div>
                            </div>
                            <div class="field item form-group campoAcceso" id="campoAccesoEdit"></div>
                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Foto</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="file" id="FotoEdit" class="imagen form_foto campoValidar" name='fotoUsuario'>
                                    <p style="display:none; font-weight: bold; color: rgb(185, 74, 72)" id='ul_FotoEdit' class="form_text_adv"></p>
                                </div>
                            </div>




                            <!-- -----------------------------------------  -->

                            <div class="field item form-group">
                                <div style="width: 80px;"></div>
                                <div>
                                    <label for="Status">Estatus</label>
                                  
                                    <input type="checkbox" class="js-switch-status form-control  js-check-change formEditarProvedorDataInput dataCheckboxEditar" id="Status" />
                                    <!-- <input type="checkbox" class="form-check-input formEditarProvedorDataInput dataCheckboxEditar " id="Status"> -->

                                </div>
                            </div>

                            <!-- -----------------------------------------------  -->

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn_FormEditUser">Actualizar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>



<!-------------------------------------Modal Asignar Unidadess -->

<div class="modal fade" tabindex="-1" id="btnAsignarUsuario" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignar Unidades a: <b id='nameUserModalAsignarUnidades'></b> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="modal-body row" id="containerListaUnidades"></form>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btnModalAsignarUnidadesUser" data-dismiss="modal" id="asignarUnidades_usuario">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!------------------------------------- Modal Reset 2 Factores  -->
<div class="modal fade" tabindex="-1" id="btnResetUsuario" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Resetear 2 Factores a: <b id='nameUserModalDosFactores'></b></h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-title">¿Seguro que desea Resetear la autentificación de 2 factores?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btnModalResetUser" data-dismiss="modal" id="reset_usuario">Resetear</button>
            </div>
        </div>
    </div>
</div>


<!------------------------------------- Modal View  -->

<div class="modal fade" id="modalView" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Usuario: <span class="modalViewTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12 col-sm-12  ">

                        <form id="formView">


                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Usuario *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class="form-control" type="text" id="UsuarioView" disabled >
                                </div>
                            </div>

                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Nombre y Apellido*</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class=" form-control " type="text" id="NombreView" disabled>
                                </div>
                            </div>

                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Correo Electrónico *</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class=" form-control " type="email" id="EmailView" disabled>
                                </div>
                            </div>



                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Rol*</label>
                                <div class="col-md-6 col-sm-6">
                                    <select type='select' class="form-control" id="SelectRolUserView" autocomplete="off" style="width:100%;" disabled>
                                    </select>
                                </div>
                            </div>

                            <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Estatus*</label>
                                <div class="col-md-6 col-sm-6">
                                    <input autocomplete="off" class=" form-control " type="text" id="estatusView" disabled>
                                </div>
                            </div>

                            <!-- <div class="field item form-group campoAcceso" id="campoAccesoEdit"></div> -->
                            
                            <!-- <div class="field item form-group">
                                <label class="col-form-label col-md-3 col-sm-3  label-align">Foto</label>
                                <div class="col-md-6 col-sm-6">
                                    <input type="file" id="FotoEdit" class="imagen form_foto campoValidar" name='fotoUsuario'>
                                </div>
                            </div> -->



                            <!-- -----------------------------------------  -->

                            <!-- <div class="field item form-group">
                                <div style="width: 80px;"></div>
                                <div>
                                    <label for="Status">Estatus</label>
                                    <input type="checkbox" class="js-switch-status-view form-control  js-check-change" id="StatusView" />
                                </div>
                            </div> -->

                            <!-- -----------------------------------------------  -->

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


<!--------------------------------------->
<!-- ----------------------------------------- -->
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>

<!-- icheck  -->
<script src="../../requerimientos/vendors/iCheck/icheck.min.js"></script>

<!-- <script src="../../requerimientos/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script> -->
<?php include '../../requerimientos/headers/footer.php' ?>
<script src="../../requerimientos/vendors/togle_switchery/dist/switchery.js"></script>
<script type="text/javascript" src="../../requerimientos/vendors/wizardLibrery/jquery.smartWizard.min.js"></script>
<script src="js/usuarios.js"></script>
<script src="../../requerimientos/vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<!-- SELECT SEARCH  -->
<script src="../../requerimientos/vendors/selectSearchChosen/chosen.jquery.js"></script>
