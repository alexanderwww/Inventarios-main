<?php
// require_once "../../includes/autocarga.inc.php";
require_once "../../../view/login/controlador/loginCtrl.php";
$dbObj = new loginCtrl();
$fechaActual = date('Y-m-d');
$sth = $dbObj->GetAllDataTC();
$tipoCambio = $sth->fetch_assoc();
if($tipoCambio != ''){
    $dbObj->UpdateTableTC($tipoCambio['tipo_cambio']);
}else{
    $tipoCambio = '';
}
if (empty($tipoCambio)) {
	$datePlus = new DateTime();
    // print_r($datePlus);
    
  $datePlus->modify('+1 day');
//   print_r($datePlus);
  $date = $datePlus->format('Y-m-d') . '/' . $datePlus->format('Y-m-d');
  $token = 'dcd256707fdb6befb6b566404269d9bfe04aa8c1d70abb6ddb4534d436901881';
  $query = 'https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF60653/datos/' . $date . '?token='.$token;
  $json = json_decode(file_get_contents($query), true);
  $tipoCambio = $json['bmx']['series'][0]['datos'][0]['dato'];
  if ($tipoCambio) {
		$dbObj->SetTableTC("INSERT INTO tipos_cambio (fecha, tipo_cambio) VALUES ('".$fechaActual."',".$tipoCambio.")");
		$dbObj->SetTableTC("INSERT INTO config (TipoCambio) VALUES (".$tipoCambio.")");
    echo "El tipo de cambio para el día de hoy es de " . $tipoCambio.' Se a guardado';
    }
  } else {
    echo "El tipo de cambio para el día de hoy es de " . $tipoCambio['tipo_cambio'];
  }
 ?>