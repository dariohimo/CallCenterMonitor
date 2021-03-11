<?php namespace Config;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
class Request{
  private $controlador;
  private $metodo;
  private $argumento;

  public function __construct(){	  
    if(isset($_GET['url'])){
      $ruta = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
      $ruta = explode("/", $ruta);
      $ruta = array_filter($ruta);
      //print_r($ruta);
      if($ruta[0] == "index.php"){
        $this->controlador = "colas";
      }else{
        $this->controlador = strtolower(array_shift($ruta));
      }
      $this->metodo = strtolower(array_shift($ruta));
      if(!$this->metodo){
        $this->metodo = "index";
        //$this->argumento = "default";
      //}else{

      //}
      //echo $this->controlador ."-". $this->metodo ."-". $this->argumento;
     }
     $this->argumento = $ruta;
  }else{
    $this->controlador = "colas";
    $this->metodo = "index";
  }
}

  public function getControlador(){
    return $this->controlador;
  }

  public function getMetodo(){
    return $this->metodo;
  }

  public function getArgumento(){
    return $this->argumento;
  }
}
?>
