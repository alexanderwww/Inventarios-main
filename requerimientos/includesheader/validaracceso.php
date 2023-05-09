<?php
	$host= $_SERVER["HTTP_HOST"];
	$recUri= $_SERVER["REQUEST_URI"];
	$urlCompleto=constant('HTTP/S').$host .$recUri;
	$url=parse_url($urlCompleto);
	$dir=explode("/",$url["path"]);                      
	if (!in_array($dir[5],$_SESSION['listaAcceso'])) {
		//echo "<script>window.location.href='".constant('URLLOGOUT')."';</script>";
		header('Location: '.constant('URLLOGOUT'));
		exit;
	}
?>