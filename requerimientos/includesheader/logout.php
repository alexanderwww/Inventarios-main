<?php
session_start();
	if(isset($_REQUEST['cerrar'])){
		session_unset();
		//$_SESSION['nusuario']="";
		//unset($_SESSION['nusuario']);
		require_once "../includesheader/https.php";
		class fromHttps extends urlHttps
		{
			public function getStatusHttps()
			{

				return $this-> statusHttps;
			}

		}
		if (isset($_SERVER['HTTPS'])) {
		  $urlOb=new urlHttps($_SERVER['HTTPS']);
		  $urlOb->toHeader();
		}else{
		  $urlOb=new urlHttps('');
		 $urlOb->toHeader();
		}
		$urlData;
		if (isset($_SERVER['HTTPS'])) {
		  $urlOb=new fromHttps($_SERVER['HTTPS']);
		  $urlData=$urlOb->getStatusHttps();
		}else{
		  $urlOb=new fromHttps('');
		  $urlData=$urlOb->getStatusHttps();
		}
		if (($urlData!=null)) {
			$uri='https://';
		}else{
			$uri='http://';
		}
	    $domain = $uri.$_SERVER['SERVER_NAME'];
		$proyectName=explode("/",$_SERVER['PHP_SELF']);
		$url=$domain.'/'.$proyectName[1].'/inventarios/view/login/index.php';
		echo "<script>window.location.href='".$url."';</script>";
		session_destroy();
	}
 ?>