<?php
session_start();
include '../../requerimientos/headers/constantes.php';
// print_r($_SESSION['ImgUser']);
$moneda = $_SESSION['Moneda'] == 'Pesos' ? '' : 'checked';
if (!isset($_SESSION['IdUser'])) {
  session_destroy();
  header('location:../login/index.php');
}
	$host= $_SERVER["HTTP_HOST"];
	$recUri= $_SERVER["REQUEST_URI"];
  if($host == 'localhost'){
    $urlCompleto=$host.$recUri;
    $url=parse_url($urlCompleto);
	  $dir=explode("/",$url["path"]);
    // print_r($urlCompleto);
    // print_r("\n");
    // print_r($url);
    // print_r("\n");
    // print_r($dir);
    // if (!in_array($dir[4],$_SESSION['listaAccesso'])) {
    //   header('Location: '.constant('INIT'));
    //   exit;
    // }
  }else{
    // print_r('Host');
    // print_r($urlCompleto);
    // print_r("\n");
    // print_r($url);
    // print_r("\n");
    // print_r($dir);
    $urlCompleto=constant('HTTP/S').$host .$recUri;
    $url=parse_url($urlCompleto);
	  $dir=explode("/",$url["path"]);
    // if (!in_array($dir[3],$_SESSION['listaAccesso'])) {
    //   header('Location: '.constant('INIT'));
    //   exit;
    // }
  }
	
	// $urlCompleto=$host .$recUri;
  
  
	// $url=parse_url($urlCompleto);
	// $dir=explode("/",$url["path"]); 
  // // print_r($urlCompleto);
  // // print_r("\n");
  // // print_r($url);
  // // print_r("\n");
  // // print_r($dir);
  // // die;
	// if (!in_array($dir[5],$_SESSION['listaAccesso'])) {
	// 	header('Location: '.constant('INIT'));
	// 	exit;
	// }

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template-free">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title id="titlePage"></title>
  <link rel="icon" type="image/x-icon" href="../../requerimientos/imgGeneral/favicon.ico" />

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />
  <!-- App IntGeneral -->

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../../assets/css/demo.css" />
  <!-- PNotify -->
  <link href="../../requerimientos/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="../../requerimientos/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="../../requerimientos/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->

  <!-- ---------------------Checked TIPO DE MONEDA  -->
  <link rel="stylesheet" href="../../requerimientos/headers/header.css">
  <!-- <script src="../../requerimientos/headers/header.js"></script> -->
  <!-- ---------------------  -->



  <!-- Helpers -->
  <script src="../../assets/vendor/js/helpers.js"></script>
  <!-- DataTable Bootstrap4 CSS -->
  <link rel="stylesheet" type="text/css" href="../../requerimientos/vendors/DataTables-1.10.24/css/dataTables.bootstrap4.min.css">
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../../assets/js/config.js"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
     <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="../proveedores/index.php" class="app-brand-link">
            <span class="app-brand-logo demo">
              <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              </svg>
            </span>
            <a href="../inventario/index.php">
              <img src="../../requerimientos/imgGeneral/5inco.png" width="100%" height="100%" alt="">
            </a>
            <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">5in<span style="text-transform: uppercase;">c</span>o</span> -->
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item active">
            <a href="../inventario/index.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div>Inicio</div>
            </a>
          </li>

          <li class="menu-header small text-uppercase">
            <!-- <span class="menu-header-text">Administración</span> -->
          </li>
          <?php if(($_SESSION['Rol']['proveedorR'] == 1) || ($_SESSION['Rol']['clienteR'] == 1) || ($_SESSION['Rol']['usuarioR'] == 1) || ($_SESSION['Rol']['prductoR'] == 1)): ?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i class="menu-icon tf-icons bx bxs-user"></i>
              <div data-i18n="Account Settings">Catalogos</div>
            </a>
            <ul class="menu-sub">
            <?php if($_SESSION['Rol']['proveedorR'] == 1): ?>
              <li class="menu-item">
                <a href="../proveedores/index.php" class="menu-link">
                  <div data-i18n="Account">Proveedores</div>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['Rol']['clienteR'] == 1): ?>
              <li class="menu-item">
                <a href="../clientes/index.php" class="menu-link">
                  <div data-i18n="Account">Clientes</div>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['Rol']['usuarioR'] == 1): ?>
              <li class="menu-item">
                <a href="../usuarios/index.php" class="menu-link">
                  <div data-i18n="Account">Usuarios</div>
                </a>
              </li>
              <?php endif?>
              <li class="menu-item">
                <a href="../roles/index.php" class="menu-link">
                  <div data-i18n="Account">Roles</div>
                </a>
              </li>
              <?php if($_SESSION['Rol']['prductoR'] == 1): ?>
              <li class="menu-item">
                <a href="../productos/index.php" class="menu-link">
                  <div data-i18n="Account">Productos</div>
                </a>
              </li>
              <?php endif?>
            </ul>
          </li>
          <?php endif?>


          <?php if(($_SESSION['Rol']['odtR'] == 1) || ($_SESSION['Rol']['ajustesR'] == 1)): ?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <!-- <i class='bx bx-user' ></i> -->
              <i class="menu-icon tf-icons bx bx-edit"></i>
              <!-- <i class='bx bxs-user-account'></i> -->
              <div data-i18n="Account Settings">Producción</div>
            </a>
            <ul class="menu-sub">
            <?php if($_SESSION['Rol']['odtR'] == 1): ?>
              <li class="menu-item">
                <a href="../odt/index.php" class="menu-link">
                  <div data-i18n="Account">Formulación</div>
                </a>
              </li>
              <?php endif?>
              <?php if($_SESSION['Rol']['ajustesR'] == 1): ?>
              <li class="menu-item">
                <a href="../ajustes/index.php" class="menu-link">
                  <div data-i18n="Account">Ajustes</div>
                </a>
              </li>
              <?php endif?>
            </ul>
          </li>
          <?php endif?>
          
          <?php if($_SESSION['Rol']['notasVR'] == 1): ?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <!-- <i class='bx bx-user' ></i> -->
              <i class="menu-icon tf-icons bx bxs-file"></i>
              <div data-i18n="Account Settings">Ventas</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="../odv/index.php" class="menu-link">
                  <div data-i18n="Account">Pedidos</div>
                </a>
              </li>
              <?php if($_SESSION['Rol']['cotizadorR'] == 1): ?>
              <li class="menu-item">
                <a href="../cotizador/index.php" class="menu-link">
                  <div data-i18n="Account">Cotizador</div>
                </a>
              </li>
              <?php endif?>
            </ul>
          </li>
          <?php endif?>
          <?php if($_SESSION['Rol']['comprasR'] == 1): ?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <!-- <i class='bx bx-user' ></i> -->
              <i class="menu-icon tf-icons bx bxs-store"></i>
              <div data-i18n="Account Settings">Compras</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="../compras/index.php" class="menu-link">
                  <div data-i18n="Account">Ordenes de compras</div>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <!-- <i class='bx bx-user' ></i> -->
              <i class="menu-icon tf-icons bx bxs-credit-card"></i>
              <div data-i18n="Account Settings">Finanzas</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="../facturacion/index.php" class="menu-link">
                  <div data-i18n="Account">Facturación</div>
                </a>
                <a href="../cp/index.php" class="menu-link">
                  <div data-i18n="Account">Complemento de Pago</div>
                </a>
                <a href="../notascredito/index.php" class="menu-link">
                  <div data-i18n="Account">Notas de Crédito</div>
                </a>
                <a href="../cxp/index.php" class="menu-link">
                  <div data-i18n="Account">Cuentas por Pagar</div>
                </a>

                <a href="../cxc/index.php" class="menu-link">
                  <div data-i18n="Account">Cuentas por Cobrar</div>
                </a>
                <!-- <a href="../gastos/index.php" class="menu-link">
                  <div data-i18n="Account">Recepción de Gastos</div>
                </a> -->
              </li>
            </ul>
          </li>
          <?php if($_SESSION['Rol']['bitacoraR'] == 1): ?>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <!-- <i class='bx bx-task' ></i> -->
              <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
              <div data-i18n="Account Settings">Reportes</div>
            </a>
            <ul class="menu-sub">
              <li class="menu-item">
                <a href="../bitacora/index.php" class="menu-link">
                  <div data-i18n="Account">Bitacora</div>
                </a>
              </li>
            </ul>
          </li>
          <?php endif?>
        </ul>
      </aside>
      <div class="layout-page">
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            <div class="navbar-nav align-items-center d-flex justify-content-between flex-row" style="width: 100%;">

              <div class="d-flex align-items-center  gap-2 responsiveMovil">
                <span>Tipo de Cambio: </span>
                <input class="form-control " style="width: auto;" type="number"  id="TC_input"  value="<?php echo $_SESSION['TC'] ?>">
                
                <input type="hidden" id="inputValueTc" value="<?php echo $_SESSION['TipoCambio'] ?>">
                <input type="hidden" id="monedaGlobal" value="<?php echo $_SESSION['Moneda'] ?>">
                <a id="btnResetTipoDeCambio" for=""><small>Tipo de cambio DOF</small></a>
              </div>

              <div class="containerCheckTipoMoneda responsiveMovil">

                <label class="toggle">
                  <span class="toggle-label ">Pesos</span>
                  <input class="toggle-checkbox" type="checkbox" id="checkTipoDeMoneda" <?php echo $moneda ?> >
                  <div class="toggle-switch"></div>
                  <span class="toggle-label">Dólares</span>
                </label>




                <!-- <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"> -->
                <!-- <span style="font-size: 1.2rem;">Pesos</span> -->
              </div>



            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img style="max-height: 40px;" src="<?php echo $_SESSION['ImgUser']; ?>" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img style="max-height: 40px;" src="<?php echo $_SESSION['ImgUser']; ?>" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block"><?php echo $_SESSION['Nombre'] ?></span>
                          <small class="text-muted"><?php echo $_SESSION['Rol']['nombre'] ?></small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">Perfil</span>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="<?php echo constant('URLLOGOUT'); ?>">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Cerrar sesion</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>

          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->