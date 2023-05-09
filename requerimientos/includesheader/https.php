<?php
class urlHttps{
    public function __construct($varHttps1){
      $this-> statusHttps = true; //Poner false para desactiva el completado https en la url
      $this-> varHttps = $varHttps1;
    }
  public function toHeaderFinal(){
    define('HTTP/S','https://');
    $url=constant('HTTP/S').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('Location: '.$url);
    exit;
  }
  public function toHeader(){
    switch ($this-> varHttps) {
    case 'on':
        defined('HTTP/S') or define('HTTP/S', 'https://');
    break;     
    default:
    if ($this-> statusHttps==true) {
      $this->toHeaderFinal();
    }else{
      define('HTTP/S','http://');
    }
    break;
    }
  }
}
if (isset($_SERVER['HTTPS'])) {
  $urlOb=new urlHttps($_SERVER['HTTPS']);
  $urlData=$urlOb->toHeader();
}else{
  $urlOb=new urlHttps('');
  $urlData=$urlOb->toHeader();
}
 ?>