<?php
// include "https.php";
// define("DOMAIN",$_SERVER["HTTP_HOST"]);
// define('URL',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/"); //se usa pocas veces
// define('URLLOGOUT',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/validarsession/logout.php?cerrar");//usado
// define('URLTOLOGIN',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/login/index.php");//usado
// define('URLTOLANDING',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/landpage/index.php");//usado
// define('EDITUS',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/editarusuario/index.php");// usado
// define('USERIMAGE',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/images/userphoto/");//usado en todas las paginas
// define('VRLOGO',constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/images/Logo_VR_Small.png");//usado en todas las paginas
// define('FAVICON',"<link rel='shortcut icon' type='image/ico' href='".constant('HTTP/S').constant('DOMAIN')."/visor/controlterrestre/view/images/favicon.ico'/>");//usado en todas las paginas
// //define('visor',constant('HTTP/S').constant('DOMAIN2').".com");//
// define('COPYRIGHT',"©2025 Todos los derechos reservados.");// usado en todas las paginas
// define("GMPKEY","AIzaSyAACbwwV60OuXFXueePpTsto0sZxqSZkfA");// llave para usar maps para sacar coordenadas
// define("ESTACIONVR","visorexterno");
// define("URLVR",constant('HTTP/S').constant('DOMAIN')."/".constant("ESTACIONVR")."/view/");
// define("PATHCDBVRM",$_SERVER['DOCUMENT_ROOT']."/".constant("ESTACIONVR")."/classes/");
// define("APILANGGMP","es-419");
// define("PATHROLCP",$_SERVER['DOCUMENT_ROOT']."/"."cartaporte"."/view/opciones/roles.json");
include "https.php";
define("DOMAIN",$_SERVER["HTTP_HOST"]);
define('URL',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/"); //se usa pocas veces
define('URLLOGOUT',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/validarsession/logout.php?cerrar");//usado
define('INIT',constant('HTTP/S').constant('DOMAIN')."/view/inventario/index.php");//usado
define('URLTOLOGIN',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/login/index.php");//usado
define('URLTOLANDING',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/landpage/index.php");//usado
define('EDITUS',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/editarusuario/index.php");// usado
define('USERIMAGE',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/images/userphoto/");//usado en todas las paginas
define('VRLOGO',constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/images/Logo_VR_Small.png");//usado en todas las paginas
define('FAVICON',"<link rel='shortcut icon' type='image/ico' href='".constant('HTTP/S').constant('DOMAIN')."/test/visor/controlterrestre/view/images/favicon.ico'/>");//usado en todas las paginas
//define('visor',constant('HTTP/S').constant('DOMAIN2').".com");//
define('COPYRIGHT',"©2025 Todos los derechos reservados.");// usado en todas las paginas
define("GMPKEY","AIzaSyAACbwwV60OuXFXueePpTsto0sZxqSZkfA");// llave para usar maps para sacar coordenadas
define("ESTACIONVR","test/visionremota");//se conecta a visionexterno
define("URLVR",constant('HTTP/S').constant('DOMAIN')."/".constant("ESTACIONVR")."/view/");
define("PATHCDBVRM",$_SERVER['DOCUMENT_ROOT']."/".constant("ESTACIONVR")."/classes/");
define("APILANGGMP","es-419");
define("PATHROLCP",$_SERVER['DOCUMENT_ROOT']."/"."cartaporte"."/view/opciones/roles.json");
?>
