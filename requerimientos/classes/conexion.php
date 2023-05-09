<?php
class conexion
{

private $usrDB = "root";
private $passDB = "";
private $host = "localhost";
private $database = "storage_in";

  protected function connect()
  {
    $conn = new mysqli($this->host, $this->usrDB, $this->passDB, $this->database);
    // Check connection
    if ($conn -> connect_errno) {
      $errorlog[] = array("msg" => 'Error de Conexión '.$conn->connect_errno.$conn->connect_error);
      die('<b>Error de Conexión Código : </b>'.$conn->connect_errno.'</br><b>Descripción de error: </b>'.$conn->connect_error);
    }else {
      return $conn;
    }

  }
}
?>
